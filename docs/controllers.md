
# Controllers

## Overview
The `ProductController` handles API requests related to the `Product` model, for listing and displaying products.

## Methods

### `index()`
**Description:**
Returns a collection of all products in the database.

**Usage:**
```http
GET /api/products
```

**Returns:**
- A JSON response containing all products wrapped in the `ProductCollection` resource.

**Example:**
```json
[
   {
      "id": 1,
      "name": "Product Name",
      "image": "image_url",
      "sku": "12345",
      "status": "sale",
      "price": 100,
      "currency": "USD",
      "quantity": 50,
      "product_quantity": [50],
      "variations": [],
   }
]
```

---

### `show(Product $product)`
**Description:**
Returns a single product's details by its ID using route model binding.

**Usage:**
```http
GET /api/products/{id}
```

**Parameters:**
- `Product $product` (Automatically resolved by route model binding)

**Returns:**
- A JSON response containing the product details wrapped in the `ProductCollection` resource.

**Example:**
```json
{
  "id": 1,
  "name": "Product Name",
  "image": "image_url",
  "sku": "12345",
  "status": "sale",
  "price": 100,
  "currency": "USD",
  "quantity": 50,
  "product_quantity": [50],
  "variations": [],
}
```

## Resource Integration
- **`ProductCollection`** is used to standardize the output format for both multiple and single product responses.