// Modal functionality
function openProductModal(productId) {
    // Fetch product data via AJAX
    fetch('../api/product-details.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update modal content with fetched data
                updateModalContent(data.product);
                // Show modal
                const modal = document.getElementById('productModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    // Initialize zoom functionality
                    initializeZoomOnModalOpen();
                }
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
        });
}



function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function updateModalContent(product) {
    // Store product images globally for navigation
    window.currentProductImages = [];
    
    // Add images to array
    if (product.gambar1) window.currentProductImages.push(product.gambar1);
    if (product.gambar2) window.currentProductImages.push(product.gambar2);
    if (product.gambar3) window.currentProductImages.push(product.gambar3);
    
    window.currentImageIndex = 0;
    
    // Update main image
    updateMainImage(0);
    
    // Update thumbnails
    const thumbnailsContainer = document.getElementById('modalThumbnails');
    if (thumbnailsContainer) {
        const thumbnailFlex = thumbnailsContainer.querySelector('.flex');
        if (thumbnailFlex) {
            thumbnailFlex.innerHTML = '';
            
            // Add image 1 thumbnail
            const thumb1 = createThumbnail(product.gambar1, product.nama_produk, 0);
            thumbnailFlex.appendChild(thumb1);
            
            // Add image 2 thumbnail if exists
            if (product.gambar2) {
                const thumb2 = createThumbnail(product.gambar2, product.nama_produk, 1);
                thumbnailFlex.appendChild(thumb2);
            }
            
            // Add image 3 thumbnail if exists
            if (product.gambar3) {
                const thumb3 = createThumbnail(product.gambar3, product.nama_produk, 2);
                thumbnailFlex.appendChild(thumb3);
            }
        }
    }
    
    // Setup navigation buttons
    setupImageNavigation(product.nama_produk);
    
    // Update product details
    const modalBrand = document.getElementById('modalBrand');
    const modalProductName = document.getElementById('modalProductName');
    const modalPrice = document.getElementById('modalPrice');
    const modalOriginalPrice = document.getElementById('modalOriginalPrice');
    const modalStockIndicator = document.getElementById('modalStockIndicator');
    const modalStockStatus = document.getElementById('modalStockStatus');
    const modalSize = document.getElementById('modalSize');
    const modalProductCode = document.getElementById('modalProductCode');
    const modalDescription = document.getElementById('modalDescription');
    const modalShopeeLink = document.getElementById('modalShopeeLink');
    const modalTiktokLink = document.getElementById('modalTiktokLink');
    const shopLinksSection = document.getElementById('modalShopLinks');
    
    if (modalBrand) modalBrand.textContent = product.brand || 'Brand Name';
    if (modalProductName) modalProductName.textContent = product.nama_produk || 'Product Name';
    if (modalPrice) modalPrice.textContent = 'IDR ' + (product.harga_aktif ? numberFormat(product.harga_aktif) : '0');
    
    // Update original price if exists
    if (modalOriginalPrice) {
        if (product.harga_coret && product.harga_coret > 0) {
            modalOriginalPrice.textContent = 'IDR ' + numberFormat(product.harga_coret);
            modalOriginalPrice.classList.remove('hidden');
        } else {
            modalOriginalPrice.classList.add('hidden');
        }
    }
    
    // Update stock status
    if (modalStockIndicator && modalStockStatus) {
        const inStock = product.in_stok == '1';
        
        if (inStock) {
            modalStockIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
            modalStockStatus.className = 'text-green-600 dark:text-green-400 text-sm font-semibold';
            modalStockStatus.textContent = 'In Stock';
        } else {
            modalStockIndicator.className = 'w-3 h-3 bg-red-500 rounded-full';
            modalStockStatus.className = 'text-red-600 dark:text-red-400 text-sm font-semibold';
            modalStockStatus.textContent = 'Out of Stock';
        }
    }
    
    // Update size and product code
    if (modalSize) modalSize.textContent = product.ukuran || 'All Size (M-L)';
    if (modalProductCode) modalProductCode.textContent = product.kode_produk || 'PROD-' + String(product.id_produk || '001').padStart(3, '0');

    // Update color (warna) with color square
    const modalColor = document.getElementById('modalColor');
    const modalColorSquare = document.getElementById('modalColorSquare');
    if (modalColor && modalColorSquare) {
        const warna = product.warna || 'Black';
        modalColor.textContent = warna;
        
        // Set the color square background based on the warna value
        const colorValue = getColorFromName(warna);
        modalColorSquare.style.backgroundColor = colorValue;
        
        // Adjust border color for light colors
        const lightColors = ['#FFFFFF', '#F5F5F5', '#FFC0CB', '#FFFFE0', '#E6E6FA'];
        if (lightColors.includes(colorValue.toUpperCase())) {
            modalColorSquare.style.borderColor = '#d1d5db';
        } else {
            modalColorSquare.style.borderColor = '#9ca3af';
        }
    }
    
    // Update description
    if (modalDescription) {
        modalDescription.textContent = product.deskripsi || 'Crafted from premium materials with attention to detail and quality. This piece combines style and comfort for the perfect addition to your wardrobe.';
    }
    
    // Update shop links
    const hasShopeeLink = product.shopee_link && product.shopee_link.trim() !== '';
    const hasTiktokLink = product.tiktok_link && product.tiktok_link.trim() !== '';
    
    if (modalShopeeLink) {
        if (hasShopeeLink) {
            modalShopeeLink.href = product.shopee_link;
            modalShopeeLink.classList.remove('hidden');
        } else {
            modalShopeeLink.classList.add('hidden');
        }
    }
    
    if (modalTiktokLink) {
        if (hasTiktokLink) {
            modalTiktokLink.href = product.tiktok_link;
            modalTiktokLink.classList.remove('hidden');
        } else {
            modalTiktokLink.classList.add('hidden');
        }
    }
    
    // Hide entire shop links section if both links are not available
    if (shopLinksSection) {
        if (!hasShopeeLink && !hasTiktokLink) {
            shopLinksSection.classList.add('hidden');
        } else {
            shopLinksSection.classList.remove('hidden');
        }
    }
}

function updateMainImage(index) {
    const mainImage = document.getElementById('modalMainImage');
    const imageLoader = document.getElementById('imageLoader');
    if (mainImage && window.currentProductImages && window.currentProductImages[index]) {
        // Show loader
        if (imageLoader) {
            imageLoader.classList.remove('hidden');
        }
        
        // Load new image
        const img = new Image();
        img.onload = function() {
            mainImage.src = this.src;
            // Hide loader
            if (imageLoader) {
                imageLoader.classList.add('hidden');
            }
            // Reset zoom when image changes
            resetZoom();
        };
        img.src = 'images/' + window.currentProductImages[index];
        
        // Update thumbnail borders to match current image
        updateThumbnailSelection(index);
        
        // Update zoom index to stay in sync
        window.currentImageIndex = index;
    }
}

function updateThumbnailSelection(activeIndex) {
    const thumbnailsContainer = document.getElementById('modalThumbnails');
    if (thumbnailsContainer) {
        const thumbnailFlex = thumbnailsContainer.querySelector('.flex');
        if (thumbnailFlex) {
            const thumbnails = thumbnailFlex.querySelectorAll('div');
            
            thumbnails.forEach((thumb) => {
                const thumbIndex = parseInt(thumb.getAttribute('data-index'));
                if (thumbIndex === activeIndex) {
                    thumb.classList.remove('border-transparent', 'hover:border-primary/50');
                    thumb.classList.add('border-primary');
                } else {
                    thumb.classList.remove('border-primary');
                    thumb.classList.add('border-transparent', 'hover:border-primary/50');
                }
            });
        }
    }
}

function setupImageNavigation(altText) {
    const prevBtn = document.getElementById('prevImageBtn');
    const nextBtn = document.getElementById('nextImageBtn');
    
    // Remove existing event listeners
    if (prevBtn) prevBtn.replaceWith(prevBtn.cloneNode(true));
    if (nextBtn) nextBtn.replaceWith(nextBtn.cloneNode(true));
    
    // Get fresh references
    const newPrevBtn = document.getElementById('prevImageBtn');
    const newNextBtn = document.getElementById('nextImageBtn');
    
    if (newPrevBtn && window.currentProductImages && window.currentProductImages.length > 1) {
        newPrevBtn.addEventListener('click', function() {
            window.currentImageIndex = (window.currentImageIndex - 1 + window.currentProductImages.length) % window.currentProductImages.length;
            updateMainImage(window.currentImageIndex);
        });
    }
    
    if (newNextBtn && window.currentProductImages && window.currentProductImages.length > 1) {
        newNextBtn.addEventListener('click', function() {
            window.currentImageIndex = (window.currentImageIndex + 1) % window.currentProductImages.length;
            updateMainImage(window.currentImageIndex);
        });
    }
    
    // Hide navigation buttons if only one image
    if (window.currentProductImages && window.currentProductImages.length <= 1) {
        if (newPrevBtn) newPrevBtn.style.display = 'none';
        if (newNextBtn) newNextBtn.style.display = 'none';
    } else {
        if (newPrevBtn) newPrevBtn.style.display = 'block';
        if (newNextBtn) newNextBtn.style.display = 'block';
    }
}

function createThumbnail(imageName, altText, index) {
    const div = document.createElement('div');
    div.className = `flex h-full flex-col rounded-lg border-2 ${index === 0 ? 'border-primary' : 'border-transparent hover:border-primary/50'} min-w-12 sm:min-w-14 md:min-w-16 lg:min-w-20 cursor-pointer`;
    div.setAttribute('data-index', index);
    
    const img = document.createElement('div');
    img.className = 'w-full bg-center bg-no-repeat aspect-square bg-cover rounded';
    img.setAttribute('data-alt', altText);
    img.style.backgroundImage = `url("images/${imageName}")`;
    
    div.appendChild(img);
    
    // Add click handler to change main image
    div.addEventListener('click', function() {
        const clickedIndex = parseInt(this.getAttribute('data-index'));
        window.currentImageIndex = clickedIndex;
        updateMainImage(clickedIndex);
    });
    
    return div;
}

function numberFormat(num) {
    return new Intl.NumberFormat('id-ID').format(parseInt(num) || 0);
}

// Pan and Zoom functionality
let imageZoomState = {
    scale: 1,
    minScale: 1,
    maxScale: 5,
    translateX: 0,
    translateY: 0,
    isDragging: false,
    startX: 0,
    startY: 0,
    lastTouchDistance: 0
};

function initializeImageZoom() {
    const container = document.getElementById('imageContainer');
    const image = document.getElementById('modalMainImage');
    const zoomInBtn = document.getElementById('zoomInBtn');
    const zoomOutBtn = document.getElementById('zoomOutBtn');
    const zoomResetBtn = document.getElementById('zoomResetBtn');
    const zoomLevel = document.getElementById('zoomLevel');
    
    if (!container || !image) return;
    
    // Mouse events
    container.addEventListener('mousedown', startDrag);
    container.addEventListener('mousemove', drag);
    container.addEventListener('mouseup', endDrag);
    container.addEventListener('mouseleave', endDrag);
    container.addEventListener('wheel', handleWheel);
    
    // Touch events
    container.addEventListener('touchstart', handleTouchStart, { passive: false });
    container.addEventListener('touchmove', handleTouchMove, { passive: false });
    container.addEventListener('touchend', handleTouchEnd);
    
    // Button events
    if (zoomInBtn) zoomInBtn.addEventListener('click', () => zoom(1.2));
    if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => zoom(0.8));
    if (zoomResetBtn) zoomResetBtn.addEventListener('click', resetZoom);
    
    // Double click to zoom
    container.addEventListener('dblclick', handleDoubleClick);
}

function startDrag(e) {
    if (imageZoomState.scale <= 1) return;
    
    imageZoomState.isDragging = true;
    imageZoomState.startX = e.clientX - imageZoomState.translateX;
    imageZoomState.startY = e.clientY - imageZoomState.translateY;
}

function drag(e) {
    if (!imageZoomState.isDragging) return;
    
    e.preventDefault();
    imageZoomState.translateX = e.clientX - imageZoomState.startX;
    imageZoomState.translateY = e.clientY - imageZoomState.startY;
    
    updateImageTransform();
}

function endDrag() {
    imageZoomState.isDragging = false;
}

function handleWheel(e) {
    e.preventDefault();
    const delta = e.deltaY > 0 ? 0.9 : 1.1;
    zoom(delta);
}

function handleTouchStart(e) {
    if (e.touches.length === 1) {
        if (imageZoomState.scale > 1) {
            imageZoomState.isDragging = true;
            imageZoomState.startX = e.touches[0].clientX - imageZoomState.translateX;
            imageZoomState.startY = e.touches[0].clientY - imageZoomState.translateY;
        }
    } else if (e.touches.length === 2) {
        imageZoomState.isDragging = false;
        imageZoomState.lastTouchDistance = getTouchDistance(e.touches);
    }
}

function handleTouchMove(e) {
    e.preventDefault();
    
    if (e.touches.length === 1 && imageZoomState.isDragging) {
        imageZoomState.translateX = e.touches[0].clientX - imageZoomState.startX;
        imageZoomState.translateY = e.touches[0].clientY - imageZoomState.startY;
        updateImageTransform();
    } else if (e.touches.length === 2) {
        const currentDistance = getTouchDistance(e.touches);
        const scale = currentDistance / imageZoomState.lastTouchDistance;
        imageZoomState.lastTouchDistance = currentDistance;
        zoom(scale);
    }
}

function handleTouchEnd() {
    imageZoomState.isDragging = false;
}

function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx * dx + dy * dy);
}

function handleDoubleClick(e) {
    const rect = e.currentTarget.getBoundingClientRect();
    const x = e.clientX - rect.left - rect.width / 2;
    const y = e.clientY - rect.top - rect.height / 2;
    
    if (imageZoomState.scale === 1) {
        zoom(2);
        imageZoomState.translateX = -x;
        imageZoomState.translateY = -y;
    } else {
        resetZoom();
    }
    updateImageTransform();
}

function zoom(delta) {
    const newScale = imageZoomState.scale * delta;
    imageZoomState.scale = Math.max(imageZoomState.minScale, Math.min(imageZoomState.maxScale, newScale));
    
    if (imageZoomState.scale === 1) {
        imageZoomState.translateX = 0;
        imageZoomState.translateY = 0;
    }
    
    updateImageTransform();
    updateZoomLevel();
}

function resetZoom() {
    imageZoomState.scale = 1;
    imageZoomState.translateX = 0;
    imageZoomState.translateY = 0;
    updateImageTransform();
    updateZoomLevel();
}

function updateImageTransform() {
    const image = document.getElementById('modalMainImage');
    if (image) {
        image.style.transform = `translate(${imageZoomState.translateX}px, ${imageZoomState.translateY}px) scale(${imageZoomState.scale})`;
    }
}

function updateZoomLevel() {
    const zoomLevel = document.getElementById('zoomLevel');
    if (zoomLevel) {
        zoomLevel.textContent = `${Math.round(imageZoomState.scale * 100)}%`;
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const zoomModal = document.getElementById('imageZoomModal');
        if (zoomModal && !zoomModal.classList.contains('hidden')) {
            closeImageZoom();
        } else {
            closeProductModal();
        }
    }
});

// Initialize zoom when modal opens
function initializeZoomOnModalOpen() {
    setTimeout(() => {
        initializeImageZoom();
        updateZoomLevel();
    }, 100);
}

/**
 * Convert color name to hex color value
 * @param {string} colorName - Color name in Indonesian/Malay
 * @returns {string} Hex color code
 */
function getColorFromName(colorName) {
    if (!colorName) return '#000000';
    
    const colorMap = {
        // Basic colors
        'hitam': '#000000',
        'black': '#000000',
        'putih': '#FFFFFF',
        'white': '#FFFFFF',
        'merah': '#DC2626',
        'red': '#DC2626',
        'hijau': '#16A34A',
        'green': '#16A34A',
        'biru': '#2563EB',
        'blue': '#2563EB',
        'kuning': '#EAB308',
        'yellow': '#EAB308',
        'oranye': '#F97316',
        'orange': '#F97316',
        'ungu': '#9333EA',
        'purple': '#9333EA',
        'pink': '#EC4899',
        'coklat': '#92400E',
        'brown': '#92400E',
        'abu': '#6B7280',
        'abu-abu': '#6B7280',
        'grey': '#6B7280',
        'gray': '#6B7280',
        'navy': '#1E3A8A',
        'cream': '#FEF3C7',
        'maroon': '#991B1B',
        'tosca': '#0891B2',
        'cyan': '#06B6D4',
        'silver': '#D1D5DB',
        'gold': '#F59E0B',
        'beige': '#F5F5DC',
        'khaki': '#C6B091',
        'army': '#4A5D23',
        'olive': '#808000',
        'teal': '#14B8A6',
        'lavender': '#E6E6FA',
        'peach': '#FFDAB9',
        'mint': '#98FF98',
        'maroon': '#800000',
        'burgundy': '#800020',
        'mustard': '#FFDB58',
        'terracotta': '#E2725B',
        'rust': '#B7410E',
        'sage': '#9DC183',
        'coral': '#FF7F50',
        'turquoise': '#40E0D0',
        'violet': '#EE82EE',
        'indigo': '#4B0082',
        'magenta': '#FF00FF',
        'lime': '#32CD32',
        'aqua': '#00FFFF',
        'fuchsia': '#FF00FF',
        'teal': '#008080',
        'navy blue': '#000080',
        'royal blue': '#4169E1',
        'sky blue': '#87CEEB',
        'light blue': '#ADD8E6',
        'dark blue': '#00008B',
        'light green': '#90EE90',
        'dark green': '#006400',
        'forest green': '#228B22',
        'lime green': '#32CD32',
        'olive green': '#808000',
        'light pink': '#FFB6C1',
        'hot pink': '#FF69B4',
        'deep pink': '#FF1493',
        'light yellow': '#FFFFE0',
        'dark yellow': '#9B870C',
        'light gray': '#D3D3D3',
        'dark gray': '#A9A9A9',
        'slate gray': '#708090',
        'charcoal': '#36453F',
        'chocolate': '#D2691E',
        'coffee': '#6F4E37',
        'tan': '#D2B48C',
        'wheat': '#F5DEB3',
        'ivory': '#FFFFF0',
        'linen': '#FAF0E6',
        'plum': '#DDA0DD',
        'orchid': '#DA70D6',
        'salmon': '#FA8072',
        'tomato': '#FF6347',
        'crimson': '#DC143C',
        'scarlet': '#FF2400',
        'ruby': '#E0115F',
        'rose': '#FF007F',
        'cerise': '#DE3163',
        'fuchsia': '#FF00FF',
        'magenta': '#FF00FF',
        'byzantium': '#702963',
        'puce': '#CC8899',
        'mauve': '#E0B0FF',
        'periwinkle': '#CCCCFF',
        'lilac': '#C8A2C8',
        'amethyst': '#9966CC',
        'jasmine': '#F8DE7E',
        'apricot': '#FBCEB1',
        'peach': '#FFE5B4',
        'canteloupe': '#FFA36C',
        'tangerine': '#F28500',
        'amber': '#FFBF00',
        'honey': '#E6AB02',
        'ochre': '#CC7722',
        'bronze': '#CD7F32',
        'copper': '#B87333',
        'rose gold': '#E0BFB8',
        'champagne': '#F7E7CE',
        'pearl': '#EAE0C8',
        'opal': '#A8A3B0',
        'slate': '#6A5E78',
        'graphite': '#38342F',
        'ebony': '#555D50',
        'jet': '#343434',
        'snow': '#FFFAFA',
        'blush': '#F59E9A',
        'nude': '#E3BC9A',
        'camel': '#C19A6B',
        'taupe': '#B38B6D',
        'greige': '#B8B0A0',
        'ecru': '#C2B280',
        'buff': '#F0DC82',
        'buff': '#F0DC82',
        'sand': '#C2B280',
        'stone': '#928E85',
        'flint': '#6D6A63',
        'ash': '#B2BEB5',
        'mist': '#D4E2D4',
        'seafoam': '#9FE5B1',
        'jade': '#00A86B',
        'emerald': '#50C878',
        'peridot': '#BCE09C',
        'chartreuse': '#7FFF00',
        'spring green': '#00FF7F',
        'sea green': '#2E8B57',
        'medium sea green': '#3CB371',
        'light sea green': '#20B2AA',
        'medium aquamarine': '#66CDAA',
        'aquamarine': '#7FFFD4',
        'pale turquoise': '#AFEEEE',
        'pale green': '#98FB98',
        'honeydew': '#F0FFF0',
        'alice blue': '#F0F8FF',
        'azure': '#007FFF',
        'cobalt': '#0047AB',
        'sapphire': '#0F52BA',
        'lapis': '#26619C',
        'cerulean': '#007BA7',
        'turquoise blue': '#00FFEF',
        'cyan': '#00FFFF',
        'electric blue': '#7DF9FF',
        'powder blue': '#B0E0E6',
        'cadet blue': '#5F9EA0',
        'steel blue': '#4682B4',
        'light steel blue': '#B0C4DE',
        'cornflower': '#6495ED',
        'medium slate blue': '#7B68EE',
        'slate blue': '#6A5ACD',
        'medium blue': '#0000CD',
        'midnight blue': '#191970',
        'prussian blue': '#003153',
        'oxford blue': '#002147',
        'yale blue': '#00356B',
        'delft blue': '#003479',
        'sapphire blue': '#12467F',
        'denim': '#1560BD',
        'indigo dye': '#00416A',
        'dark indigo': '#2E1A47',
        'tyrian purple': '#66023C',
        'royal purple': '#6B3FA0',
        'blue violet': '#8A2BE2',
        'deep violet': '#330066',
        'wine': '#722F37',
        'bordeaux': '#5C0120',
        'merlot': '#831923',
        'sangria': '#92000A',
        'oxblood': '#4A0404',
        'chestnut': '#954535',
        'mahogany': '#C04000',
        'reddish brown': '#A52A2A',
        'sienna': '#A0522D',
        'umber': '#635147',
        'raw umber': '#826644',
        'burnt umber': '#8A3324',
        'raw sienna': '#D68A59',
        'burnt sienna': '#E97451',
        'terracotta': '#C0785F',
        'rust': '#8B4000',
        'vermilion': '#E34234',
        'cinnabar': '#E34234',
        'carmine': '#960018',
        'alizarin': '#E32636',
        'candy apple red': '#FF0800',
        'cherry red': '#DE3163',
        'fire engine red': '#CE1126',
        'cardinal': '#C41E3A',
        'garnet': '#733635',
        'mahogany': '#C04000',
        'auburn': '#A52A2A',
        'rusty red': '#DA2C43',
        'brick red': '#CB4154',
        'coral red': '#FF4040',
        'salmon pink': '#FF6663',
        'light salmon': '#FFA07A',
        'dark salmon': '#E9967A',
        'light coral': '#F08080',
        'indian red': '#CD5C5C',
        'pale violet red': '#DB7093',
        'medium violet red': '#C71585',
        'deep rose': '#C54F82',
        'raspberry': '#E30B5C',
        'strawberry': '#FC5A8D',
        'watermelon': '#FC6C85',
        'bubblegum': '#FFC1CC',
        'cotton candy': '#FFBCD9',
        'lavender blush': '#FFF0F5',
        'misty rose': '#FFE4E1',
        'seashell': '#FFF5EE',
        'old lace': '#FDF5E6',
        'floral white': '#FFFAF0',
        'ghost white': '#F8F8FF',
        'white smoke': '#F5F5F5',
        'gainsboro': '#DCDCDC',
        'light grey': '#D3D3D3',
        'silver': '#C0C0C0',
        'dark silver': '#A9A9A9',
        'gray': '#808080',
        'dim gray': '#696969',
        'light slate gray': '#778899',
        'dark slate gray': '#2F4F4F'
    };
    
    const normalizedColor = colorName.toLowerCase().trim();
    return colorMap[normalizedColor] || '#000000';
}
