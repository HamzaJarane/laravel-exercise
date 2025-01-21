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
