# Providers

## Overview
The `AppServiceProvider` class is responsible for binding services and configurations into the Laravel service container. It includes the `register` and `boot` methods to handle service registration and application bootstrapping, respectively.

---

## Methods

### **1. `register()`**
This method is used to bind application services into the service container.

#### Key Functionality
- Registers the `ProductImportFactory` as a singleton.
- Dynamically registers different importers for the factory:
  - **`api`** → `Product\ApiImporter`
  - **`csv`** → `Product\CsvImporter`
  - **`xml`** → `Product\XmlImporter`
  - **`json`** → `Product\JsonImporter`

#### Implementation
```php
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
```

#### Notes
- **Singleton Pattern**: Ensures only one instance of the `ProductImportFactory` is created throughout the application's lifecycle.
- **Dynamic Registration**: The factory can support additional importers in the future by extending its registrations.