<?php
// Fetch distro data using the helper function (works on both Linux and Windows)
require_once __DIR__ . '/../config/api_helper.php';

$distro = null;
$api_file_path = __DIR__ . '/../api/distro/list.php';

if (file_exists($api_file_path)) {
    $data = @fetchApiData($api_file_path);
    $distro = isset($data['data'][0]) ? $data['data'][0] : null;
}

// Get category parameter from URL
$category_filter = isset($_GET['kategori']) ? $_GET['kategori'] : null;

$title_nama_distro = $distro ? $distro['nama_distro'] : 'APRIL';
$title_slogan = $distro ? $distro['slogan'] : 'Modern Fashion';

// Set page title based on category filter
$page_title = $category_filter ? htmlspecialchars(ucfirst($category_filter)) : 'All Products';
$page_description = $category_filter
    ? "Discover our " . htmlspecialchars(ucfirst($category_filter)) . " collection designed for the modern lifestyle."
    : "Discover our latest collection designed for the modern lifestyle.";
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
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
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
    
    /* Custom transparent scrollbar for cart */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
    /* Dark mode scrollbar */
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.3);
    }
    
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.5);
    }
    
    /* Firefox scrollbar */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }
    
    .dark .custom-scrollbar {
        scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
    }
    
    /* Mobile transparent scrollbar */
    .mobile-transparent-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .mobile-transparent-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .mobile-transparent-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 2px;
    }
    
    .mobile-transparent-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }
    
    /* Dark mode mobile scrollbar */
    .dark .mobile-transparent-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .dark .mobile-transparent-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Firefox mobile scrollbar */
    .mobile-transparent-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }
    
    .dark .mobile-transparent-scrollbar {
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }
    
    /* Mobile menu icon transition */
    .mobile-menu-icon-transition {
        transition: all 0.3s ease;
    }
  </style>
  <script src="script/tailwind-config.js"></script>
<script src="script/product-modal.js"></script>
<script src="script/search.js"></script>
<script src="script/product-filter.js"></script>
<script src="script/mobile-menu.js"></script>
 </head>
 <body class="font-display bg-background-light dark:bg-background-dark text-foreground-light dark:text-foreground-dark">
  <div class="relative flex min-h-screen w-full flex-col">

   <header class="sticky top-0 z-50 w-full border-b border-surface-light dark:border-surface-dark/50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
     <div class="flex h-16 items-center justify-between">
      <div class="flex items-center gap-8">
       <a class="flex items-center gap-2 text-foreground-light dark:text-foreground-dark" href="index.php">
        <img src="icon.png" alt="<?php echo htmlspecialchars($title_nama_distro); ?>" class="h-6 w-6">
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
        
        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle" class="md:hidden flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-transparent hover:bg-surface-light dark:hover:bg-surface-dark">
            <span class="material-symbols-outlined text-2xl mobile-menu-icon-transition" id="mobile-menu-icon">
                menu
            </span>
        </button>
       </div>
      </div>
     </div>
    </div>
   </header>

   <!-- Mobile Menu Overlay -->
   <div id="mobile-menu" class="fixed inset-0 z-40 hidden">
       <!-- Backdrop -->
       <div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
       
       <!-- Menu Panel -->
       <div class="absolute right-0 top-0 h-full w-full max-w-sm bg-white dark:bg-surface-dark shadow-xl transform translate-x-full transition-transform duration-300" id="mobile-menu-panel">
           <!-- Menu Header -->
           <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 p-4">
               <h2 class="text-lg font-semibold text-foreground-light dark:text-foreground-dark">Menu</h2>
               <button id="close-mobile-menu" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-surface-light dark:hover:bg-background-dark">
                   <span class="material-symbols-outlined">close</span>
               </button>
           </div>
           
           <!-- Mobile Search -->
           <div class="p-4 border-b border-gray-200 dark:border-gray-700">
               <div class="relative">
                   <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary-light dark:text-secondary-dark">
                       search
                   </span>
                   <input
                       id="mobile-search-input"
                       class="form-input h-10 w-full rounded-full border-none bg-surface-light dark:bg-surface-dark pl-10 pr-4 text-sm placeholder:text-secondary-light dark:placeholder:text-secondary-dark focus:outline-none focus:ring-2 focus:ring-primary/50"
                       placeholder="Search products..."
                       type="search"
                   />
               </div>
               
               <!-- Mobile Search Results -->
               <div id="mobile-search-results" class="absolute left-4 right-4 mt-2 bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 hidden">
                   <div id="mobile-search-results-content" class="py-2 max-h-96 overflow-y-auto">
                       <!-- Search results will be populated here -->
                   </div>
               </div>
           </div>
           
           <!-- Mobile Categories -->
           <div class="flex-1 overflow-y-auto p-4 mobile-transparent-scrollbar">
               <h3 class="text-sm font-semibold text-secondary-light dark:text-secondary-dark mb-3">Categories</h3>
               <nav class="space-y-2">
                   <?php
                   // Fetch categories for mobile menu using the helper function
                   $favorite_categories = [];
                   $non_favorite_categories = [];

                   $api_file_path = __DIR__ . '/../api/kategori/list.php';

                   if (file_exists($api_file_path)) {
                       $response_data = @fetchApiData($api_file_path);

                       if ($response_data && isset($response_data['success']) && $response_data['success'] === true) {
                           $all_categories = $response_data['data'];

                           foreach ($all_categories as $kategori) {
                               if ($kategori['favorit'] == '1' && $kategori['aktif'] == '1') {
                                   $favorite_categories[] = $kategori;
                               } elseif ($kategori['favorit'] == '0' && $kategori['aktif'] == '1') {
                                   $non_favorite_categories[] = $kategori;
                               }
                           }

                           usort($favorite_categories, function($a, $b) {
                               return strcasecmp($a['nama_kategori'], $b['nama_kategori']);
                           });

                           usort($non_favorite_categories, function($a, $b) {
                               return strcasecmp($a['nama_kategori'], $b['nama_kategori']);
                           });
                       }
                   }

                   // Display top 4 favorite categories like desktop navbar
                   $top_mobile_categories = array_slice($favorite_categories ?? [], 0, 4);
                   if (!empty($top_mobile_categories)): ?>
                       <?php foreach ($top_mobile_categories as $kategori): ?>
                           <a href="product.php?kategori=<?php echo urlencode($kategori['nama_kategori']); ?>" class="block px-4 py-3 rounded-lg text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
                               <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                           </a>
                       <?php endforeach; ?>
                   <?php endif; ?>

                   <!-- All Categories Dropdown for Mobile -->
                   <div class="mobile-category-dropdown">
                       <button id="mobile-all-categories-btn" class="w-full px-4 py-3 rounded-lg text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors flex items-center justify-between">
                           <span>All Categories</span>
                           <svg class="w-4 h-4 transition-transform" id="mobile-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                           </svg>
                       </button>
                       
                       <div id="mobile-dropdown-categories" class="hidden pl-4 pr-4 pb-2 space-y-2">
                           <?php 
                           // Get remaining categories (non-favorites and any favorites beyond top 4)
                           $remaining_mobile_categories = array_merge(
                               array_slice($favorite_categories ?? [], 4), 
                               $non_favorite_categories ?? []
                           );
                           if (!empty($remaining_mobile_categories)): ?>
                               <?php foreach ($remaining_mobile_categories as $kategori): ?>
                                   <a href="product.php?kategori=<?php echo urlencode($kategori['nama_kategori']); ?>" class="block px-4 py-2 rounded text-sm text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
                                       <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                   </a>
                               <?php endforeach; ?>
                           <?php endif; ?>
                       </div>
                   </div>
               </nav>
           </div>
       </div>
   </div>

   <main class="flex-grow">
    <section id="product-page-content" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumb -->
        <nav class="flex text-sm text-secondary-light dark:text-secondary-dark mb-6">
          <a href="index.php" class="hover:text-primary transition-colors">Home</a>
          <span class="mx-2">/</span>
          <?php if ($category_filter): ?>
            <a href="product.php" class="hover:text-primary transition-colors">All Products</a>
            <span class="mx-2">/</span>
            <span class="font-medium text-foreground-light dark:text-foreground-dark">
              <?php echo htmlspecialchars(ucfirst($category_filter)); ?>
            </span>
          <?php else: ?>
            <span class="font-medium text-foreground-light dark:text-foreground-dark">
              All Products
            </span>
          <?php endif; ?>
        </nav>

        <!-- Header + Filter -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b border-surface-light dark:border-surface-dark/50 pb-6">
          <div>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-2">
              <?php echo $page_title; ?>
            </h1>
            <p class="text-secondary-light dark:text-secondary-dark">
              <?php echo $page_description; ?>
            </p>
          </div>

          <!-- FILTER -->
          <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium mr-1 hidden sm:inline-block">
              Filter by:
            </span>

            <!-- Price -->
            <div class="relative">
              <button
                type="button"
                class="filter-btn flex items-center gap-2 px-4 py-2 bg-surface-light dark:bg-surface-dark rounded-full text-sm font-medium"
                data-target="price-filter"
              >
                Price
                <span class="material-symbols-outlined text-base">expand_more</span>
              </button>

              <div
                id="price-filter"
                class="filter-menu absolute z-20 mt-2 w-48 rounded-lg bg-white dark:bg-surface-dark shadow-lg hidden"
              >
                <ul class="py-2 text-sm">
                  <li class="px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark" data-price="under100000">Under 100.000</li>
                  <li class="px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark" data-price="100000-500000">100.000-500.000</li>
                  <li class="px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark" data-price="500000-1000000">500.000-1.000.000</li>
                  <li class="px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark" data-price="lowest">Lowest</li>
                  <li class="px-4 py-2 cursor-pointer hover:bg-surface-light dark:hover:bg-background-dark" data-price="highest">Highest</li>
                </ul>
              </div>
            </div>

            <!-- Size -->
            <div class="relative">
              <button
                type="button"
                class="filter-btn flex items-center gap-2 px-4 py-2 bg-surface-light dark:bg-surface-dark rounded-full text-sm font-medium"
                data-target="size-filter"
              >
                Size
                <span class="material-symbols-outlined text-base">expand_more</span>
              </button>

              <div
                id="size-filter"
                class="filter-menu absolute z-20 mt-2 w-32 rounded-lg bg-white dark:bg-surface-dark shadow-lg hidden"
              >
                <ul class="py-2 text-sm" id="size-list">
                  <!-- Sizes will be loaded dynamically from API -->
                </ul>
              </div>
            </div>

            <!-- Brand -->
            <div class="relative">
              <button
                type="button"
                class="filter-btn flex items-center gap-2 px-4 py-2 bg-surface-light dark:bg-surface-dark rounded-full text-sm font-medium"
                data-target="brand-filter"
              >
                Brand
                <span class="material-symbols-outlined text-base">expand_more</span>
              </button>

              <div
                id="brand-filter"
                class="filter-menu absolute z-20 mt-2 w-40 rounded-lg bg-white dark:bg-surface-dark shadow-lg hidden"
              >
                <ul class="py-2 text-sm" id="brand-list">
                  <!-- Brands will be loaded dynamically -->
                </ul>
              </div>
            </div>

            <div class="h-6 w-px bg-surface-dark/10 dark:bg-surface-light/10 mx-2"></div>
          </div>
        </div>

        <!-- Active Filters Display -->
        <div id="activeFilters" class="hidden mb-6 p-4 bg-surface-light dark:bg-surface-dark rounded-lg">
          <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-secondary-light dark:text-secondary-dark">Active filters:</span>
            <div id="filterTags" class="flex flex-wrap items-center gap-2">
              <!-- Filter tags will be added here dynamically -->
            </div>
            <button id="clearAllFilters" class="text-sm text-primary hover:underline font-medium">
              Clear all
            </button>
          </div>
        </div>

        <?php include 'all-itemproduct.php'; ?>
    </section>
   </main>

   <?php include 'footer.php'; ?>
   
   <!-- Include Product Modal -->
   <?php include 'modal/product-modal.php'; ?>
   
   <!-- Shopping Cart Modal -->
   <div id="cart-modal" class="fixed inset-0 z-50 hidden">
     <!-- Backdrop -->
     <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="cart-backdrop"></div>
     
     <!-- Cart Panel -->
     <div class="absolute right-0 top-0 h-full w-full max-w-md sm:max-w-lg bg-white dark:bg-surface-dark shadow-xl transform translate-x-full transition-transform duration-300" id="cart-panel">
       <!-- Cart Header -->
       <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 p-4">
         <h2 class="text-lg font-semibold text-foreground-light dark:text-foreground-dark">Shopping Cart</h2>
         <button id="close-cart" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-surface-light dark:hover:bg-background-dark">
           <span class="material-symbols-outlined">close</span>
         </button>
       </div>
       
       <!-- Cart Items Container -->
       <div class="flex-1 overflow-y-auto p-4 max-h-[calc(100vh-280px)] custom-scrollbar" id="cart-items-container">
         <!-- Sample cart item (will be dynamically populated) -->
         <div class="flex gap-4 mb-4 p-3 bg-surface-light dark:bg-background-dark rounded-lg">
           <!-- Product Image -->
           <div class="h-20 w-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
             <img src="https://via.placeholder.com/80x80" alt="Product" class="h-full w-full object-cover">
           </div>
           
           <!-- Product Details -->
           <div class="flex-1">
             <h3 class="font-medium text-foreground-light dark:text-foreground-dark">Nama Produk</h3>
             <p class="text-sm text-secondary-light dark:text-secondary-dark">Kode: PRD001</p>
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
       <div class="border-t border-gray-200 dark:border-gray-700 p-4 space-y-4">
         <!-- Summary Section -->
         <div class="space-y-2">
           <div class="flex justify-between text-sm">
             <span class="text-secondary-light dark:text-secondary-dark">Items:</span>
             <span class="font-medium text-foreground-light dark:text-foreground-dark" id="cart-item-count">0</span>
           </div>
           <div class="flex justify-between">
             <span class="text-base font-semibold text-foreground-light dark:text-foreground-dark">Total:</span>
             <span class="text-lg font-bold text-primary" id="cart-total">Rp 0</span>
           </div>
         </div>
         
         <!-- Action Buttons -->
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
  </div>

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
            const itemCountElement = document.getElementById('cart-item-count');
            
            if (cartItems.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-4xl text-gray-400">shopping_bag</span>
                        <p class="mt-2 text-gray-500">Your cart is empty</p>
                    </div>
                `;
                totalElement.textContent = 'Rp 0';
                itemCountElement.textContent = '0';
                cartBadge.classList.add('hidden');
            } else {
                let html = '';
                let total = 0;
                let totalItems = 0;
                
                cartItems.forEach((item, index) => {
                    const subtotal = item.harga_aktif * item.qty;
                    total += subtotal;
                    totalItems += item.qty;
                    
                    html += `
                        <div class="flex gap-3 sm:gap-4 mb-3 sm:mb-4 p-3 bg-surface-light dark:bg-background-dark rounded-lg">
                            <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                                <img src="${item.gambar}" alt="${item.nama_produk}" class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-foreground-light dark:text-foreground-dark text-sm sm:text-base truncate">${item.nama_produk}</h3>
                                <p class="text-xs sm:text-sm text-secondary-light dark:text-secondary-dark">Kode: ${item.kode_produk}</p>
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
                itemCountElement.textContent = `${totalItems} ${totalItems > 1 ? 'items' : 'item'}`;
                cartBadge.textContent = totalItems;
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
        
        // Add to cart function
        function addToCart(product) {
            const existingItemIndex = cartItems.findIndex(item => item.kode_produk === product.kode_produk);
            
            if (existingItemIndex > -1) {
                cartItems[existingItemIndex].qty += 1;
            } else {
                cartItems.push({
                    ...product,
                    qty: 1
                });
            }
            
            saveCartToStorage();
            renderCartItems();
            
            // Show success notification
            showNotification('Product added to cart!');
        }
        
        // Show notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-pulse';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 2000);
        }
        
        // Add to cart button click handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('#add-to-cart-btn')) {
                const modal = document.getElementById('productModal');
                if (modal && !modal.classList.contains('hidden')) {
                    const productName = document.getElementById('modalProductName').textContent;
                    const productCode = document.getElementById('modalProductCode').textContent;
                    const productPrice = document.getElementById('modalPrice').textContent.replace(/[^\d]/g, '');
                    const productImage = document.getElementById('modalMainImage').src;
                    
                    const product = {
                        nama_produk: productName,
                        kode_produk: productCode,
                        harga_aktif: parseInt(productPrice),
                        gambar: productImage
                    };
                    
                    addToCart(product);
                }
            }
        });
        
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

  <!-- Initialize product filter with PHP variables -->
  <script>
    // Set initial category filter from PHP
    if (typeof currentFilters !== 'undefined') {
        currentFilters.category = '<?php echo $category_filter ? htmlspecialchars($category_filter) : ''; ?>';
    }

      <!-- Product filter functionality moved to script/product-filter.js -->
  </script>
 </body>
</html>