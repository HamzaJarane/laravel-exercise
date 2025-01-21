# Factories

## Overview
The `ProductImportFactory` is a factory class responsible for dynamically creating instances of product importers based on the type of source data. It allows the registration of importer classes and ensures type-based instantiation during runtime. 

---

## Key Features
1. **Dynamic Importer Registration**: Enables associating specific import types (e.g., `csv`, `json`) with importer classes.
2. **Importer Creation**: Generates instances of registered importer classes based on the requested type.
3. **Error Handling**: Throws an exception if an unsupported import type is requested.

---

## Methods

### `register(string $type, string $importerClass): void`
- **Purpose**: Registers a new importer class for a specific type of data source.
- **Parameters**:
  - `type` (string): The type identifier for the importer (e.g., `csv`, `json`, `xml`).
  - `importerClass` (string): The fully qualified class name of the importer.
- **Returns**: `void`
- **Usage Example**:
  ```php
  $factory = new ProductImportFactory();
  $factory::register('csv', CsvImporter::class);
  ```

---

### `create(string $type): ImporterInterface`
- **Purpose**: Creates an instance of the importer class registered for the specified type.
- **Parameters**:
  - `type` (string): The type of importer to create.
- **Returns**: An instance of a class implementing the `ImporterInterface`.
- **Throws**:
  - `Exception`: If the requested `type` is not registered.
- **Usage Example**:
  ```php
  $importer = $factory->create('csv'); // Returns an instance of CsvImporter
  ```

---

## Static Property

### `self::$importers`
- **Type**: `array`
- **Purpose**: Stores mappings of types to their corresponding importer classes.
- **Example Structure**:
  ```php
  [
      'csv' => CsvImporter::class,
      'json' => JsonImporter::class,
  ]
  ```

---

## Error Handling
- **Unsupported Type**: If a type is not registered using `register`, calling `create` with that type will throw an `Exception`:
  ```php
  throw new Exception("Unsupported import type: {$type}");
  ```

---
