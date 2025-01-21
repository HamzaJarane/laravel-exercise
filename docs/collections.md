# Collections

## Overview

The `ProductCollection` and `ProductVariationCollection` classes is are resource collections for transforming the `Product` and `ProductVariation` models instance into an API-friendly array structure. They extends Laravel's `JsonResource` to provide a standardized way to present product data in API responses.

#### ProductCollection - Properties Transformed:
- `id`: The unique identifier for the product.
- `name`: The name of the product.
- `image`: URL or path to the product image.
- `sku`: The stock-keeping unit for the product.
- `status`: The current status of the product.
- `price`: The price of the product.
- `currency`: The currency for the price.
- `quantity`: The base quantity of the product.
- `product_quantity`: The calculated product quantity considering variations.
- `variations`: A collection of product variations, loaded when the `variations` relationship is eager-loaded.

#### ProductVariationCollection - Properties Transformed:
- `id`: The unique identifier for the variation.
- `payload`: Additional data associated with the variation (stored as JSON).
- `quantity`: The available quantity for this variation.
- `availability`: The availability status of the variation.
- `product_availability`: A computed attribute reflecting the availability status based on quantity.





