// Shopping Cart JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const cartButton = document.getElementById('cart-button');
    const cartModal = document.getElementById('cart-modal');
    const cartPanel = document.getElementById('cart-panel');
    const closeCart = document.getElementById('close-cart');
    const cartBackdrop = document.getElementById('cart-backdrop');
    const cartBadge = document.getElementById('cart-badge');
    
    // Cart data (will be populated from backend)
    let cartItems = [];
    
    // Make cart items globally accessible
    window.cartItems = cartItems;
    
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
            if (itemCountElement) itemCountElement.textContent = '0';
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
            if (itemCountElement) itemCountElement.textContent = `${totalItems} ${totalItems > 1 ? 'items' : 'item'}`;
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
            
            const phoneNumber = window.distroPhone || '628123456789'; // Use phone number from PHP
            let message = 'Halo, saya ingin memesan produk berikut:\\n\\n';
            let total = 0;
            
            cartItems.forEach((item, index) => {
                const subtotal = item.harga_aktif * item.qty;
                total += subtotal;
                message += `${index + 1}. ${item.nama_produk}\\n`;
                message += `   Kode: ${item.kode_produk}\\n`;
                message += `   Harga: Rp ${item.harga_aktif.toLocaleString('id-ID')} x ${item.qty}\\n`;
                message += `   Subtotal: Rp ${subtotal.toLocaleString('id-ID')}\\n\\n`;
            });
            
            message += `Total: Rp ${total.toLocaleString('id-ID')}\\n\\n`;
            message += 'Mohon informasikan ketersediaan dan cara pemesanannya. Terima kasih!';
            
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
            
            window.open(whatsappUrl, '_blank');
        }
    });
    
    // Event listeners
    if (cartButton) cartButton.addEventListener('click', openCart);
    if (closeCart) closeCart.addEventListener('click', closeCartModal);
    if (cartBackdrop) cartBackdrop.addEventListener('click', closeCartModal);
    
    // Close cart on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && cartModal && !cartModal.classList.contains('hidden')) {
            closeCartModal();
        }
    });
    
    // Load cart from storage on page load
    loadCartFromStorage();
    

    
    // Initial render
    renderCartItems();
});

// Make cart functions globally accessible
window.cartFunctions = {
    addToCart: function(product) {
        const existingItemIndex = window.cartItems.findIndex(item => item.kode_produk === product.kode_produk);
        
        if (existingItemIndex > -1) {
            window.cartItems[existingItemIndex].qty += 1;
        } else {
            window.cartItems.push({
                ...product,
                qty: 1
            });
        }
        
        localStorage.setItem('shoppingCart', JSON.stringify(window.cartItems));
        
        // Re-render if elements exist
        const container = document.getElementById('cart-items-container');
        const badge = document.getElementById('cart-badge');
        if (container && badge) {
            renderCartItems();
        }
        
        // Show success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-pulse';
        notification.textContent = 'Product added to cart!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 2000);
    }
};
