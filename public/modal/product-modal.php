<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeProductModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative flex items-center justify-center p-1 sm:p-2 md:p-3 min-h-screen">
        <div id="modalContent" class="relative flex flex-col w-full max-w-4xl h-auto max-h-[90vh] bg-background-light dark:bg-background-dark rounded-xl shadow-2xl overflow-hidden mx-auto">
            
            <!-- Close Button -->
            <div class="absolute top-0 right-0 z-10 p-2 sm:p-3">
                <button onclick="closeProductModal()" class="p-1.5 sm:p-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors rounded-full bg-white/80 dark:bg-black/50 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">close</span>
                </button>
            </div>
            
            <div id="modalBody" class="flex flex-col md:flex-row w-full h-full overflow-hidden">
                <!-- Image Section -->
                <div class="w-full md:w-2/5 lg:w-1/2 p-2 sm:p-3 md:p-4 flex flex-col">
                    <div class="flex flex-col gap-2 sm:gap-3 h-full">
                        <!-- Main Image Container -->
                        <div class="flex-1 relative group overflow-hidden rounded-lg">
                            <div id="imageContainer" class="w-full h-full relative cursor-grab active:cursor-grabbing touch-pan-y touch-pinch-zoom">
                                <img id="modalMainImage" src="" alt="Product Image" class="w-full h-full object-contain transition-transform duration-200" draggable="false">
                                <!-- Loading indicator -->
                                <div id="imageLoader" class="absolute inset-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center hidden">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                                </div>
                            </div>
                            <!-- Zoom Controls -->
                            <div class="absolute bottom-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button id="zoomInBtn" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center bg-black/50 text-white hover:bg-black/70 transition-colors" style="border-radius: 50%;">
                                    <span class="material-symbols-outlined text-sm sm:text-base">add</span>
                                </button>
                                <button id="zoomOutBtn" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center bg-black/50 text-white hover:bg-black/70 transition-colors" style="border-radius: 50%;">
                                    <span class="material-symbols-outlined text-sm sm:text-base">remove</span>
                                </button>
                                <button id="zoomResetBtn" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center bg-black/50 text-white hover:bg-black/70 transition-colors" style="border-radius: 50%;">
                                    <span class="material-symbols-outlined text-sm sm:text-base">fit_screen</span>
                                </button>
                            </div>
                            <!-- Image Navigation -->
                            <button id="prevImageBtn" class="absolute top-1/2 left-1 sm:left-2 -translate-y-1/2 p-1.5 sm:p-2 bg-white/70 dark:bg-black/50 text-gray-800 dark:text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-sm sm:text-base">arrow_back_ios_new</span>
                            </button>
                            <button id="nextImageBtn" class="absolute top-1/2 right-1 sm:right-2 -translate-y-1/2 p-1.5 sm:p-2 bg-white/70 dark:bg-black/50 text-gray-800 dark:text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-sm sm:text-base">arrow_forward_ios</span>
                            </button>
                            <!-- Zoom Level Indicator -->
                            <div class="absolute top-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <span id="zoomLevel">100%</span>
                            </div>
                        </div>
                        
                        <!-- Thumbnail Images -->
                        <div id="modalThumbnails" class="flex gap-1.5 sm:gap-2 overflow-x-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                            <div class="flex items-stretch">
                                <!-- Thumbnails will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Details Section -->
                <div class="w-full md:w-3/5 lg:w-1/2 p-2 sm:p-3 md:p-4 flex flex-col overflow-y-auto">
                    <!-- Brand -->
                    <p id="modalBrand" class="text-primary dark:text-primary/90 text-xs sm:text-sm font-medium leading-normal tracking-wide">
                        Brand Name
                    </p>
                    
                    <!-- Product Name -->
                    <div class="flex flex-wrap justify-between gap-2 pt-1 pb-2">
                        <h1 id="modalProductName" class="text-gray-900 dark:text-gray-50 text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold leading-tight flex-1">
                            Product Name
                        </h1>
                    </div>
                    
                    <!-- Price -->
                    <div class="flex items-baseline gap-2 sm:gap-3 pb-3">
                        <h3 id="modalPrice" class="text-primary dark:text-primary tracking-tight text-xl sm:text-2xl md:text-3xl font-extrabold leading-none">
                            IDR 0
                        </h3>
                        <p id="modalOriginalPrice" class="text-gray-400 dark:text-gray-500 text-sm sm:text-base md:text-lg font-medium leading-normal line-through hidden">
                            IDR 0
                        </p>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="flex items-center gap-2 mb-3 sm:mb-4">
                        <span id="modalStockIndicator" class="w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-500 rounded-full"></span>
                        <p id="modalStockStatus" class="text-green-600 dark:text-green-400 text-xs sm:text-sm font-semibold">
                            In Stock
                        </p>
                    </div>
                    
                    <!-- Product Details Grid -->
                    <div class="grid grid-cols-2 gap-2 sm:gap-3 p-2 sm:p-3 mb-3 sm:mb-4 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10">
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Ukuran</p>
                            <p id="modalSize" class="text-gray-900 dark:text-gray-200 text-xs sm:text-sm font-medium mt-0.5">All Size (M-L)</p>
                        </div>
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Kode Produk</p>
                            <p id="modalProductCode" class="text-gray-900 dark:text-gray-200 text-xs sm:text-sm font-medium mt-0.5">PROD-001</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p id="modalDescription" class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6">
                        Crafted from premium materials with attention to detail and quality. This piece combines style and comfort for the perfect addition to your wardrobe.
                    </p>
                    
                    <!-- Add to Cart Button -->
                    <button id="add-to-cart-btn" class="w-full py-2 sm:py-3 md:py-4 px-3 sm:px-4 md:px-6 rounded-full bg-primary hover:bg-[#b00e44] text-white text-sm sm:text-base md:text-lg font-bold shadow-lg shadow-primary/25 hover:shadow-xl active:scale-[0.99] transition-all flex items-center justify-center gap-1.5 sm:gap-2 mb-3 sm:mb-4">
                        <span class="material-symbols-outlined text-lg sm:text-xl md:text-2xl">shopping_cart</span>
                        <span>Add to Cart</span>
                    </button>
                    
                    <!-- Shop Links -->
                    <div id="modalShopLinks" class="mt-auto pt-3 sm:pt-4 border-t border-gray-200 dark:border-white/10">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 sm:mb-3 uppercase tracking-wide">Shop this look on</p>
                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                            <a id="modalShopeeLink" href="#" class="flex items-center justify-center gap-1.5 sm:gap-2 h-8 sm:h-10 md:h-12 px-2 sm:px-3 md:px-4 rounded-full bg-[#ee4d2d] text-white text-xs sm:text-sm md:text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#d73211] transition-colors hidden" target="_blank">
                                <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[20px]">shopping_bag</span>
                                <span>Shopee</span>
                            </a>
                            <a id="modalTiktokLink" href="#" class="flex items-center justify-center gap-1.5 sm:gap-2 h-8 sm:h-10 md:h-12 px-2 sm:px-3 md:px-4 rounded-full bg-black dark:bg-white dark:text-black dark:hover:bg-gray-200 text-white text-xs sm:text-sm md:text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-800 transition-colors hidden" target="_blank">
                                <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[20px]">storefront</span>
                                <span>TikTok</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Product modal functionality moved to script/product-modal.js -->
