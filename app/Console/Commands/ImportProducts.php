<?php

namespace App\Console\Commands;

use App\Factories\ProductImportFactory;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products
                            {source : Source file or url} 
                            {type : Type of the source [api | csv | xml | json ...]}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->argument('source');
        $type = $this->argument('type');

        $importer = app(ProductImportFactory::class)->create($type);
        $importer->import($source);
    }
}
