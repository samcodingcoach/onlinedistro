// Mobile Menu Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuPanel = document.getElementById('mobile-menu-panel');
    const closeMobileMenu = document.getElementById('close-mobile-menu');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    const mobileSearchInput = document.getElementById('mobile-search-input');
    const mobileSearchResults = document.getElementById('mobile-search-results');
    const mobileSearchResultsContent = document.getElementById('mobile-search-results-content');
    // Toggle mobile menu
    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        setTimeout(() => {
            mobileMenuPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        // Change hamburger icon to X
        console.log('Changing icon to close');
        mobileMenuIcon.textContent = 'close';
        console.log('Icon textContent:', mobileMenuIcon.textContent);
    }

    function closeMobileMenuFunc() {
        mobileMenuPanel.classList.add('translate-x-full');
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = '';
            // Clear search when closing
            mobileSearchInput.value = '';
            mobileSearchResults.classList.add('hidden');
        }, 300);
        // Change X icon back to hamburger immediately
        mobileMenuIcon.textContent = 'menu';
    }

    // Make closeMobileMenu globally accessible
    window.closeMobileMenu = closeMobileMenuFunc;

    // Event listeners
    mobileMenuToggle.addEventListener('click', function() {
        if (mobileMenu.classList.contains('hidden')) {
            openMobileMenu();
        } else {
            closeMobileMenuFunc();
        }
    });
    closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    mobileMenuBackdrop.addEventListener('click', closeMobileMenuFunc);

    

    // Mobile Search Functionality
    if (mobileSearchInput) {
        let searchTimeout;

        mobileSearchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            if (query.length < 2) {
                mobileSearchResults.classList.add('hidden');
                return;
            }

            // Show loading state
            mobileSearchResultsContent.innerHTML = `
                <div class="px-4 py-3 text-center text-secondary-light dark:text-secondary-dark">
                    <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                    Searching...
                </div>
            `;
            mobileSearchResults.classList.remove('hidden');

            searchTimeout = setTimeout(() => {
                performMobileSearch(query);
            }, 400); // Increased debounce time slightly
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileSearchInput.contains(e.target) && !mobileSearchResults.contains(e.target)) {
                mobileSearchResults.classList.add('hidden');
            }
        });

        // Handle Enter key for search
        mobileSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query) {
                    window.location.href = `product.php?search=${encodeURIComponent(query)}`;
                }
            }
        });
    }

    // Cache for search results
    const searchCache = new Map();
    let currentSearchQuery = '';

    // Mobile search function
    async function performMobileSearch(query) {
        // Avoid duplicate searches
        if (currentSearchQuery === query && searchCache.has(query)) {
            displayMobileSearchResults(searchCache.get(query), query);
            return;
        }

        currentSearchQuery = query;

        try {
            const response = await fetch(`../api/produk/search.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.success && data.data && data.data.length > 0) {
                // Cache the results
                searchCache.set(query, data.data);
                displayMobileSearchResults(data.data, query);
            } else {
                mobileSearchResultsContent.innerHTML = `
                    <div class="px-4 py-3 text-center text-secondary-light dark:text-secondary-dark">
                        No products found for "${query}"
                    </div>
                `;
                mobileSearchResults.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Mobile search error:', error);
            mobileSearchResultsContent.innerHTML = `
                <div class="px-4 py-3 text-center text-red-500">
                    Search temporarily unavailable
                </div>
            `;
            mobileSearchResults.classList.remove('hidden');
        }
    }

    // Display mobile search results
    function displayMobileSearchResults(products, query) {
        let html = '';
        
        products.slice(0, 5).forEach(product => {
            // Use actual product image if available - fix path to use images/ not ../images/
            const imageUrl = product.gambar1 ? `images/${product.gambar1}` : 'images/placeholder.png';
            const price = parseInt(product.harga_aktif).toLocaleString('id-ID');
            const originalPrice = product.harga_coret ? parseInt(product.harga_coret).toLocaleString('id-ID') : null;
            const inStock = product.in_stok == '1';
            
            html += `
                <div onclick="openProductModal(${product.id_produk}); closeMobileMenu();" class="block hover:bg-surface-light dark:hover:bg-surface-dark transition-colors cursor-pointer">
                    <div class="flex items-center gap-3 p-3">
                        <div class="relative w-12 h-12 flex-shrink-0 overflow-hidden rounded-lg bg-surface-light dark:bg-surface-dark">
                            ${product.gambar1 ? 
                                `<img src="${imageUrl}" alt="${product.nama_produk}" class="w-full h-full object-cover transition-opacity duration-300" style="opacity: 0;" onload="this.style.opacity='1'" onerror="this.onerror=null; this.src='images/placeholder.png'; this.style.opacity='1';">` :
                                `<div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl text-secondary-light dark:text-secondary-dark">
                                        shopping_bag
                                    </span>
                                </div>`
                            }
                            ${!inStock ? '<div class="absolute inset-0 bg-black/50 rounded-lg flex items-center justify-center"><span class="text-white text-xs font-semibold">Out</span></div>' : ''}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-foreground-light dark:text-foreground-dark truncate">
                                ${product.nama_produk}
                            </p>
                            <div class="flex items-center gap-2">
                                <p class="text-sm text-primary font-semibold">
                                    IDR ${price}
                                </p>
                                ${originalPrice ? `<p class="text-xs text-secondary-light dark:text-secondary-dark line-through">IDR ${originalPrice}</p>` : ''}
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-secondary-light dark:text-secondary-dark">
                                    ${product.nama_kategori}
                                </p>
                                ${product.terjual > 0 ? `<span class="text-xs text-green-600 dark:text-green-400">${product.terjual} sold</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        if (products.length > 5) {
            html += `
                <a href="product.php?search=${encodeURIComponent(query)}" class="block px-4 py-2 text-center text-sm text-primary hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
                    View all ${products.length} results
                </a>
            `;
        }

        mobileSearchResultsContent.innerHTML = html;
        mobileSearchResults.classList.remove('hidden');
    }

    // Mobile All Categories Dropdown
    const mobileAllCategoriesBtn = document.getElementById('mobile-all-categories-btn');
    const mobileDropdownCategories = document.getElementById('mobile-dropdown-categories');
    const mobileDropdownArrow = document.getElementById('mobile-dropdown-arrow');
    
    if (mobileAllCategoriesBtn && mobileDropdownCategories && mobileDropdownArrow) {
        mobileAllCategoriesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHidden = mobileDropdownCategories.classList.contains('hidden');
            
            if (isHidden) {
                mobileDropdownCategories.classList.remove('hidden');
                mobileDropdownArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileDropdownCategories.classList.add('hidden');
                mobileDropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Close mobile menu when clicking on category links
    const categoryLinks = mobileMenu.querySelectorAll('nav a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Allow normal navigation, just close the menu after a short delay
            setTimeout(closeMobileMenuFunc, 100);
        });
    });

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
            closeMobileMenuFunc();
        }
    });
});
