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
