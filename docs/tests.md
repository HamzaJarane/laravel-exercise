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

