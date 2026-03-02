# JavaScript Files Structure

## Overview
This directory contains organized JavaScript modules for the distro website.

## Files

### 1. tailwind-config.js
- **Purpose**: Tailwind CSS configuration
- **Loaded on**: All pages
- **Dependencies**: None

### 2. search.js
- **Purpose**: Product search functionality with modal integration
- **Loaded on**: index.php, product.php
- **Features**:
  - Real-time search with debouncing (300ms)
  - AJAX requests to `/distro/api/produk/search.php`
  - Results open in product modal instead of navigation
  - Click outside to close dropdown
- **Dependencies**: product-modal.js (for openProductModal function)

### 3. product-modal.js
- **Purpose**: Product modal functionality
- **Loaded on**: index.php, product.php
- **Features**:
  - AJAX fetch product details from `../api/product-details.php`
  - Image gallery with thumbnails and navigation
  - Product information display (name, price, stock, etc.)
  - Shop links (Shopee, TikTok)
  - Escape key to close modal
- **Dependencies**: None

### 4. product-filter.js
- **Purpose**: Product filtering and pagination functionality
- **Loaded on**: product.php
- **Features**:
  - Dynamic product filtering (price, size, brand, category)
  - AJAX loading of products, brands, and sizes
  - Pagination with ellipsis
  - Active filters display with remove functionality
  - Real-time filter updates
- **Dependencies**: product-modal.js (for openProductModal function)

### 5. newarrival_touch.js
- **Purpose**: Touch and carousel functionality for new arrivals section
- **Loaded on**: index.php
- **Features**:
  - Touch/swipe gestures
  - Carousel navigation
- **Dependencies**: None

## Loading Order

### For index.php
```html
<script src="script/tailwind-config.js"></script>
<script src="script/product-modal.js"></script>
<script src="script/search.js"></script>
<script src="script/newarrival_touch.js"></script>
```

### For product.php
```html
<script src="script/tailwind-config.js"></script>
<script src="script/product-modal.js"></script>
<script src="script/search.js"></script>
<script src="script/product-filter.js"></script>
```

## Benefits
1. **Modularity**: Each file has a single responsibility
2. **Maintainability**: Easier to debug and update specific functionality
3. **Reusability**: Files can be used across multiple pages
4. **Performance**: Better caching and parallel loading
5. **Clean HTML**: Separation of concerns between structure and behavior

## Notes
- search.js checks for element existence before initializing
- product-modal.js includes defensive programming with null checks
- All files use modern JavaScript (ES6+ features)
- Consistent coding style and error handling across files
