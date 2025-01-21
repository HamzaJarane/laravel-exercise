# Interfaces

## Overview
This documentation covers two interfaces, `BaseServiceInterface` and `ImporterInterface`. These interfaces define the contract for their respective services, ensuring consistency and standardization across implementations.

---

## **1. `BaseServiceInterface`**
The `BaseServiceInterface` defines methods for managing database models, including CRUD operations and retrieval of models.

### **Methods**

#### `getModel(): Model`
- **Purpose**: Retrieves the instance of the model associated with the service.
- **Returns**: 
  - An instance of the model (`Illuminate\Database\Eloquent\Model`).
- **Usage Example**:
  ```php
  $model = $service->getModel();
  ```

#### `all()`
- **Purpose**: Retrieves all records from the associated model.
- **Returns**: 
  - A collection of all records.
- **Usage Example**:
  ```php
  $records = $service->all();
  ```

#### `create(array $data)`
- **Purpose**: Creates a new record in the database.
- **Parameters**:
  - `data` (array): An associative array of data to create the record.
- **Returns**: 
  - The created model instance.
- **Usage Example**:
  ```php
  $newRecord = $service->create(['name' => 'Product 1']);
  ```

#### `update(array $data, $id)`
- **Purpose**: Updates an existing record by its ID.
- **Parameters**:
  - `data` (array): An associative array of updated data.
  - `id`: The identifier of the record to update.
- **Returns**: 
  - The updated model instance.
- **Usage Example**:
  ```php
  $updatedRecord = $service->update(['name' => 'Updated Product'], $id);
  ```

#### `delete($id)`
- **Purpose**: Deletes a record by its ID.
- **Parameters**:
  - `id`: The identifier of the record to delete.
- **Returns**: 
  - Boolean indicating success or failure.
- **Usage Example**:
  ```php
  $isDeleted = $service->delete($id);
  ```

#### `find($id)`
- **Purpose**: Finds a record by its ID.
- **Parameters**:
  - `id`: The identifier of the record to find.
- **Returns**: 
  - The model instance or `null` if not found.
- **Usage Example**:
  ```php
  $record = $service->find($id);
  ```

---

## **2. `ImporterInterface`**
The `ImporterInterface` is designed for importing and verifying data from external sources.

### **Methods**

#### `import(string $source): void`
- **Purpose**: Handles the process of importing data from a given source.
- **Parameters**:
  - `source` (string): The source file path or URL.
- **Returns**: 
  - `void`.
- **Usage Example**:
  ```php
  $importer->import('/path/to/file.csv');
  ```

#### `verify(string $source): bool`
- **Purpose**: Verifies the validity of the given source before importing.
- **Parameters**:
  - `source` (string): The source file path or URL to verify.
- **Returns**: 
  - `true` if the source is valid, `false` otherwise.
- **Usage Example**:
  ```php
  if ($importer->verify('/path/to/file.csv')) {
      $importer->import('/path/to/file.csv');
  }
  ```