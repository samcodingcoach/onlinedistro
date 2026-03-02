<?php
// Fetch distro data using the helper function (works on both Linux and Windows)
require_once __DIR__ . '/../config/api_helper.php';

$distro = null;
$api_file_path = __DIR__ . '/../api/distro/list.php';

if (file_exists($api_file_path)) {
    $data = @fetchApiData($api_file_path);
    $distro = isset($data['data'][0]) ? $data['data'][0] : null;
}

$title_nama_distro = $distro ? $distro['nama_distro'] : 'APRIL';
$title_slogan = $distro ? $distro['slogan'] : 'Modern Fashion';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>
       <?php echo htmlspecialchars($title_nama_distro); ?> - <?php echo htmlspecialchars($title_slogan); ?>
    </title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#d41152",
                        "background-light": "#f8f6f6",
                        "background-dark": "#221016",
                        "text-main": "#1b0d12",
                        "text-secondary": "#9a4c66",
                        "border-color": "#f3e7eb",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollable-container {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }
        .category-card:hover .category-image {
            transform: scale(1.05);
        }
        .category-card:hover .category-title {
            color: #d41152;
        }
    </style>
    <script src="script/tailwind-config.js"></script>
    <script src="script/search.js"></script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-main dark:text-white">
  <div class="relative flex min-h-screen w-full flex-col">

   <header class="sticky top-0 z-50 w-full border-b border-surface-light dark:border-surface-dark/50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
     <div class="flex h-16 items-center justify-between">
      <div class="flex items-center gap-8">
       <a class="flex items-center gap-2 text-text-main dark:text-white" href="index.php">
        <svg class="h-6 w-6" fill="currentColor" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
         <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z">
         </path>
        </svg>
        <h2 class="text-xl font-bold tracking-[-0.015em]">
            <?php echo htmlspecialchars($title_nama_distro); ?>
        </h2>
       </a>
       <!-- daftar navigasi menu diambil dari api/kategori/list.php, ambil favorit true/1 sebanyak 5-->
       <?php include 'navbar.php'; ?>
      </div>
      <div class="flex flex-1 items-center justify-end gap-2 md:gap-4">
       <div class="relative hidden sm:block w-full max-w-xs">
        <label class="relative w-full">
         <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary-light dark:text-secondary-dark">
          search
         </span>
         <input
            id="main-search-input"
            class="form-input h-10 w-full rounded-full border-none bg-surface-light dark:bg-surface-dark pl-10 pr-4 text-sm placeholder:text-secondary-light dark:placeholder:text-secondary-dark focus:outline-none focus:ring-2 focus:ring-primary/50"
            placeholder="Search"
            type="search"
         />
        </label>

        <!-- Search Results Dropdown -->
        <div id="main-search-results" class="absolute left-0 right-0 mt-2 bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 hidden">
            <div id="main-search-results-content" class="py-2 max-h-96 overflow-y-auto">
                <!-- Search results will be populated here -->
            </div>
        </div>
       </div>
       <div class="flex items-center gap-1 md:gap-2">
        <div class="relative">
            <button id="cart-button" class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-transparent hover:bg-surface-light dark:hover:bg-surface-dark">
             <span class="material-symbols-outlined text-2xl">
              shopping_bag
             </span>
            </button>
            <span id="cart-badge" class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-primary text-xs text-white flex items-center justify-center hidden">0</span>
        </div>
       </div>
      </div>
     </div>
    </div>
   </header>

   <main class="flex-grow">
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="w-full max-w-[1440px] px-4 md:px-10 py-6 md:py-10 flex flex-col gap-8">

                <!-- Page Header & Filters -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-border-color dark:border-gray-800 pb-6">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-text-main dark:text-white tracking-tight">Shop by Category</h1>
                            <p class="text-text-secondary dark:text-gray-400 max-w-2xl">
                                Explore our wide range of collections designed for every occasion. From summer essentials to winter layers.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10" id="categories-grid">
                    <!-- Categories will be loaded here via JavaScript -->
                </div>

                <!-- Loading State -->
                <div id="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                </div>

                <!-- Error State -->
                <div id="error" class="hidden text-center py-12">
                    <p class="text-text-secondary dark:text-gray-400">Failed to load categories. Please try again later.</p>
                </div>
                
                <!-- Pagination -->
                <div class="mt-12 flex justify-center border-t border-surface-light dark:border-surface-dark/50 pt-8 gap-4 w-full">
                    <nav class="flex items-center gap-2" id="pagination-controls">
                        <!-- Pagination buttons will be generated by JavaScript -->
                    </nav>
                </div>

    </section>
   </main>

   <?php include 'footer.php'; ?>
  </div>

  <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            
            fetchCategories(currentPage);
        });

        function fetchCategories(page = 1) {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const grid = document.getElementById('categories-grid');

            // Show loading
            loading.style.display = 'flex';
            error.classList.add('hidden');
            grid.classList.remove('hidden');

            fetch(`../api/kategori/listv2.php?page=${page}&limit=20`)
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (data.success && data.data && data.data.length > 0) {
                        renderCategories(data.data);
                        renderPagination(data.pagination);
                        currentPage = page;
                    } else {
                        showError();
                    }
                })
                .catch(err => {
                    console.error('Error fetching categories:', err);
                    loading.style.display = 'none';
                    showError();
                });

            function renderCategories(categories) {
                grid.innerHTML = categories.map(category => {
                    const backgroundUrl = category.background_url && category.background_url.trim() !== '' 
                        ? `images/${category.background_url}` 
                        : 'images/kategori.png';
                    
                    return `
                        <a class="category-card group flex flex-col gap-3 cursor-pointer" href="product.php?kategori=${encodeURIComponent(category.nama_kategori)}">
                            <div class="relative aspect-[3/4] w-full overflow-hidden rounded bg-gray-100">
                                <img alt="${category.nama_kategori}" class="category-image h-full w-full object-cover transition-transform duration-500 ease-out" data-alt="${category.nama_kategori}" src="${backgroundUrl}"/>
                                <div class="absolute inset-0 bg-black/5 dark:bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="category-title text-lg font-bold text-text-main dark:text-white transition-colors duration-200">${category.nama_kategori}</h3>
                                <span class="text-sm text-text-secondary font-medium">${category.jumlah_produk} items</span>
                            </div>
                        </a>
                    `;
                }).join('');
            }

            function showError() {
                error.classList.remove('hidden');
                grid.classList.add('hidden');
                document.getElementById('pagination-controls').innerHTML = '';
            }

            function renderPagination(pagination) {
                const paginationControls = document.getElementById('pagination-controls');
                const { current_page, total_pages } = pagination;
                
                let html = '';
                
                // Previous button
                if (current_page > 1) {
                    html += `
                        <a href="?page=${current_page - 1}" class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light dark:text-secondary-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" onclick="fetchCategories(${current_page - 1}); return false;">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    `;
                } else {
                    html += `
                        <button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                    `;
                }
                
                // Page numbers
                const maxVisible = 5;
                let startPage = Math.max(1, current_page - Math.floor(maxVisible / 2));
                let endPage = Math.min(total_pages, startPage + maxVisible - 1);
                
                // Adjust start page if we're near the end
                if (endPage - startPage < maxVisible - 1) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }
                
                // First page and ellipsis
                if (startPage > 1) {
                    html += `
                        <a href="?page=1" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium" onclick="fetchCategories(1); return false;">
                            1
                        </a>
                    `;
                    
                    if (startPage > 2) {
                        html += `<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>`;
                    }
                }
                
                // Page numbers in range
                for (let i = startPage; i <= endPage; i++) {
                    if (i === current_page) {
                        html += `
                            <button class="h-10 w-10 flex items-center justify-center rounded-full bg-primary text-white font-medium">
                                ${i}
                            </button>
                        `;
                    } else {
                        html += `
                            <a href="?page=${i}" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium" onclick="fetchCategories(${i}); return false;">
                                ${i}
                            </a>
                        `;
                    }
                }
                
                // Last page and ellipsis
                if (endPage < total_pages) {
                    if (endPage < total_pages - 1) {
                        html += `<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>`;
                    }
                    
                    html += `
                        <a href="?page=${total_pages}" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium" onclick="fetchCategories(${total_pages}); return false;">
                            ${total_pages}
                        </a>
                    `;
                }
                
                // Next button
                if (current_page < total_pages) {
                    html += `
                        <a href="?page=${current_page + 1}" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" onclick="fetchCategories(${current_page + 1}); return false;">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    `;
                } else {
                    html += `
                        <button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    `;
                }
                
                paginationControls.innerHTML = html;
            }
        }
    </script>
   
   <!-- Shopping Cart Modal -->
   <div id="cart-modal" class="fixed inset-0 z-50 hidden">
     <!-- Backdrop -->
     <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="cart-backdrop"></div>
     
     <!-- Cart Panel -->
     <div class="absolute right-0 top-0 h-full w-full max-w-md sm:max-w-lg bg-white dark:bg-surface-dark shadow-xl transform translate-x-full transition-transform duration-300" id="cart-panel">
       <!-- Cart Header -->
       <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 p-4">
         <h2 class="text-lg font-semibold text-text-main dark:text-white">Shopping Cart</h2>
         <button id="close-cart" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-surface-light dark:hover:bg-background-dark">
           <span class="material-symbols-outlined">close</span>
         </button>
       </div>
       
       <!-- Cart Items Container -->
       <div class="flex-1 overflow-y-auto p-4" id="cart-items-container">
         <!-- Sample cart item (will be dynamically populated) -->
         <div class="flex gap-4 mb-4 p-3 bg-surface-light dark:bg-background-dark rounded-lg">
           <!-- Product Image -->
           <div class="h-20 w-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
             <img src="https://via.placeholder.com/80x80" alt="Product" class="h-full w-full object-cover">
           </div>
           
           <!-- Product Details -->
           <div class="flex-1">
             <h3 class="font-medium text-text-main dark:text-white">Nama Produk</h3>
             <p class="text-sm text-text-secondary dark:text-gray-400">Kode: PRD001</p>
             <p class="text-sm font-medium text-primary">Rp 150.000</p>
             
             <!-- Quantity Controls -->
             <div class="flex items-center gap-2 mt-2">
               <button class="h-8 w-8 rounded-full bg-surface-light dark:bg-background-dark flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600">
                 <span class="material-symbols-outlined text-sm">remove</span>
               </button>
               <span class="text-sm font-medium w-8 text-center">1</span>
               <button class="h-8 w-8 rounded-full bg-surface-light dark:bg-background-dark flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600">
                 <span class="material-symbols-outlined text-sm">add</span>
               </button>
               <button class="ml-auto text-red-500 hover:text-red-700">
                 <span class="material-symbols-outlined text-sm">delete</span>
               </button>
             </div>
           </div>
         </div>
       </div>
       
       <!-- Cart Footer -->
       <div class="border-t border-gray-200 dark:border-gray-700 p-4">
         <div class="flex justify-between mb-4">
           <span class="font-medium text-text-main dark:text-white">Total:</span>
           <span class="font-semibold text-primary" id="cart-total">Rp 0</span>
         </div>
         <div class="flex gap-2">
           <button id="clear-cart-btn" class="flex items-center justify-center gap-2 px-3 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-red-50 hover:border-red-300 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:border-red-800 dark:hover:text-red-400 transition-all duration-200 shadow-sm hover:shadow-md">
             <span class="material-symbols-outlined text-lg">delete_sweep</span>
             <span class="hidden sm:inline">Clear All</span>
           </button>
           <button id="request-whatsapp-btn" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98]">
             <i class="fab fa-whatsapp text-lg" style="color: white"></i>
             <span>Request via WhatsApp</span>
           </button>
         </div>
       </div>
     </div>
   </div>
   
   <!-- Include WhatsApp Floating Button Component -->
   <?php include 'components/whatsapp-float-btn.php'; ?>

  <!-- Shopping Cart JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartButton = document.getElementById('cart-button');
        const cartModal = document.getElementById('cart-modal');
        const cartPanel = document.getElementById('cart-panel');
        const closeCart = document.getElementById('close-cart');
        const cartBackdrop = document.getElementById('cart-backdrop');
        const cartBadge = document.getElementById('cart-badge');
        
        // Cart data (will be populated from backend)
        let cartItems = [];
        
        // Cart persistence functions
        function saveCartToStorage() {
            localStorage.setItem('shoppingCart', JSON.stringify(cartItems));
        }
        
        function loadCartFromStorage() {
            const savedCart = localStorage.getItem('shoppingCart');
            if (savedCart) {
                try {
                    cartItems = JSON.parse(savedCart);
                } catch (e) {
                    cartItems = [];
                }
            }
        }
        
        function openCart() {
            cartModal.classList.remove('hidden');
            setTimeout(() => {
                cartPanel.classList.remove('translate-x-full');
            }, 10);
        }
        
        function closeCartModal() {
            cartPanel.classList.add('translate-x-full');
            setTimeout(() => {
                cartModal.classList.add('hidden');
            }, 300);
        }
        
        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const totalElement = document.getElementById('cart-total');
            
            if (cartItems.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-4xl text-gray-400">shopping_bag</span>
                        <p class="mt-2 text-gray-500">Your cart is empty</p>
                    </div>
                `;
                totalElement.textContent = 'Rp 0';
                cartBadge.classList.add('hidden');
            } else {
                let html = '';
                let total = 0;
                
                cartItems.forEach((item, index) => {
                    const subtotal = item.harga_aktif * item.qty;
                    total += subtotal;
                    
                    html += `
                        <div class="flex gap-3 sm:gap-4 mb-3 sm:mb-4 p-3 bg-surface-light dark:bg-background-dark rounded-lg">
                            <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                                <img src="${item.gambar}" alt="${item.nama_produk}" class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-text-main dark:text-white text-sm sm:text-base truncate">${item.nama_produk}</h3>
                                <p class="text-xs sm:text-sm text-text-secondary dark:text-gray-400">Kode: ${item.kode_produk}</p>
                                <p class="text-sm font-medium text-primary">Rp ${item.harga_aktif.toLocaleString('id-ID')}</p>
                                
                                <div class="flex items-center gap-2 mt-2">
                                    <button class="decrease-qty h-6 w-6 sm:h-8 sm:w-8 rounded-full bg-surface-light dark:bg-background-dark flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600" data-index="${index}">
                                        <span class="material-symbols-outlined text-xs sm:text-sm">remove</span>
                                    </button>
                                    <span class="text-xs sm:text-sm font-medium w-6 sm:w-8 text-center qty-display">${item.qty}</span>
                                    <button class="increase-qty h-6 w-6 sm:h-8 sm:w-8 rounded-full bg-surface-light dark:bg-background-dark flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600" data-index="${index}">
                                        <span class="material-symbols-outlined text-xs sm:text-sm">add</span>
                                    </button>
                                    <button class="ml-auto text-red-500 hover:text-red-700 remove-item" data-index="${index}">
                                        <span class="material-symbols-outlined text-sm sm:text-base">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                cartBadge.textContent = cartItems.reduce((sum, item) => sum + item.qty, 0);
                cartBadge.classList.remove('hidden');
                
                // Add event listeners for quantity controls
                document.querySelectorAll('.decrease-qty').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = parseInt(e.currentTarget.dataset.index);
                        if (cartItems[index].qty > 1) {
                            cartItems[index].qty--;
                            saveCartToStorage();
                            renderCartItems();
                        }
                    });
                });
                
                document.querySelectorAll('.increase-qty').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = parseInt(e.currentTarget.dataset.index);
                        cartItems[index].qty++;
                        saveCartToStorage();
                        renderCartItems();
                    });
                });
                
                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = parseInt(e.currentTarget.dataset.index);
                        cartItems.splice(index, 1);
                        saveCartToStorage();
                        renderCartItems();
                    });
                });
            }
        }
        
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-pulse';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 2000);
        }
        
        // Clear cart button handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('#clear-cart-btn')) {
                if (cartItems.length > 0 && confirm('Are you sure you want to clear all items from your cart?')) {
                    cartItems = [];
                    saveCartToStorage();
                    renderCartItems();
                    showNotification('Cart cleared!');
                }
            }
        });
        
        // Request via WhatsApp button handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('#request-whatsapp-btn')) {
                if (cartItems.length === 0) {
                    showNotification('Your cart is empty!');
                    return;
                }
                
                const phoneNumber = '<?php echo $distro && isset($distro['no_telepon']) ? ($distro['no_telepon'][0] === '0' ? '62' . substr($distro['no_telepon'], 1) : $distro['no_telepon']) : '628123456789'; ?>';
                let message = 'Halo, saya ingin memesan produk berikut:\n\n';
                let total = 0;
                
                cartItems.forEach((item, index) => {
                    const subtotal = item.harga_aktif * item.qty;
                    total += subtotal;
                    message += `${index + 1}. ${item.nama_produk}\n`;
                    message += `   Kode: ${item.kode_produk}\n`;
                    message += `   Harga: Rp ${item.harga_aktif.toLocaleString('id-ID')} x ${item.qty}\n`;
                    message += `   Subtotal: Rp ${subtotal.toLocaleString('id-ID')}\n\n`;
                });
                
                message += `Total: Rp ${total.toLocaleString('id-ID')}\n\n`;
                message += 'Mohon informasikan ketersediaan dan cara pemesanannya. Terima kasih!';
                
                const encodedMessage = encodeURIComponent(message);
                const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
                
                window.open(whatsappUrl, '_blank');
            }
        });
        
        // Event listeners
        cartButton.addEventListener('click', openCart);
        closeCart.addEventListener('click', closeCartModal);
        cartBackdrop.addEventListener('click', closeCartModal);
        
        // Close cart on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !cartModal.classList.contains('hidden')) {
                closeCartModal();
            }
        });
        
        // Load cart from storage on page load
        loadCartFromStorage();
        
        // Initial render
        renderCartItems();
    });
  </script>

</body>
</html>
