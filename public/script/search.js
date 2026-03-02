// Main search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('main-search-input');
    const searchResults = document.getElementById('main-search-results');
    const searchResultsContent = document.getElementById('main-search-results-content');
    let searchTimeout;

    // Only initialize if search elements exist
    if (!searchInput || !searchResults || !searchResultsContent) {
        return;
    }

    // Show search results when typing
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Debounce search requests
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Hide search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.add('hidden');
        }
    });

    function performSearch(query) {
        if (query.length < 1) {
            searchResults.classList.add('hidden');
            return;
        }

        // Show loading state
        searchResultsContent.innerHTML = '<div class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">Searching...</div>';
        searchResults.classList.remove('hidden');

        // Make AJAX request to search API
        fetch('/distro/api/produk/search.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    displaySearchResults(data.data);
                } else {
                    searchResultsContent.innerHTML = '<div class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">No results found</div>';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResultsContent.innerHTML = '<div class="px-4 py-3 text-red-500 text-sm">Error performing search</div>';
            });
    }

    function displaySearchResults(results) {
        if (results.length === 0) {
            searchResultsContent.innerHTML = '<div class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">No results found</div>';
            return;
        }

        let html = '';
        results.forEach(product => {
            html += '<div class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary/10 dark:hover:bg-primary/10 transition-colors main-search-result-item border-b border-gray-100 dark:border-gray-700 last:border-b-0 cursor-pointer" data-product-id="' + product.id_produk + '">' +
                    '<div class="font-bold uppercase text-gray-900 dark:text-white text-sm">' + product.nama_produk + '</div>' +
                    '<div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-wrap gap-2">' +
                        '<span class="font-medium px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">' + product.nama_kategori + '</span>' +
                        '<span>' + product.merk + '</span>' +
                        '<span class="font-medium">Kode: ' + product.kode_produk + '</span>' +
                        '<span class="font-bold text-primary">Rp. ' + parseInt(product.harga_aktif).toLocaleString() + '</span>' +
                    '</div>' +
                    '</div>';
        });

        searchResultsContent.innerHTML = html;

        // Add event listeners to search result items
        document.querySelectorAll('.main-search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                searchResults.classList.add('hidden');
                searchInput.value = ''; // Clear the search after selection
                if (typeof openProductModal === 'function') {
                    openProductModal(productId); // Open modal instead of navigating
                }
            });
        });
    }
});
