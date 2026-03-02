// Product Filter and Pagination functionality
let allProducts = [];
let filteredProducts = [];
let currentFilters = {
    category: '',
    price: null,
    size: null,
    brand: null
};

// Load products, brands, and sizes from API
async function loadProductsAndBrands() {
    try {
        const response = await fetch('../api/produk/list.php');
        const data = await response.json();
        
        if (data.success && data.data) {
            allProducts = data.data;
            filteredProducts = [...allProducts];
            
            // Load brands from merk field
            const brands = [...new Set(allProducts.map(product => product.merk)
                .filter(brand => brand && brand.trim() !== '')
                .map(brand => brand.trim()))];
            brands.sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
            
            const brandList = document.getElementById('brand-list');
            if (brandList) {
                brandList.innerHTML = '';
                
                brands.forEach(brand => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark';
                    li.setAttribute('data-brand', brand);
                    li.textContent = brand;
                    brandList.appendChild(li);
                });
            }

            // Load sizes from ukuran field
            const sizes = [...new Set(allProducts.map(product => product.ukuran).filter(size => size && size.trim() !== ''))];
            sizes.sort((a, b) => {
                // Custom sort: S, M, L, XL, XXL, etc.
                const sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                const aIndex = sizeOrder.indexOf(a.toUpperCase());
                const bIndex = sizeOrder.indexOf(b.toUpperCase());
                
                if (aIndex !== -1 && bIndex !== -1) {
                    return aIndex - bIndex;
                } else if (aIndex !== -1) {
                    return -1;
                } else if (bIndex !== -1) {
                    return 1;
                } else {
                    return a.localeCompare(b);
                }
            });
            
            const sizeList = document.getElementById('size-list');
            if (sizeList) {
                sizeList.innerHTML = '';
                
                sizes.forEach(size => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark';
                    li.setAttribute('data-size', size);
                    li.textContent = size;
                    sizeList.appendChild(li);
                });

                // Add "Unknown" option for products without size
                const unknownLi = document.createElement('li');
                unknownLi.className = 'px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark';
                unknownLi.setAttribute('data-size', 'Unknown');
                unknownLi.textContent = 'Unknown';
                sizeList.appendChild(unknownLi);
            }
        }
    } catch (error) {
        console.error('Error loading products and brands:', error);
    }
}

// Filter products based on current filters
function filterProducts() {
    filteredProducts = allProducts.filter(product => {
        // Category filter (only apply if not already filtered by PHP)
        if (currentFilters.category && document.location.search.includes('kategori=') === false) {
            if (!product.nama_kategori || 
                product.nama_kategori.toLowerCase() !== currentFilters.category.toLowerCase()) {
                return false;
            }
        }

        // Price filter
        if (currentFilters.price) {
            const price = parseInt(product.harga_aktif);
            switch (currentFilters.price) {
                case 'under100000':
                    if (price >= 100000) return false;
                    break;
                case '100000-500000':
                    if (price < 100000 || price > 500000) return false;
                    break;
                case '500000-1000000':
                    if (price < 500000 || price > 1000000) return false;
                    break;
            }
        }

        // Size filter
        if (currentFilters.size && currentFilters.size !== 'Unknown') {
            if (!product.ukuran || product.ukuran.trim() === '') {
                return false;
            }
            // Exact match for size from API ukuran field
            if (product.ukuran.toUpperCase() !== currentFilters.size.toUpperCase()) {
                return false;
            }
        } else if (currentFilters.size === 'Unknown') {
            // Show products with no size or empty size
            if (product.ukuran && product.ukuran.trim() !== '') {
                return false;
            }
        }

        // Brand filter
        if (currentFilters.brand) {
            if (!product.merk || product.merk.trim() === '' || product.merk.trim().toLowerCase() !== currentFilters.brand.trim().toLowerCase()) {
                return false;
            }
        }

        return true;
    });

    // Update display
    updateProductDisplay();
    
    // Update active filters display
    updateActiveFiltersDisplay();
}

// Update product display with filtered results and pagination
function updateProductDisplay() {
    const grid = document.querySelector('.grid.grid-cols-2');
    if (!grid) return;

    if (filteredProducts.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-secondary-light dark:text-secondary-dark">No products found matching your filters.</p></div>';
        // Hide pagination when no products
        const paginationContainer = document.querySelector('.flex.justify-center.mt-12');
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        return;
    }

    // Pagination setup
    const itemsPerPage = 12;
    let currentPage = 1; // Reset to page 1 when filters change
    
    // Get products for current page
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentProducts = filteredProducts.slice(startIndex, endIndex);

    // Generate product HTML
    grid.innerHTML = currentProducts.map(product => {
        const inStock = parseInt(product.jumlah_stok) > 0;
        const stockStatus = inStock 
            ? '<div class="absolute bottom-2 right-2 z-10 rounded-lg bg-green-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">In Stock</div>'
            : '<div class="absolute bottom-2 right-2 z-10 rounded-lg bg-red-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">Out of Stock</div>';

        const soldBadge = parseInt(product.terjual) > 0 
            ? `<div class="absolute bottom-2 left-2 z-10 rounded-lg bg-black/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">${numberFormat(product.terjual)} sold</div>`
            : '';

        const originalPrice = parseInt(product.harga_coret) > 0 
            ? `<p class="text-sm font-normal line-through" style="color: #9a4c66;">Rp ${numberFormat(product.harga_coret)}</p>`
            : '';

        return `
            <div class="group flex flex-col gap-3 cursor-pointer" onclick="openProductModal(${product.id_produk})">
                <div class="relative overflow-hidden rounded-xl">
                    ${soldBadge}
                    ${stockStatus}
                    <div class="absolute inset-0 aspect-[3/4] bg-cover bg-center transition-opacity duration-500 group-hover:opacity-0 bg-gray-200" style="background-image: url('images/${product.gambar1}');"></div>
                    <div class="aspect-[3/4] bg-cover bg-center opacity-0 transition-opacity duration-500 group-hover:opacity-100 bg-gray-200" style="background-image: url('images/${product.gambar2}');"></div>
                </div>
                <div>
                    <p class="text-base font-medium leading-normal">${product.nama_produk}</p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-normal leading-normal text-primary" style="color: #d41152;">Rp ${numberFormat(product.harga_aktif)}</p>
                        ${originalPrice}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    // Update pagination
    updatePagination(filteredProducts.length, itemsPerPage, currentPage);
}

// Update pagination controls
function updatePagination(totalItems, itemsPerPage, currentPage) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationContainer = document.querySelector('.flex.justify-center.mt-12');
    
    if (!paginationContainer || totalPages <= 1) {
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        return;
    }

    paginationContainer.style.display = 'flex';
    
    let paginationHTML = '<nav class="flex items-center gap-2">';
    
    // Previous button
    if (currentPage > 1) {
        paginationHTML += `<a href="#" onclick="changePage(${currentPage - 1}); return false;" class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light dark:text-secondary-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
            <span class="material-symbols-outlined">chevron_left</span>
        </a>`;
    } else {
        paginationHTML += `<button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
            <span class="material-symbols-outlined">chevron_left</span>
        </button>`;
    }

    // Page numbers with ellipsis
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    
    if (startPage > 1) {
        paginationHTML += `<a href="#" onclick="changePage(1); return false;" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">1</a>`;
        if (startPage > 2) {
            paginationHTML += '<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>';
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationHTML += `<button class="h-10 w-10 flex items-center justify-center rounded-full bg-primary text-white font-medium">${i}</button>`;
        } else {
            paginationHTML += `<a href="#" onclick="changePage(${i}); return false;" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">${i}</a>`;
        }
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationHTML += '<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>';
        }
        paginationHTML += `<a href="#" onclick="changePage(${totalPages}); return false;" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">${totalPages}</a>`;
    }

    // Next button
    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" onclick="changePage(${currentPage + 1}); return false;" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
            <span class="material-symbols-outlined">chevron_right</span>
        </a>`;
    } else {
        paginationHTML += `<button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
            <span class="material-symbols-outlined">chevron_right</span>
        </button>`;
    }
    
    paginationHTML += '</nav>';
    paginationContainer.innerHTML = paginationHTML;
}

// Change page
function changePage(page) {
    displayPage(page);
}

// Display specific page
function displayPage(page) {
    const grid = document.querySelector('.grid.grid-cols-2');
    if (!grid) return;

    const itemsPerPage = 12;
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentProducts = filteredProducts.slice(startIndex, endIndex);

    // Generate product HTML for this page
    grid.innerHTML = currentProducts.map(product => {
        const inStock = parseInt(product.jumlah_stok) > 0;
        const stockStatus = inStock 
            ? '<div class="absolute bottom-2 right-2 z-10 rounded-lg bg-green-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">In Stock</div>'
            : '<div class="absolute bottom-2 right-2 z-10 rounded-lg bg-red-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">Out of Stock</div>';

        const soldBadge = parseInt(product.terjual) > 0 
            ? `<div class="absolute bottom-2 left-2 z-10 rounded-lg bg-black/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">${numberFormat(product.terjual)} sold</div>`
            : '';

        const originalPrice = parseInt(product.harga_coret) > 0 
            ? `<p class="text-sm font-normal line-through" style="color: #9a4c66;">Rp ${numberFormat(product.harga_coret)}</p>`
            : '';

        return `
            <div class="group flex flex-col gap-3 cursor-pointer" onclick="openProductModal(${product.id_produk})">
                <div class="relative overflow-hidden rounded-xl">
                    ${soldBadge}
                    ${stockStatus}
                    <div class="absolute inset-0 aspect-[3/4] bg-cover bg-center transition-opacity duration-500 group-hover:opacity-0 bg-gray-200" style="background-image: url('images/${product.gambar1}');"></div>
                    <div class="aspect-[3/4] bg-cover bg-center opacity-0 transition-opacity duration-500 group-hover:opacity-100 bg-gray-200" style="background-image: url('images/${product.gambar2}');"></div>
                </div>
                <div>
                    <p class="text-base font-medium leading-normal">${product.nama_produk}</p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-normal leading-normal text-primary" style="color: #d41152;">Rp ${numberFormat(product.harga_aktif)}</p>
                        ${originalPrice}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    // Update pagination controls
    updatePagination(filteredProducts.length, itemsPerPage, page);
    
    // Scroll to top of products
    grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Helper function for number formatting
function numberFormat(num) {
    return new Intl.NumberFormat('id-ID').format(parseInt(num) || 0);
}

// Update active filters display
function updateActiveFiltersDisplay() {
    const activeFiltersDiv = document.getElementById('activeFilters');
    const filterTagsDiv = document.getElementById('filterTags');
    
    if (!activeFiltersDiv || !filterTagsDiv) return;
    
    // Create array of active filters
    const activeFilters = [];
    
    if (currentFilters.category) {
        activeFilters.push({ type: 'category', label: `Category: ${currentFilters.category}` });
    }
    
    if (currentFilters.price) {
        let priceLabel = '';
        switch(currentFilters.price) {
            case 'under100000':
                priceLabel = 'Price: Under 100.000';
                break;
            case '100000-500000':
                priceLabel = 'Price: 100.000-500.000';
                break;
            case '500000-1000000':
                priceLabel = 'Price: 500.000-1.000.000';
                break;
            case 'lowest':
                priceLabel = 'Price: Lowest';
                break;
            case 'highest':
                priceLabel = 'Price: Highest';
                break;
        }
        activeFilters.push({ type: 'price', label: priceLabel });
    }
    
    if (currentFilters.size && currentFilters.size !== 'Unknown') {
        activeFilters.push({ type: 'size', label: `Size: ${currentFilters.size}` });
    }
    
    if (currentFilters.brand) {
        activeFilters.push({ type: 'brand', label: `Brand: ${currentFilters.brand}` });
    }
    
    // Show/hide active filters section
    if (activeFilters.length > 0) {
        activeFiltersDiv.classList.remove('hidden');
        
        // Generate filter tags HTML
        filterTagsDiv.innerHTML = activeFilters.map(filter => `
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium">
                ${filter.label}
                <button class="hover:text-primary/80" onclick="removeFilter('${filter.type}')">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </span>
        `).join('');
    } else {
        activeFiltersDiv.classList.add('hidden');
    }
}

// Remove specific filter
function removeFilter(filterType) {
    if (filterType === 'category') {
        // Redirect to product.php without category parameter
        window.location.href = 'product.php';
        return;
    }
    currentFilters[filterType] = null;
    filterProducts();
}

// Clear all filters
function clearAllFilters() {
    if (currentFilters.category) {
        // Redirect to product.php without category parameter
        window.location.href = 'product.php';
        return;
    }
    currentFilters = {
        category: '',
        price: null,
        size: null,
        brand: null
    };
    filterProducts();
}

// Initialize filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on product page
    if (document.getElementById('product-page-content')) {
        loadProductsAndBrands();
        
        // Filter menu interactions
        document.addEventListener("click", function (e) {
            const buttons = document.querySelectorAll(".filter-btn");
            const menus = document.querySelectorAll(".filter-menu");

            buttons.forEach((btn) => {
                const target = document.getElementById(btn.dataset.target);

                if (btn.contains(e.target)) {
                    menus.forEach((menu) => {
                        if (menu !== target) menu.classList.add("hidden");
                    });
                    target.classList.toggle("hidden");
                }
            });

            if (![...buttons].some((btn) => btn.contains(e.target))) {
                menus.forEach((menu) => menu.classList.add("hidden"));
            }

            // Handle price filter clicks
            if (e.target.matches('[data-price]')) {
                const priceValue = e.target.getAttribute('data-price');
                currentFilters.price = currentFilters.price === priceValue ? null : priceValue;
                filterProducts();
            }

            // Handle size filter clicks
            if (e.target.matches('[data-size]')) {
                const sizeValue = e.target.getAttribute('data-size');
                currentFilters.size = currentFilters.size === sizeValue ? null : sizeValue;
                filterProducts();
            }

            // Handle brand filter clicks
            if (e.target.matches('[data-brand]')) {
                const brandValue = e.target.getAttribute('data-brand');
                currentFilters.brand = currentFilters.brand === brandValue ? null : brandValue;
                filterProducts();
            }
        });

        // Add clear all filters button listener
        const clearAllBtn = document.getElementById('clearAllFilters');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', clearAllFilters);
        }
    }
});
