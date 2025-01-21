# Import Products Command

## Overview
The `ImportProducts` console command is used to import product data into the system from various sources. The command accepts two arguments: the source of the data and the type of the source (e.g., API, CSV, XML, JSON). It utilizes the `ProductImportFactory` to dynamically create an appropriate importer based on the type provided.

---

## Command Signature
```bash
php artisan import:products {source} {type}
```

### Arguments
1. **`source`** (required):  
   - Description: The source of the product data. This can be a file path, URL, or another supported input.  

2. **`type`** (required):  
   - Description: The type of the source, indicating the format of the data. Supported values depend on the factory configuration but could include `api`, `csv`, `xml`, `json`, etc.  

---

## Methods

### `handle()`
- **Purpose**: Executes the logic for importing products.
- **Steps**:
  1. Retrieves the `source` and `type` arguments from the command input.
  2. Uses the `ProductImportFactory` to create an importer instance tailored to the specified `type`.
  3. Calls the `import` method on the generated importer, passing the `source`.

---

## Example Usage
### Import Products from a CSV File
```bash
php artisan import:products storage/products.csv csv
```
