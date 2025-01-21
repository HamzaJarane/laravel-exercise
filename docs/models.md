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
