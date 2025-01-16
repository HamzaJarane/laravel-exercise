

# Import Products Command

## Overview
The `ImportProducts` class is a Laravel console command used to import product data into the database from a CSV file or an API source. It offers various options for controlling the import behavior and provides feedback during the process, the script uses league/csv package to read the csv file and convert it to associative array.

---

### Options:
- `--onlyApi`: Use the API instead of the CSV file.
- `--api`: Import products from both CSV and API.
- `--sku`: Generate a random SKU if not set.
- `--debug`: Run the command in debug mode.
- `--throw`: Throw an error if product creation fails.
- `--force`: Force database commit without confirmation.
- `--soft`: Simulate the process without committing changes.
- `--file`: Specify a CSV file from `database/seeders`.
- `--url`: Specify a custom API endpoint.
- `--max`: Limit the number of rows processed.

---

## Class Properties
### Counters and Status Tracking
- `$productsSucceed`, `$productsUpdated`, `$productsSkipped`, `$productsFailed`, `$productsRemoved`
- `$proceededProducts`, `$totalRows`

### Flags and Options
- `$api`, `$onlyApi`, `$randomSku`, `$debugMode`, `$forceMode`, `$softMode`, `$throwMode`

### CSV and API
- `$csv`: CSV Reader instance
- `$httpClient`: Guzzle Client instance

### Console Output
- `$consoleOutput`: Console output manager
- `$counterLine`: Tracks the progress line in the console

---

## Constructor
The constructor initializes services, CSV reader, HTTP client, and Faker for generating test data.

```php
public function __construct()
```

---

## Methods
### `handle()`
Executes the command, initializing the environment, performing imports, and handling transactions.

### `setUpEnvironment()`
Initializes the import environment by processing command-line options and setting up CSV and console output.

### `importCsvProducts()`
Imports products from the CSV file and inserts or updates them in the database.

### `importApiProducts()`
Fetches product data from a specified API and inserts or updates products in the database.

### `deleteExpiredProducts()`
Deletes products from the database if they are not present in the current import, or flagged as deleted.

### `syncChanges(Closure $commit, Closure $rollback)`
Handles database transactions based on the provided commit and rollback closures.

### `showDebugInformation()`
Displays detailed debug information if enabled.

### `updateCounter()`
Updates the progress counter displayed in the console.

### `getApiData()`
Fetches product data from the specified API endpoint using Guzzle.

---

## Usage Example
```bash
php artisan import:products --sku --max=500 --throw
```

---

## Error Handling
- If the `--throw` flag is enabled, exceptions are thrown on import failure.
- Failed products are logged for review if debug is enabled.

---

# Events

### Overview
The following event classes handle different stages of the product lifecycle within the application. Each event carries a `Product` model instance and can be dispatched when the respective product action occurs.

### Common Structure
All four event classes share the same structure:

- **Namespace:** `App\Events\Product`
- **Model:** `App\Models\Product`
- **Traits Used:**
  - `Dispatchable`: Allows the event to be dispatched easily.
  - `SerializesModels`: Ensures the `Product` model is serialized when queued.

### Events

#### ProductCreated
**Description:** Triggered when a new product is created.

**Constructor:**
```php
public function __construct(public Product $product) {}
```

---

#### ProductDeleted
**Description:** Triggered when a product is deleted.

**Constructor:**
```php
public function __construct(public Product $product) {}
```

---

#### ProductRestored
**Description:** Triggered when a deleted product is restored.

**Constructor:**
```php
public function __construct(public Product $product) {}
```

---

#### ProductUpdated
**Description:** Triggered when a product's details are updated.

**Constructor:**
```php
public function __construct(public Product $product) {}
```

### Usage
These events can be dispatched using Laravel's event dispatcher:
```php
ProductCreated::dispatch($product);
```

# Interfaces

### Overview
The `BaseService` interface defines a contract for common service operations on Eloquent models. It provides a standardized set of methods for basic CRUD operations and data retrieval.

### Methods

#### getModel(): Model
**Description:**
- Returns the Eloquent model instance associated with the service.

**Return Type:**
- `Model`

---

#### all()
**Description:**
- Retrieves all records from the model's database table.

**Return Type:**
- Collection of model instances

---

#### create(array $data)
**Description:**
- Creates a new record in the model's database table using the provided data.

**Parameters:**
- `array $data`: The data to be used for creating the record.

**Return Type:**
- Newly created model instance

---

#### update(array $data, $id)
**Description:**
- Updates an existing record identified by its ID with the provided data.

**Parameters:**
- `array $data`: The data to update the record with.
- `$id`: The identifier of the record to be updated.

**Return Type:**
- Updated model instance

---

#### delete($id)
**Description:**
- Deletes a record from the model's database table using the specified ID.

**Parameters:**
- `$id`: The identifier of the record to be deleted.

**Return Type:**
- `bool` (true if deletion was successful, false otherwise)

---

#### find($id)
**Description:**
- Finds and retrieves a record by its ID.

**Parameters:**
- `$id`: The identifier of the record to retrieve.

**Return Type:**
- Model instance if found, null otherwise

### Usage Example
```php
class ProductService implements BaseService
{
    public function getModel(): Model
    {
        return new Product();
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    ...
}
```

# Listeners:

## Overview
Event listeners handle specific events triggered by the application and define the logic executed when those events occur.

---

## `NotifyCustomers`

**Namespace:** `App\Listeners\Notifications`

### Purpose
The `NotifyCustomers` listener is responsible for handling customer notifications when a product is updated.

### Listens For
- `ProductUpdated`

### Constructor
The constructor does not perform any operations.

### Methods
- `handle(ProductUpdated $event): void`
  - This method is triggered when a `ProductUpdated` event is dispatched. Currently, it does not implement any logic but serves as a placeholder for future functionality.

---

## `NotifyWareHouse`

**Namespace:** `App\Listeners\Notifications`

### Purpose
The `NotifyWareHouse` listener manages notifications for the warehouse when a product is created or deleted.

### Listens For
- `ProductCreated`
- `ProductDeleted`

### Constructor
The constructor does not perform any operations.

### Methods
- `handle(ProductCreated|ProductDeleted $event): void`
  - This method is triggered when either a `ProductCreated` or `ProductDeleted` event is dispatched. The method is prepared to implement logic for notifying the warehouse.

---

## `SyncThirdPartyApplication`

**Namespace:** `App\Listeners`

### Purpose
The `SyncThirdPartyApplication` listener synchronizes product data with an external third-party application when product-related events occur.

### Listens For
- `ProductCreated`
- `ProductUpdated`
- `ProductDeleted`
- `ProductRestored`

### Constructor
The constructor does not perform any operations.

### Methods
- `handle(ProductCreated|ProductUpdated|ProductDeleted|ProductRestored $event): void`
  - This method is triggered for various product-related events and is intended to contain the logic for syncing product data with a third-party service.

# Models

## Product Model

The `Product` model represents a product entity within the application, defining attributes, relationships, and behaviors associated with products.

### Attributes:
- `id`: (int) The unique identifier for the product.
- `name`: (string) Name of the product.
- `image`: (string) URL or path to the product image.
- `sku`: (string) Stock Keeping Unit, a unique identifier for the product.
- `status`: (string) Current status of the product, constrained by the `STATUS` constant.
- `price`: (float) Price of the product.
- `currency`: (string) Currency code for the price.
- `quantity`: (int) Quantity available for the product.
- `delete_reason`: (string|null) Reason for soft deletion, if applicable.

### Constants:
- `HIDDEN`, `SALE`, `OUT`, `DELETED`, `PENDING`: Predefined status values.
- `STATUS`: Array containing all the available product statuses.

### Relationships:
- `variations()`: One-to-Many relationship with `ProductVariation`. Each product can have multiple variations.

### Accessors:
- `productQuantity()`: Returns an array containing quantities of variations if present, otherwise returns the base product's quantity.

### Observers:
- The model is observed by `ProductObserver` using the `#[ObservedBy]` attribute.

### Traits:
- `HasFactory`: Provides factory support for the model.
- `SoftDeletes`: Enables soft deleting functionality.

---

## ProductVariation Model

The `ProductVariation` model represents individual variations of a product, such as different sizes or colors.

### Attributes:
- `payload`: (json) Additional data for the variation, stored as a JSON object.
- `quantity`: (int) Quantity available for the variation.
- `availability`: (string) Availability status of the variation, constrained by the `AVAILABILITY` constant.

### Constants:
- `AVAILABLE`: Indicates the variation is available.
- `UNAVAILABLE`: Indicates the variation is not available.
- `AVAILABILITY`: Array containing both available and unavailable statuses.

### Relationships:
- `product()`: Belongs to a `Product`. Each variation belongs to a single product.

### Attribute Casting:
- `payload`: Casts the payload attribute to a JSON object.

### Accessors:
- `productAvailability()`: Returns `AVAILABLE` if the quantity is greater than zero, otherwise returns `UNAVAILABLE`.

---

# ObServers

## Overview
The `ProductObserver` class is an observer designed to handle lifecycle events related to the `Product` model in a Laravel application. It implements the `ShouldHandleEventsAfterCommit` interface to ensure that events are dispatched only after a successful database transaction commit.

## Purpose
The `ProductObserver` listens for various lifecycle events on the `Product` model and dispatches corresponding events when they occur.

## Event Handling Methods

### `created(Product $product): void`
Dispatched after a `Product` is created.
```php
public function created(Product $product): void
{
    ProductCreated::dispatch($product);
}
```

### `updated(Product $product): void`
Dispatched after a `Product` is updated.
```php
public function updated(Product $product): void
{
    ProductUpdated::dispatch($product);
}
```

### `deleted(Product $product): void`
Dispatched after a `Product` is deleted (soft delete included).
```php
public function deleted(Product $product): void
{
    ProductDeleted::dispatch($product);
}
```

### `restored(Product $product): void`
Dispatched when a previously deleted `Product` is restored.
```php
public function restored(Product $product): void
{
    ProductRestored::dispatch($product);
}
```

### `forceDeleted(Product $product): void`
Dispatched when a `Product` is permanently deleted.
```php
public function forceDeleted(Product $product): void
{
    ProductDeleted::dispatch($product);
}
```

# Tests

## 1. **`EventsTest`**
This test class verifies that specific events are dispatched when actions (create, update, delete, and restore) are performed on the `Product` model.

### **Test Class Setup**
- The `setUp` method ensures that the specified events are faked using `Event::fake()`. This prevents actual event listeners from being triggered during the test.

### **Test Method: `test_model_should_dispatch_events`**
#### **Purpose**
To verify that the following events are dispatched when actions are performed on the `Product` model:
1. `ProductCreated`
2. `ProductUpdated`
3. `ProductDeleted`
4. `ProductRestored`

#### **Steps**
1. **Create a Product**: Ensures `ProductCreated` is dispatched.
2. **Update the Product**: Ensures `ProductUpdated` is dispatched.
3. **Delete the Product**: Ensures `ProductDeleted` is dispatched.
4. **Restore the Product**: Ensures `ProductRestored` is dispatched.

#### **Assertions**
- Uses `Event::assertDispatched` to verify that the correct events were dispatched with the expected product ID.

---

## 2. **`ImportProductsCommandTest`**
This test class ensures the `import:products` Artisan command executes successfully without errors, even under specific conditions.

### **Test Method: `test_should_not_throw_an_exception_for_any_row`**
#### **Purpose**
To ensure the `import:products` command does not fail or throw an exception.

#### **Steps**
1. Execute the Artisan command `import:products` with various options (`--api`, `--sku`, `--throw`, `--soft`, `--max`).
2. Capture the exit code.

#### **Assertions**
- Confirms that the exit code is `0`, indicating successful execution.

---

## 3. **`ProductShouldBeCreatedTest`**
This test class verifies basic lifecycle operations for the `Product` model, specifically creation and forced deletion.

### **Test Method: `test_model_should_create_and_delete`**
#### **Purpose**
To ensure the `Product` model can be created and deleted correctly.

#### **Steps**
1. **Create a Product**: Asserts that the model exists in the database.
2. **Force Delete the Product**: Asserts that the model no longer exists in the database.

#### **Assertions**
- Uses `assertModelExists` to confirm the model is present.
- Uses `assertModelMissing` to confirm the model is removed.

---

## 4. **`ProductShouldSoftDeletedTest`**
This test class verifies the soft delete functionality of the `Product` model.

### **Test Method: `test_model_should_be_soft_deleted`**
#### **Purpose**
To ensure the `Product` model can be soft-deleted correctly.

#### **Steps**
1. **Create a Product**: Asserts that the model exists in the database.
2. **Soft Delete the Product**: Asserts that the model is soft-deleted but still exists in the database.

#### **Assertions**
- Uses `assertModelExists` to confirm the model is present before deletion.
- Uses `assertSoftDeleted` to confirm the model is soft-deleted.
