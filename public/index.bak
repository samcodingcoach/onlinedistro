<!DOCTYPE html>
<html class="light" lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <?php
  // Fetch distro data by executing the API script in a separate process to avoid header conflicts
  $distro = null;

  // Use a method that ensures the API is properly executed without interfering with the current page
  $api_file_path = __DIR__ . '/../api/distro/list.php';

  if (file_exists($api_file_path)) {
      // Execute the API script and capture its output
      $command = 'php ' . escapeshellarg($api_file_path);
      $api_output = shell_exec($command);

      if ($api_output !== null) {
          $data = json_decode($api_output, true);
          $distro = isset($data['data'][0]) ? $data['data'][0] : null;
      }
  }

  $title_nama_distro = $distro ? $distro['nama_distro'] : 'APRIL';
  $title_slogan = $distro ? $distro['slogan'] : 'Modern Fashion';
  ?>
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
    
    /* Cookie Consent Styles */
    .cookie-consent-overlay {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
    }
    
    .cookie-consent-overlay.show {
        transform: translateY(0);
    }
    
    .cookie-consent-content {
        padding: 24px;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-top: 4px solid #137fec;
    }
    
    .dark .cookie-consent-content {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-top-color: #2563eb;
    }
    
    .cookie-button {
        transition: all 0.2s ease;
    }
    
    .cookie-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
    }
    
    .cookie-button:active {
        transform: translateY(0);
    }
  </style>
  <script src="script/tailwind-config.js"></script>
<script src="script/product-modal.js"></script>
<script src="script/search.js"></script>
<script src="script/mobile-menu.js"></script>
 </head>
 <body class="font-display bg-background-light dark:bg-background-dark text-foreground-light dark:text-foreground-dark">
  <div class="relative flex min-h-screen w-full flex-col">

   <header class="sticky top-0 z-50 w-full border-b border-surface-light dark:border-surface-dark/50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
     <div class="flex h-16 items-center justify-between">
      <div class="flex items-center gap-8">
       <a class="flex items-center gap-2 text-foreground-light dark:text-foreground-dark" href="#">
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
    <!-- konten utama di sini -->
    <!-- hero banner section -->
        <?php
        // Fetch banner data by executing the API script in a separate process to avoid header conflicts
        $banner = null;

        // Use a method that ensures the API is properly executed without interfering with the current page
        $api_file_path = __DIR__ . '/../api/banner/list.php';

        if (file_exists($api_file_path)) {
            // Change to the API directory before executing to fix relative path issues
            $command = 'cd ' . escapeshellarg(dirname($api_file_path)) . ' && php ' . escapeshellarg(basename($api_file_path));
            $api_output = shell_exec($command);

            if ($api_output !== null) {
                $data = json_decode($api_output, true);
                
                // Filter only active banners (aktif = '1')
                $active_banners = [];
                if (isset($data['data']) && is_array($data['data'])) {
                    $active_banners = array_filter($data['data'], function($b) {
                        return isset($b['aktif']) && $b['aktif'] == '1';
                    });
                }
                
                // Get the first active banner if any exist
                $banner = !empty($active_banners) ? reset($active_banners) : null;
            }
        }

        $banner_judul = $banner['judul'] ?? null;
        $banner_deskripsi = $banner['deskripsi'] ?? null;
        $banner_url_gambar = $banner['url_gambar'] ?? null;
        ?>
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex min-h-[60vh] flex-col items-center justify-center gap-6 rounded-xl bg-cover bg-center bg-no-repeat p-4 text-center" data-alt="A model wearing a stylish trench coat from the new autumn collection, posing in a minimalist urban setting." style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("<?php echo htmlspecialchars($banner_url_gambar); ?>");'>
                <div class="flex flex-col gap-2">
                    <h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] md:text-6xl">
                        <?php echo htmlspecialchars($banner_judul); ?>
                    </h1>
                    <h2 class="text-white text-base font-normal leading-normal md:text-lg">
                        <?php echo htmlspecialchars($banner_deskripsi); ?>
                    </h2>
                </div>
                <a href="product.php" class="flex h-12 min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full bg-primary px-6 text-base font-bold leading-normal tracking-[0.015em] text-white transition-opacity hover:opacity-90">
                    <span class="truncate">
                        Shop Now
                    </span>
                </a>
            </div>
        </section>
    
    <!-- hero banner section -->

   <!--  section new arrival -->
        <?php
        // Fetch produk data by executing the API script in a separate process
        $produk_list = [];
        $api_file_path = __DIR__ . '/../api/produk/list.php';

        if (file_exists($api_file_path)) {
            $command = 'cd ' . escapeshellarg(dirname($api_file_path)) . ' && php ' . escapeshellarg(basename($api_file_path));
            $api_output = shell_exec($command);

            if ($api_output !== null) {
                $data = json_decode($api_output, true);
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    // Limit to 6 products
                    $produk_list = array_slice($data['data'], 0, 6);
                }
            }
        }
        ?>
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h2 class="text-foreground-light dark:text-foreground-dark text-2xl md:text-3xl font-bold leading-tight tracking-[-0.015em] mb-6">
            New Arrivals
            </h2>
            <div class="relative">
                <button id="newArrivalsPrev" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-black/30 backdrop-blur-sm rounded-full h-12 w-12 flex items-center justify-center shadow-lg hover:bg-black/50 transition-all duration-200 opacity-0 hover:opacity-100">
                    <span class="material-symbols-outlined text-2xl text-white">
                        chevron_left
                    </span>
                </button>
                <button id="newArrivalsNext" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-black/30 backdrop-blur-sm rounded-full h-12 w-12 flex items-center justify-center shadow-lg hover:bg-black/50 transition-all duration-200 opacity-0 hover:opacity-100">
                    <span class="material-symbols-outlined text-2xl text-white">
                        chevron_right
                    </span>
                </button>
                <div id="newArrivalsContainer" class="flex overflow-x-auto space-x-4 md:space-x-6 pb-4 no-scrollbar scroll-smooth scrollable-container cursor-grab active:cursor-grabbing">
                <?php if (!empty($produk_list)): ?>
                    <?php foreach ($produk_list as $produk): ?>
                        <div class="group flex flex-col gap-3 flex-shrink-0 w-[45vw] sm:w-[30vw] md:w-[22vw] lg:w-[20vw] cursor-pointer" onclick="addToCartFromNewArrivals(<?php echo htmlspecialchars(json_encode($produk)); ?>)">
                            <div class="relative overflow-hidden rounded-xl">
                                <div class="absolute bottom-2 left-2 z-10 rounded-lg bg-black/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                                    <?php echo htmlspecialchars($produk['terjual']); ?> sold
                                </div>
                                <div class="absolute bottom-2 right-2 z-10 rounded-lg <?php echo $produk['in_stok'] == '1' ? 'bg-green-600/50' : 'bg-red-600/50'; ?> backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                                    <?php echo $produk['in_stok'] == '1' ? 'In Stock' : 'Out of Stock'; ?>
                                </div>
                                <div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover absolute inset-0 transition-opacity duration-500 ease-in-out group-hover:opacity-0" data-alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" style='background-image: url("images/<?php echo htmlspecialchars($produk['gambar1']); ?>");'>
                                </div>
                                <div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover opacity-0 transition-opacity duration-500 ease-in-out group-hover:opacity-100" data-alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" style='background-image: url("<?php echo !empty($produk['gambar3']) ? 'images/' . htmlspecialchars($produk['gambar3']) : 'images/' . htmlspecialchars($produk['gambar1']); ?>");'>
                                </div>
                            </div>
                            <div>
                                <p class="text-base font-medium leading-normal">
                                    <?php echo htmlspecialchars($produk['nama_produk']); ?>
                                </p>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-normal leading-normal text-primary">
                                        IDR <?php echo number_format((int)$produk['harga_aktif'], 0, ',', '.'); ?>
                                    </p>
                                    <?php if (!empty($produk['harga_coret']) && $produk['harga_coret'] > 0): ?>
                                        <p class="text-sm font-normal leading-normal text-secondary-light dark:text-secondary-dark line-through">
                                            IDR <?php echo number_format((int)$produk['harga_coret'], 0, ',', '.'); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex items-center justify-center w-full py-8">
                        <p class="text-secondary-light dark:text-secondary-dark">No products available</p>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </section>

   <!--  section new arrival -->

  <!--  section shop by category -->
        <?php
        // Fetch kategori data by executing the API script in a separate process
        $kategori_list = [];
        $api_file_path = __DIR__ . '/../api/kategori/list.php';

        if (file_exists($api_file_path)) {
            $command = 'cd ' . escapeshellarg(dirname($api_file_path)) . ' && php ' . escapeshellarg(basename($api_file_path));
            $api_output = shell_exec($command);

            if ($api_output !== null) {
                $data = json_decode($api_output, true);
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    // Filter categories: aktif=1, favorit=1, then sort by id_kategori asc and limit to 3
                    $filtered_categories = array_filter($data['data'], function($kategori) {
                        return $kategori['aktif'] == '1' && $kategori['favorit'] == '1';
                    });
                    
                    // Sort by id_kategori ascending
                    usort($filtered_categories, function($a, $b) {
                        return intval($a['id_kategori']) - intval($b['id_kategori']);
                    });
                    
                    // Limit to 3 categories
                    $kategori_list = array_slice($filtered_categories, 0, 3);
                }
            }
        }
        ?>
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h2 class="text-foreground-light dark:text-foreground-dark text-2xl md:text-3xl font-bold leading-tight tracking-[-0.015em] mb-6">
                Shop by Category
            </h2>

            <div class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-4">
                <?php if (!empty($kategori_list)): ?>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <a class="group relative flex h-80 items-center justify-center overflow-hidden rounded-xl" href="product.php?kategori=<?php echo urlencode($kategori['nama_kategori']); ?>">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-300 group-hover:scale-105" 
                                data-alt="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" 
                                style='background-image: url("images/<?php echo !empty($kategori['background_url']) ? htmlspecialchars($kategori['background_url']) : 'kategori.png'; ?>");'>
                            </div>
                            <div class="absolute inset-0 bg-black/30"></div>
                            <h3 class="relative text-2xl font-bold text-white">
                                <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

                <a class="group relative flex h-80 items-center justify-center overflow-hidden rounded-xl bg-surface-light dark:bg-surface-dark" href="all-categories.php">
                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <h3 class="relative text-2xl font-bold text-foreground-light dark:text-foreground-dark group-hover:text-white transition-colors duration-300">
                        All Category
                    </h3>
                </a>
            </div>
        </section>
       
   <!-- section shop by category -->

    <!-- best seller -->
        <?php
        // Fetch best seller products from the API
        $best_seller_list = [];
        $api_file_path = __DIR__ . '/../api/produk/list.php';

        if (file_exists($api_file_path)) {
            $command = 'cd ' . escapeshellarg(dirname($api_file_path)) . ' && php ' . escapeshellarg(basename($api_file_path));
            $api_output = shell_exec($command);

            if ($api_output !== null) {
                $data = json_decode($api_output, true);
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    // Filter for active products with terjual > 0, sort by terjual (sold count) descending, then limit to 4
                    $active_products = array_filter($data['data'], function($produk) {
                        return $produk['aktif'] == '1' && intval($produk['terjual']) > 0;
                    });
                    // Sort by terjual (sold count) descending
                    usort($active_products, function($a, $b) {
                        return intval($b['terjual']) - intval($a['terjual']);
                    });
                    $best_seller_list = array_slice($active_products, 0, 4);
                }
            }
        }
        ?>
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h2 class="text-foreground-light dark:text-foreground-dark text-2xl md:text-3xl font-bold leading-tight tracking-[-0.015em] mb-6">Best Sellers</h2>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
                <?php if (!empty($best_seller_list)): ?>
                    <?php foreach ($best_seller_list as $produk): ?>
                        <div class="group relative overflow-hidden rounded-xl aspect-[3/4]">
                            <div class="w-full h-full bg-center bg-no-repeat bg-cover absolute inset-0 transition-opacity duration-500 ease-in-out group-hover:opacity-0" data-alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" style='background-image: url("images/<?php echo htmlspecialchars($produk['gambar3']); ?>");'></div>
                            <div class="w-full h-full bg-center bg-no-repeat bg-cover opacity-0 transition-opacity duration-500 ease-in-out group-hover:opacity-100" data-alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" style='background-image: url("images/<?php echo htmlspecialchars($produk['gambar1']); ?>");'></div>
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm flex flex-col items-center justify-center p-4 text-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <h3 class="text-white text-lg font-bold mb-1"><?php echo htmlspecialchars($produk['nama_produk']); ?></h3>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-primary font-bold">IDR <?php echo number_format((int)$produk['harga_aktif'], 0, ',', '.'); ?></span>
                                    <span class="text-white/60 line-through text-sm">IDR <?php echo number_format((int)$produk['harga_coret'], 0, ',', '.'); ?></span>
                                </div>
                                <div class="flex flex-wrap justify-center gap-2 mb-6">
                                    <span class="bg-white/20 text-white text-xs font-semibold px-2 py-1 rounded"><?php echo htmlspecialchars($produk['terjual']); ?> sold</span>
                                    <span class="bg-<?php echo $produk['jumlah_stok'] > 0 ? 'green' : 'red'; ?>-600/80 text-white text-xs font-semibold px-2 py-1 rounded"><?php echo $produk['jumlah_stok'] > 0 ? 'In Stock' : 'Out of Stock'; ?></span>
                                </div>
                                <div class="flex flex-col gap-2 w-full max-w-[180px]">
                                    <?php if (!empty($produk['shopee_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($produk['shopee_link']); ?>" class="w-full bg-[#ee4d2d] hover:bg-[#d03e1e] text-white text-sm font-bold py-2 rounded transition-colors text-center" target="_blank">Shopee</a>
                                    <?php endif; ?>
                                    <?php if (!empty($produk['tiktok_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($produk['tiktok_link']); ?>" class="w-full bg-black hover:bg-gray-900 border border-white/20 text-white text-sm font-bold py-2 rounded transition-colors text-center" target="_blank">TikTok</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    <!-- best seller -->

    <!-- Find us -->
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h2 class="text-foreground-light dark:text-foreground-dark text-2xl md:text-3xl font-bold leading-tight tracking-[-0.015em] mb-6">Find Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-center">
                <div class="relative rounded-xl aspect-video overflow-hidden cursor-pointer group hover:shadow-lg transition-shadow">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80')"></div>

                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                        <div class="text-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform scale-90 group-hover:scale-100">
                            <svg class="w-20 h-20 mx-auto mb-3" viewBox="0 0 109.59 122.88" fill="white">
                                <path d="M74.98,91.98C76.15,82.36,69.96,76.22,53.6,71c-7.92-2.7-11.66-6.24-11.57-11.12 c0.33-5.4,5.36-9.34,12.04-9.47c4.63,0.09,9.77,1.22,14.76,4.56c0.59,0.37,1.01,0.32,1.35-0.2c0.46-0.74,1.61-2.53,2-3.17 c0.26-0.42,0.31-0.96-0.35-1.44c-0.95-0.7-3.6-2.13-5.03-2.72c-3.88-1.62-8.23-2.64-12.86-2.63c-9.77,0.04-17.47,6.22-18.12,14.47 c-0.42,5.95,2.53,10.79,8.86,14.47c1.34,0.78,8.6,3.67,11.49,4.57c9.08,2.83,13.8,7.9,12.69,13.81c-1.01,5.36-6.65,8.83-14.43,8.93 c-6.17-0.24-11.71-2.75-16.02-6.1c-0.11-0.08-0.65-0.5-0.72-0.56c-0.53-0.42-1.11-0.39-1.47,0.15c-0.26,0.4-1.92,2.8-2.34,3.43 c-0.39,0.55-0.18,0.86,0.23,1.2c1.8,1.5,4.18,3.14,5.81,3.97c4.47,2.28,9.32,3.53,14.48,3.72c3.32,0.22,7.5-0.49,10.63-1.81 C70.63,102.67,74.25,97.92,74.98,91.98L74.98,91.98z M54.79,7.18c-10.59,0-19.22,9.98-19.62,22.47h39.25 C74.01,17.16,65.38,7.18,54.79,7.18L54.79,7.18z M94.99,122.88l-0.41,0l-80.82-0.01h0c-5.5-0.21-9.54-4.66-10.09-10.19l-0.05-1 l-3.61-79.5v0C0,32.12,0,32.06,0,32c0-1.28,1.03-2.33,2.3-2.35l0,0h25.48C28.41,13.15,40.26,0,54.79,0s26.39,13.15,27.01,29.65 h25.4h0.04c1.3,0,2.35,1.05,2.35,2.35c0,0.04,0,0.08,0,0.12v0l-3.96,79.81l-0.04,0.68C105.12,118.21,100.59,122.73,94.99,122.88 L94.99,122.88z"/>
                            </svg>
                            <h3 class="text-2xl font-bold">Shopee</h3>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-xl aspect-video overflow-hidden cursor-pointer group hover:shadow-lg transition-shadow">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1611224923853-80b023f02d71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80')"></div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                        <div class="text-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform scale-90 group-hover:scale-100">
                            <svg class="w-20 h-20 mx-auto mb-3" viewBox="0 0 455 512.098" fill="white">
                                <path d="M321.331.011h-81.882v347.887c0 45.59-32.751 74.918-72.582 74.918-39.832 0-75.238-29.327-75.238-74.918 0-52.673 41.165-80.485 96.044-74.727v-88.153c-7.966-1.333-15.932-1.77-22.576-1.77C75.249 183.248 0 255.393 0 344.794c0 94.722 74.353 167.304 165.534 167.304 80.112 0 165.097-58.868 165.097-169.96V161.109c35.406 35.406 78.341 46.476 124.369 46.476V126.14C398.35 122.151 335.494 84.975 321.331 0v.011z"/>
                            </svg>
                            <h3 class="text-2xl font-bold">TikTok</h3>
                        </div>  
                    </div>
                </div>

                <?php
                // Parse GPS coordinates
                $gps_coords = explode(',', $distro['gps']);
                $latitude = isset($gps_coords[0]) ? trim($gps_coords[0]) : '';
                $longitude = isset($gps_coords[1]) ? trim($gps_coords[1]) : '';
                ?>
                <div class="aspect-video overflow-hidden rounded-xl">
                    <iframe allowfullscreen="" class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d997.4195273151761!2d<?php echo htmlspecialchars($longitude); ?>!3d<?php echo htmlspecialchars($latitude); ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1766382075981!5m2!1sid!2sid" style="border:0;">
                    </iframe>
                </div>
            </div>
        </section>

    <!--  Find Us -->
   

   </main>


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
         <!-- Cart items will be dynamically populated here -->
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

   <?php include 'footer.php'; ?>
  </div>
  
  <!-- Cookie Consent Overlay -->
  <div id="cookie-consent" class="cookie-consent-overlay">
    <div class="cookie-consent-content">
      <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-foreground-light dark:text-foreground-dark mb-2 flex items-center gap-2">
            <span class="material-symbols-outlined text-2xl text-primary">cookie</span>
            Cookie Consent
          </h3>
          <p class="text-sm text-secondary-light dark:text-secondary-dark leading-relaxed">
            We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. 
            By clicking "Accept All", you consent to our use of all cookies. 
            You can manage your preferences by clicking "Settings".
          </p>
          <div class="mt-3 flex flex-wrap gap-2">
            <button onclick="showCookieDetails()" class="text-xs text-primary hover:underline font-medium flex items-center gap-1">
              <span class="material-symbols-outlined text-base">info</span>
              Learn more about cookies
            </button>
          </div>
        </div>
        
        <div class="flex flex-col gap-3 mt-4 md:mt-0 md:pr-4">
          <!-- Mobile: 2 buttons horizontal on first row, Settings below -->
          <!-- Desktop: all 3 buttons horizontal -->
          <div class="flex flex-1 gap-3 justify-center md:justify-end">
            <button onclick="acceptAllCookies()" class="cookie-button bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg font-medium text-sm shadow-lg flex items-center justify-center gap-2 flex-1 md:flex-auto md:min-w-[110px] md:max-w-[140px]">
              <span class="material-symbols-outlined text-base">check_circle</span>
              Accept All
            </button>
            <button onclick="acceptEssentialCookies()" class="cookie-button bg-surface-light dark:bg-surface-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-foreground-light dark:text-foreground-dark px-4 py-2 rounded-lg font-medium text-sm border border-gray-300 dark:border-gray-600 flex items-center justify-center gap-2 flex-1 md:flex-auto md:min-w-[110px] md:max-w-[140px]">
              <span class="material-symbols-outlined text-base">shield</span>
              Essential Only
            </button>
            <!-- Settings button only visible on desktop (inline with other buttons) -->
            <button onclick="showCookieSettings()" class="cookie-button bg-surface-light dark:bg-surface-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-foreground-light dark:text-foreground-dark px-4 py-2 rounded-lg font-medium text-sm border border-gray-300 dark:border-gray-600 flex items-center justify-center gap-2 hidden md:flex md:flex-auto md:min-w-[110px] md:max-w-[140px]">
              <span class="material-symbols-outlined text-base">tune</span>
              Settings
            </button>
          </div>
          <!-- Settings button only visible on mobile (below other buttons) -->
          <button onclick="showCookieSettings()" class="cookie-button bg-surface-light dark:bg-surface-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-foreground-light dark:text-foreground-dark px-4 py-2 rounded-lg font-medium text-sm border border-gray-300 dark:border-gray-600 flex items-center justify-center gap-2 w-full md:hidden">
            <span class="material-symbols-outlined text-base">tune</span>
            Settings
          </button>
        </div>
      </div>
      
      <!-- Cookie Details (Hidden by default) -->
      <div id="cookie-details" class="hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
        <h4 class="font-medium text-foreground-light dark:text-foreground-dark mb-3">Cookie Categories</h4>
        <div class="space-y-3">
          <div class="flex items-start gap-3">
            <input type="checkbox" id="essential-cookies" checked disabled class="mt-1">
            <div class="flex-1">
              <label for="essential-cookies" class="font-medium text-sm text-foreground-light dark:text-foreground-dark">Essential Cookies</label>
              <p class="text-xs text-secondary-light dark:text-secondary-dark mt-1">Required for the site to function properly.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <input type="checkbox" id="analytics-cookies" checked class="mt-1">
            <div class="flex-1">
              <label for="analytics-cookies" class="font-medium text-sm text-foreground-light dark:text-foreground-dark">Analytics Cookies</label>
              <p class="text-xs text-secondary-light dark:text-secondary-dark mt-1">Help us improve our website by collecting anonymous usage data.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <input type="checkbox" id="marketing-cookies" class="mt-1">
            <div class="flex-1">
              <label for="marketing-cookies" class="font-medium text-sm text-foreground-light dark:text-foreground-dark">Marketing Cookies</label>
              <p class="text-xs text-secondary-light dark:text-secondary-dark mt-1">Used to deliver personalized advertisements.</p>
            </div>
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button onclick="hideCookieDetails()" class="px-3 py-1 text-sm text-secondary-light dark:text-secondary-dark hover:text-foreground-light dark:hover:text-foreground-dark">
            Cancel
          </button>
          <button onclick="saveCookiePreferences()" class="px-3 py-1 bg-primary hover:bg-primary-hover text-white text-sm rounded">
            Save Preferences
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Include WhatsApp Floating Button Component -->
  <?php include 'components/whatsapp-float-btn.php'; ?>
  
  <!-- Include Product Modal -->
  <?php include 'modal/product-modal.php'; ?>
  <script src="script/newarrival_touch.js"></script>
  <script src="script/cart.js?v=<?php echo time(); ?>"></script>
  <script>
    function addToCartFromNewArrivals(product) {
        const cartProduct = {
            nama_produk: product.nama_produk,
            kode_produk: product.kode_produk,
            harga_aktif: parseInt(product.harga_aktif),
            gambar: 'images/' + product.gambar1
        };
        
        // Open product modal first
        openProductModal(product.id_produk);
        
        // Also add to cart (optional - you can remove this if you only want add to cart from modal)
        // window.cartFunctions.addToCart(cartProduct);
    }
  </script>
  
  <!-- Cookie Consent Script -->
  <script>
  // Cookie Consent Management
  document.addEventListener('DOMContentLoaded', function() {
      const cookieConsent = document.getElementById('cookie-consent');
      const cookieDetails = document.getElementById('cookie-details');
      
      // Check if user has already made a consent choice
      if (!getCookie('cookie_consent')) {
          // Show cookie consent after a short delay to let page load
          setTimeout(() => {
              cookieConsent.classList.add('show');
          }, 1500);
      }
  });
  
  // Set a cookie
  function setCookie(name, value, days) {
      const expires = new Date();
      expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
      const path = window.location.pathname.includes('distro') ? '/distro/' : '/';
      document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=' + path + ';samesite=strict';
  }
  
  // Get a cookie
  function getCookie(name) {
      const nameEQ = name + "=";
      const ca = document.cookie.split(';');
      for(let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) === ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
  }
  
  // Accept all cookies
  function acceptAllCookies() {
      const preferences = {
          essential: true,
          analytics: true,
          marketing: true
      };
      
      // Save preferences
      setCookie('cookie_consent', 'accepted', 365);
      setCookie('cookie_preferences', JSON.stringify(preferences), 365);
      
      // Initialize analytics if accepted
      if (preferences.analytics) {
          initializeAnalytics();
      }
      
      // Initialize marketing if accepted
      if (preferences.marketing) {
          initializeMarketing();
      }
      
      // Hide cookie consent
      hideCookieConsent();
      
      // Show success notification
      showNotification('Cookie preferences saved!', 'success');
  }
  
  // Accept essential cookies only
  function acceptEssentialCookies() {
      const preferences = {
          essential: true,
          analytics: false,
          marketing: false
      };
      
      // Save preferences
      setCookie('cookie_consent', 'essential', 365);
      setCookie('cookie_preferences', JSON.stringify(preferences), 365);
      
      // Hide cookie consent
      hideCookieConsent();
      
      // Show notification
      showNotification('Only essential cookies enabled', 'info');
  }
  
  // Show cookie settings
  function showCookieSettings() {
      const details = document.getElementById('cookie-details');
      details.classList.remove('hidden');
      
      // Load current preferences
      const savedPrefs = getCookie('cookie_preferences');
      if (savedPrefs) {
          const prefs = JSON.parse(savedPrefs);
          document.getElementById('analytics-cookies').checked = prefs.analytics;
          document.getElementById('marketing-cookies').checked = prefs.marketing;
      }
  }
  
  // Show cookie details
  function showCookieDetails() {
      showCookieSettings();
  }
  
  // Hide cookie details
  function hideCookieDetails() {
      const details = document.getElementById('cookie-details');
      details.classList.add('hidden');
  }
  
  // Save cookie preferences
  function saveCookiePreferences() {
      const preferences = {
          essential: true,
          analytics: document.getElementById('analytics-cookies').checked,
          marketing: document.getElementById('marketing-cookies').checked
      };
      
      // Save preferences
      setCookie('cookie_consent', 'custom', 365);
      setCookie('cookie_preferences', JSON.stringify(preferences), 365);
      
      // Initialize analytics if accepted
      if (preferences.analytics) {
          initializeAnalytics();
      }
      
      // Initialize marketing if accepted
      if (preferences.marketing) {
          initializeMarketing();
      }
      
      // Hide cookie consent
      hideCookieConsent();
      
      // Show notification
      showNotification('Your cookie preferences have been saved', 'success');
  }
  
  // Hide cookie consent overlay
  function hideCookieConsent() {
      const cookieConsent = document.getElementById('cookie-consent');
      cookieConsent.classList.remove('show');
      
      // Remove from DOM after animation
      setTimeout(() => {
          cookieConsent.style.display = 'none';
      }, 400);
  }
  
  // Show notification
  function showNotification(message, type = 'info') {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `fixed bottom-4 right-4 z-[10000] px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-full`;
      
      // Style based on type
      if (type === 'success') {
          notification.classList.add('bg-green-500', 'text-white');
      } else if (type === 'error') {
          notification.classList.add('bg-red-500', 'text-white');
      } else {
          notification.classList.add('bg-blue-500', 'text-white');
      }
      
      notification.innerHTML = `
          <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-base">
                  ${type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info'}
              </span>
              <span class="text-sm font-medium">${message}</span>
          </div>
      `;
      
      // Add to page
      document.body.appendChild(notification);
      
      // Animate in
      setTimeout(() => {
          notification.classList.remove('translate-y-full');
          notification.classList.add('translate-y-0');
      }, 10);
      
      // Remove after 3 seconds
      setTimeout(() => {
          notification.classList.add('translate-y-full');
          setTimeout(() => {
              notification.remove();
          }, 300);
      }, 3000);
  }
  
  // Initialize analytics (placeholder for Google Analytics, etc.)
  function initializeAnalytics() {
      console.log('Analytics cookies accepted');
      // Add your analytics initialization code here
      // Example: gtag('config', 'GA_MEASUREMENT_ID');
  }
  
  // Initialize marketing (placeholder for marketing pixels, etc.)
  function initializeMarketing() {
      console.log('Marketing cookies accepted');
      // Add your marketing initialization code here
      // Example: fbq('init', 'YOUR_PIXEL_ID');
  }
  
  // Check consent on page load and initialize services accordingly
  document.addEventListener('DOMContentLoaded', function() {
      const consent = getCookie('cookie_consent');
      if (consent) {
          const prefs = getCookie('cookie_preferences');
          if (prefs) {
              const preferences = JSON.parse(prefs);
              if (preferences.analytics) {
                  initializeAnalytics();
              }
              if (preferences.marketing) {
                  initializeMarketing();
              }
          }
      }
  });
  </script>
 </body>
</html>