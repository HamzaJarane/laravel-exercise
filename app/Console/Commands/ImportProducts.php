<?php

namespace App\Console\Commands;

use App\Models\ProductVariation;
use App\Services\ProductService;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
use Laravel\Prompts\Output\ConsoleOutput;
use League\Csv\Reader;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\Console\Style\OutputStyle;

class ImportProducts extends Command
{
    public function __construct()
    {
        parent::__construct();

        $this->productService = new ProductService();
        $this->httpClient = new Client();
        $this->faker = \Faker\Factory::create();
        $this->csv = Reader::createFromPath(database_path("seeders/products.csv"))->setHeaderOffset(0);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products 
                            {--onlyApi : Use the api instead of the csv file}
                            {--api : Insert the products from the api as well}
                            {--sku : Generate product sku when if its not set} 
                            {--debug : Run the script in debug mode}
                            {--throw : Throw an error if any of the products failed to create / update}
                            {--force : Force commit database changes} 
                            {--soft : Don\'t commit the database changes}
                            {--file= : Use an other file for csv data, it should be in database/seeders path}
                            {--url= : Use an other api url for the products data}
                            {--max= : Specify max number of rows to insert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports products into database';

    /**
     * Service to manage product-related operations.
     */
    protected ProductService $productService;

    /**
     * HTTP client for API requests.
     */
    protected Client $httpClient;

    /**
     * Faker instance for generating random data.
     */
    protected Generator $faker;

    /**
     * CSV reader for reading product data.
     */
    protected Reader $csv;

    /**
     * Tracks the number of successful product imports.
     */
    protected int $productsSucceed = 0;

    /**
     * Tracks the number of product updates.
     */
    protected int $productsUpdated = 0;

    /**
     * Tracks the number of skipped products.
     */
    protected int $productsSkipped = 0;

    /**
     * Tracks failed product imports with details.
     */
    protected array $productsFailed = [];

    /**
     * Tracks removed products during synchronization.
     */
    protected array $productsRemoved = [];

    /**
     * IDs of products to ignore during deletion.
     */
    protected array $shouldIgnoreIds = [];

    /**
     * Counter for the number of processed products.
     */
    protected int $proceededProducts = 0;

    /**
     * Indicates whether to use the API for product data.
     */
    protected bool $api;

    /**
     * Indicates whether to only use the API for product data.
     */
    protected bool $onlyApi;

    /**
     * Indicates whether to generate random SKUs for products.
     */
    protected bool $randomSku;

    /**
     * Indicates if the script is running in debug mode.
     */
    protected bool $debugMode;

    /**
     * Indicates if changes should be forcibly committed to the database.
     */
    protected bool $forceMode;

    /**
     * Indicates if database changes should not be committed.
     */
    protected bool $softMode;

    /**
     * Indicates if errors should be thrown during execution.
     */
    protected bool $throwMode;

    /**
     * Maximum number of rows to process.
     */
    protected int $maxRows;

    /**
     * File name for the CSV data source.
     */
    protected string|null $file;

    /**
     * Output handler for console messages.
     */
    protected ConsoleOutput|OutputStyle $consoleOutput;

    /**
     * Tracks the progress line for the console output.
     */
    protected string $counterLine;

    /**
     * Total number of rows in the CSV or API data.
     */
    protected int $totalRows;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setUpEnvironment();
        DB::beginTransaction();

        if (!$this->onlyApi) {
            $this->importCsvProducts();
            $this->deleteExpiredProducts();
        }

        if ($this->api || $this->onlyApi) {
            $this->importApiProducts();
        }

        if ($this->debugMode) {
            $this->showDebugInformation();
        }

        $this->syncChanges(
            fn() => DB::commit(),
            fn() => DB::rollBack(),
        );
    }

    /**
     * Sets up the environment based on command options.
     */
    public function setUpEnvironment()
    {
        $this->api = trim($this->option('api') ?? '') === '1';
        $this->onlyApi = trim($this->option('onlyApi') ?? '') === '1';

        $this->randomSku = trim($this->option('sku') ?? '') === '1';
        $this->debugMode = trim($this->option('debug') ?? '') === '1';
        $this->forceMode = trim($this->option('force') ?? '') === '1';
        $this->softMode = trim($this->option('soft') ?? '') === '1';
        $this->throwMode = trim($this->option('throw') ?? '') === '1';

        $this->maxRows = intval($this->option('max') ?? 0);
        $this->file = trim($this->option('file') ?? '') !== '' ? trim($this->option('file') ?? '') : null;

        if ($this->file !== null) {
            $this->csv = Reader::createFromPath(database_path("seeders/{$this->file}"))->setHeaderOffset(0);
        }

        $this->totalRows = $this->maxRows > 0 ? $this->maxRows : sizeof($this->csv);
    
        $this->consoleOutput = $this->output ?? new \Symfony\Component\Console\Output\ConsoleOutput();
        $this->consoleOutput->writeln("\n<info>Starting import process...</info>\n");
        
        // move the cursor up by one line and clear the entire line
        $this->counterLine = "\x1B[1A\x1B[2K";
        
        if($this->onlyApi) {
            $this->consoleOutput->writeln("Processed: 0 | Succeeded: 0 | Updated: 0 | Skipped: 0 | Removed: 0 | Failed: 0");
        } else {
            $this->consoleOutput->writeln("Processed: 0/{$this->totalRows} | Succeeded: 0 | Updated: 0 | Skipped: 0 | Removed: 0 | Failed: 0");
        }
    }

    /**
     * Displays debug information after processing products.
     */
    public function showDebugInformation()
    {
        if (sizeof($this->productsRemoved) > 0 && $this->confirm('Do you want to see the products that were removed?')) {
            foreach ($this->productsRemoved as $product) {
                $this->info('ID: ' . $product['id']);
                $this->info('SKU: ' . $product['sku']);
            }
        }

        if (sizeof($this->productsFailed) > 0 && $this->confirm('Do you want to see the products that were failed?')) {
            foreach ($this->productsFailed as $product) {
                $this->info('ID: ' . $product['id']);
                $this->info('SKU: ' . $product['sku']);
                $this->info('REASON: ' . $product['reason']);
                $this->newLine();
            }
        }
    }

    /**
     * Synchronizes changes to the database.
     *
     * @param \Closure $commit
     * @param \Closure $rollback
     */
    public function syncChanges(\Closure $commit, \Closure $rollback)
    {
        $commitChanges = function () use ($commit) {
            $commit();
            $this->info('Products were inserted successfully!');
        };

        $rollbackChanges = function () use ($rollback) {
            $rollback();
            $this->info('Rolled back!');
        };

        if($this->softMode) {
            $rollbackChanges();
        }

        if ($this->forceMode) {
            $commitChanges();
            return;
        }

        if ($this->productsSucceed > 0 || $this->productsFailed > 0) {
            if ($this->confirm('Do you want to commit the changes to the database?')) {
                $commitChanges();
            } else {
                $rollbackChanges();
            }
        }
    }

    /**
     * Imports products from the CSV file.
     */
    public function importCsvProducts()
    {
        $existingIds = $this->productService->getModel()
            ->whereIn('id', array_column((array) $this->csv, 'id'))
            ->withTrashed()
            ->pluck('id')
            ->flip()
            ->toArray();
    
        foreach ($this->csv as $product) {
            if ($this->maxRows > 0 && $this->proceededProducts == $this->maxRows) {
                break;
            }
            
            $this->shouldIgnoreIds[] = $product['id'];
            $this->proceededProducts++;

            if (isset($existingIds[$product['id']])) {
                $this->productsSkipped++;
                continue;
            }
    
            try {
                if ((!isset($product['sku']) || $product['sku'] === '') && $this->randomSku) {
                    $product['sku'] = strtoupper(implode('-', $this->faker->words(2)) . '-' . date('Y') . '-' . $this->faker->randomLetter . $this->faker->randomLetter);
                }
    
                $productInstance = $this->productService->create([
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'price' => floatval($product['price']),
                    'image' => $this->faker->imageUrl(),
                    'currency' => $product['currency'],
                    'quantity' => intval($product['quantity']),
                    'status' => trim($product['status'])
                ]);
    
                if (trim($product['variations']) !== '') {
                    $variations = json_decode($product['variations'], true);
                    if ($variations !== null && json_last_error() == JSON_ERROR_NONE) {
                        $variationData = array_map(function($variation) use ($product) {
                            return [
                                'payload' => [
                                    'name' => $variation['name'],
                                    'value' => $variation['value'],
                                ],
                                'quantity' => intval($product['quantity']),
                                'availability' => intval($product['quantity']) > 0 ? ProductVariation::AVAILABLE : ProductVariation::UNAVAILABLE
                            ];
                        }, $variations);
                        
                        $productInstance->variations()->createMany($variationData);
                    }
                }
    
                if (trim($product['status']) === $this->productService->getModel()::DELETED) {
                    $this->productsRemoved[] = [
                        'id' => $productInstance->id,
                        'sku' => $productInstance->sku,
                    ];
                    $productInstance->delete_reason = "synchronization";
                    $productInstance->save();
                    $productInstance->delete();
                }

                $this->productsSucceed++;
                $this->updateCounter();
            } catch (\Throwable $th) {
                $this->productsFailed[] = [
                    'id' => $product['id'],
                    'sku' => $product['sku'],
                    'reason' => $th->getMessage(),
                ];
                if($this->throwMode) {
                    throw new \Exception($th->getMessage());
                }
            }
        }
    }

    /**
     * Imports products from the API.
     */
    public function importApiProducts()
    {
        $data = $this->getApiData();
        foreach ($data as $product) {
            if ($this->maxRows > 0 && $this->proceededProducts == $this->maxRows)
                break;
            $this->productsSucceed++;

            try {
                $this->proceededProducts++;
                $productInstance = $this->productService->getModel()
                    ->where('name', trim($product['name']))
                    ->withTrashed()
                    ->first();

                if ($productInstance) {
                    $productInstance->update([
                        'price' => $product['price'],
                        'image' => $product['image'],
                    ]);
                    $this->productsUpdated++;
                } else {
                    $product['sku'] = strtoupper(implode('-', $this->faker->words(2)) . '-' . date('Y') . '-' . $this->faker->randomLetter . $this->faker->randomLetter);
                    $productInstance = $this->productService->getModel()->create([
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'price' => floatval($product['price']),
                        'image' => $product['image'],
                        'currency' => null,
                        'quantity' => 0,
                        'status' => $this->productService->getModel()::PENDING,
                    ]);

                    foreach ($product['variations'] as $variation) {
                        $productInstance->variations()->create([
                            'payload' => array_filter($variation, fn($key): bool => !str_contains(strtolower($key), 'id')),
                            'quantity' => intval($variation['quantity']),
                        ]);
                    }

                    $this->productsSucceed++;
                    $this->updateCounter();
                }
            } catch (\Throwable $th) {
                $this->productsFailed[] = [
                    'id' => $product['id'],
                    'sku' => $product['sku'],
                    'reason' => $th->getMessage(),
                ];
            }
        }
    }

    /**
     * Updates the progress counter in the console output.
     */
    public function updateCounter()
    {
        $this->consoleOutput->write($this->counterLine);
        if($this->onlyApi) {
            $this->consoleOutput->writeln(sprintf(
                "Processed: %d | Succeeded: %d | Updated: %d | Skipped: %d | Removed: %d | Failed: %d",
                $this->proceededProducts,
                $this->productsSucceed,
                $this->productsUpdated,
                $this->productsSkipped,
                sizeof($this->productsRemoved),
                sizeof($this->productsFailed),
            ));
        } else {
            $this->consoleOutput->writeln(sprintf(
                "Processed: %d/%d | Succeeded: %d | Updated: %d | Skipped: %d | Removed: %d | Failed: %d",
                $this->proceededProducts,
                $this->totalRows,
                $this->productsSucceed,
                $this->productsUpdated,
                $this->productsSkipped,
                sizeof($this->productsRemoved),
                sizeof($this->productsFailed),
            ));
        }
    }

    /**
     * Fetches product data from the API.
     *
     * @return array
     */
    public function getApiData()
    {
        $url = trim($this->option('url') ?? '') !== '' ? trim($this->option('url')) : "https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products";   
        try {
            $response = $this->httpClient->request('GET', $url, ['allow_redirects' => true]);    
        } catch (\Throwable $th) {
            $this->error('[API] Something went wrong, [ ' . $th->getMessage() . ' ]');
        }

        if (!isset($response) || $response->getStatusCode() !== 200) {
            throw new \Exception("Request failed with status: " . $response->getStatusCode());
        }
        
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Deletes expired products from the database.
     */
    public function deleteExpiredProducts()
    {
        $this->productService->getModel()
            ->query()
            ->whereNotIn("id", $this->shouldIgnoreIds)
            ->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    $this->productsRemoved[] = [
                        'id' => $product->id,
                        'sku' => $product->sku,
                    ];
                    
                    $product->update([
                        'delete_reason' => 'synchronization'
                    ]);
                    $product->delete();
                }
            });
    }
}
