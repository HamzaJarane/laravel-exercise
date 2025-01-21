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
