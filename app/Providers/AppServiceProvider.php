<?php

namespace App\Providers;

use App\Factories\ProductImportFactory;
use App\Importers\Product;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProductImportFactory::class, function () {
            $factory = new ProductImportFactory();

            $factory::register('api', Product\ApiImporter::class);
            $factory::register('csv', Product\CsvImporter::class);
            $factory::register('xml', Product\XmlImporter::class);
            $factory::register('json', Product\JsonImporter::class);
            
            return $factory;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
