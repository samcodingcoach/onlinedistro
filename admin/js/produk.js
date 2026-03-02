/**
 * Produk Management JavaScript
 * Handle all produk-related functionality
 */

// Global variables
let currentToken = localStorage.getItem('admin_token');
let currentAdmin = JSON.parse(localStorage.getItem('admin_data') || '{}');
let allProdukData = [];
let currentPage = 1;
const itemsPerPage = 15;
let filteredData = [];

// Loading progress functions
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    overlay.style.display = 'flex';
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
}

function updateProgress(percent) {
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    progressBar.style.width = percent + '%';
    progressText.textContent = percent + '%';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    
    // Ensure 100% before hiding
    updateProgress(100);
    
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 500);
}

// Simulate progress while loading
function simulateProgress(callback, duration = 2000) {
    let progress = 0;
    const increment = 100 / (duration / 50); // Update every 50ms
    
    const interval = setInterval(() => {
        progress += increment;
        if (progress >= 95) {
            progress = 95; // Stop at 95%, let actual completion take it to 100%
            clearInterval(interval);
        }
        updateProgress(Math.round(progress));
    }, 50);
    
    return () => {
        clearInterval(interval);
        updateProgress(100);
        callback();
    };
}

// Check authentication
if (!currentToken) {
    window.location.href = 'login.html';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Display admin name
    if (currentAdmin.username) {
        document.getElementById('adminName').textContent = currentAdmin.username;
        document.getElementById('mobileAdminName').textContent = currentAdmin.username;
    }
    
    // Show loading immediately when page loads
    showLoading();
    
    // Load initial data
    loadProduk();
    loadKategoriForFilter();
    
    // Setup form submission
    document.getElementById('produkForm').addEventListener('submit', submitProdukForm);
    
    // Setup kategori dropdown
    setupKategoriDropdown();
});

/**
 * Toggle mobile menu
 */
function toggleMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileMenuOverlay');
    
    if (mobileMenu.classList.contains('-translate-x-full')) {
        mobileMenu.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        mobileMenu.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

/**
 * Load produk data from API
 */
async function loadProduk() {
    // Start progress simulation
    const completeProgress = simulateProgress(() => {
        // This callback will be called when we want to complete the progress
    }, 2000);
    
    try {
        const response = await fetch('../api/produk/list.php');
        
        let data;
        try {
            const responseText = await response.text();
            console.log('Raw response from list.php:', responseText); // Debug log
            
            // Try to parse as JSON
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error in loadProduk:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Server returned invalid JSON format when loading products');
            }
        } catch (error) {
            if (error.message.includes('JSON')) {
                throw error;
            }
            throw new Error('Failed to read response: ' + error.message);
        }
        
        if (data.success && data.data.length > 0) {
            allProdukData = data.data;
            filteredData = [...allProdukData];
            displayProduk();
            updatePagination();
        } else {
            allProdukData = [];
            filteredData = [];
            const tbody = document.getElementById('produkTableBody');
            tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data produk</td></tr>';
            updatePagination();
        }
        
        // Complete progress and hide loading
        completeProgress();
        hideLoading();
    } catch (error) {
        // Complete progress and hide loading even on error
        completeProgress();
        hideLoading();
        showMessage('error', 'Gagal memuat data: ' + error.message);
    }
}

/**
 * Load kategori data for dropdown
 */
async function loadKategori() {
    try {
        const response = await fetch('../api/kategori/list.php');
        
        let data;
        try {
            const responseText = await response.text();
            console.log('Raw response from kategori/list.php:', responseText); // Debug log
            
            // Try to parse as JSON
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error in loadKategori:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Server returned invalid JSON format when loading categories');
            }
        } catch (error) {
            if (error.message.includes('JSON')) {
                throw error;
            }
            throw new Error('Failed to read response: ' + error.message);
        }
        
        if (data.success && data.data.length > 0) {
            const select = document.getElementById('id_kategori');
            select.innerHTML = '<option value="">Pilih Kategori</option>';
            data.data.forEach(kategori => {
                select.innerHTML += `<option value="${kategori.id_kategori}">${kategori.nama_kategori}</option>`;
            });
        }
    } catch (error) {
        console.error('Gagal memuat data kategori:', error);
    }
}

/**
 * Load kategori data for filter dropdown
 */
async function loadKategoriForFilter() {
    try {
        const response = await fetch('../api/kategori/listv2.php');
        
        let data;
        try {
            const responseText = await response.text();
            console.log('Raw response from kategori/listv2.php:', responseText); // Debug log
            
            // Try to parse as JSON
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error in loadKategoriForFilter:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Server returned invalid JSON format when loading categories for filter');
            }
        } catch (error) {
            if (error.message.includes('JSON')) {
                throw error;
            }
            throw new Error('Failed to read response: ' + error.message);
        }
        
        if (data.success && data.data.length > 0) {
            window.allKategoriData = data.data;
            populateKategoriDropdown(data.data);
        } else {
            window.allKategoriData = [];
        }
    } catch (error) {
        console.error('Gagal memuat data kategori untuk filter:', error);
        window.allKategoriData = [];
    }
}

/**
 * Populate kategori dropdown with data
 */
function populateKategoriDropdown(kategoriData) {
    const optionsContainer = document.getElementById('kategoriOptions');
    
    // Clear existing options
    optionsContainer.innerHTML = '';
    
    // Add "All Categories" option
    const allOption = document.createElement('div');
    allOption.className = 'kategori-option';
    allOption.textContent = 'Semua Kategori';
    allOption.dataset.value = '';
    allOption.dataset.id = '';
    optionsContainer.appendChild(allOption);
    
    // Add category options
    kategoriData.forEach(kategori => {
        const option = document.createElement('div');
        option.className = 'kategori-option';
        option.textContent = kategori.nama_kategori;
        option.dataset.value = kategori.nama_kategori;
        option.dataset.id = kategori.id_kategori;
        optionsContainer.appendChild(option);
    });
}

/**
 * Setup kategori dropdown functionality
 */
function setupKategoriDropdown() {
    const input = document.getElementById('kategoriInput');
    const optionsContainer = document.getElementById('kategoriOptions');
    const arrow = document.getElementById('kategoriArrow');
    
    // Variables to track selected state
    let selectedKategori = {
        id: '',
        nama: ''
    };
    
    // Toggle dropdown when clicking input
    input.addEventListener('click', function() {
        toggleDropdown();
    });
    
    // Filter options when typing and detect clear
    input.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterKategoriOptions(searchTerm);
        
        // If input is cleared, reset filter
        if (this.value.trim() === '') {
            clearKategori();
        }
        
        // Show dropdown when typing
        if (!optionsContainer.classList.contains('block')) {
            optionsContainer.classList.remove('hidden');
            optionsContainer.classList.add('block');
            arrow.classList.add('rotate-180');
        }
    });
    
    // Handle option selection
    optionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('kategori-option')) {
            selectKategori(e.target);
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('div.relative')) {
            closeDropdown();
        }
    });
    
    function toggleDropdown() {
        if (optionsContainer.classList.contains('block')) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }
    
    function openDropdown() {
        optionsContainer.classList.remove('hidden');
        optionsContainer.classList.add('block');
        arrow.classList.add('rotate-180');
        input.focus();
    }
    
    function closeDropdown() {
        optionsContainer.classList.remove('block');
        optionsContainer.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
    
    function filterKategoriOptions(searchTerm) {
        const options = optionsContainer.querySelectorAll('.kategori-option');
        
        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Check if any options are visible
        const visibleOptions = Array.from(options).filter(option => option.style.display !== 'none');
        
        // Show "No results" message if no options match
        if (visibleOptions.length === 0 && searchTerm !== '') {
            // Remove any existing "no results" message
            const existingNoResults = optionsContainer.querySelector('.no-results');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            // Add "no results" message
            const noResults = document.createElement('div');
            noResults.className = 'kategori-option no-results';
            noResults.textContent = 'Tidak ada kategori yang cocok';
            optionsContainer.appendChild(noResults);
        } else {
            // Remove "no results" message if it exists
            const existingNoResults = optionsContainer.querySelector('.no-results');
            if (existingNoResults) {
                existingNoResults.remove();
            }
        }
    }
    
    function selectKategori(option) {
        // Update input value
        input.value = option.dataset.value;
        
        // Update selected state
        selectedKategori = {
            id: option.dataset.id,
            nama: option.dataset.value
        };
        
        // Highlight selected option
        const options = optionsContainer.querySelectorAll('.kategori-option');
        options.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');
        
        // Close dropdown
        closeDropdown();
        
        // Apply filter
        filterByKategori();
    }
    
    function clearKategori() {
        // Clear input and selected state
        input.value = '';
        selectedKategori = {
            id: '',
            nama: ''
        };
        
        // Remove selected class from all options
        const options = optionsContainer.querySelectorAll('.kategori-option');
        options.forEach(opt => opt.classList.remove('selected'));
        
        // Apply filter
        filterByKategori();
    }
    
    // Expose selected kategori data globally
    window.getSelectedKategori = function() {
        return selectedKategori;
    };
}

/**
 * Format currency to Rupiah
 */
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
}

/**
 * Display produk data with pagination
 */
function displayProduk() {
    const tbody = document.getElementById('produkTableBody');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredData.slice(startIndex, endIndex);
    
    if (pageData.length > 0) {
        tbody.innerHTML = pageData.map((produk, index) => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startIndex + index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${produk.kode_produk || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 truncate max-w-xs" title="${produk.nama_produk}">${produk.nama_produk}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${produk.merk || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${produk.nama_kategori}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${produk.warna || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStockColorClass(produk.jumlah_stok)}">
                        ${produk.jumlah_stok || 0}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">${formatRupiah(produk.harga_aktif)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <button onclick="editProduk(${JSON.stringify(produk).replace(/"/g, '&quot;')})"
                            class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                    <button onclick="deleteProduk(${produk.id_produk}, '${produk.nama_produk}', '${produk.kode_produk}')"
                            class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        `).join('');
    } else {
        tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada data yang ditemukan</td></tr>';
    }
}

/**
 * Get stock color class based on quantity
 */
function getStockColorClass(stock) {
    if (!stock || stock <= 5) return 'bg-red-100 text-red-800';
    if (stock <= 10) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
}

/**
 * Update pagination controls
 */
function updatePagination() {
    const totalItems = filteredData.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);
    
    // Update text displays
    document.getElementById('dataCount').textContent = totalItems;
    document.getElementById('startItem').textContent = startItem;
    document.getElementById('endItem').textContent = endItem;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
    
    // Update button states
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;
}

/**
 * Change page
 */
function changePage(direction) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const newPage = currentPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        displayProduk();
        updatePagination();
    }
}

/**
 * Search produk data
 */
function searchProduk() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    
    // Reset to all products first
    filteredData = [...allProdukData];
    
    // Apply search filter if there's a search term
    if (searchTerm !== '') {
        filteredData = filteredData.filter(produk => 
            (produk.kode_produk && produk.kode_produk.toLowerCase().includes(searchTerm)) ||
            (produk.nama_produk && produk.nama_produk.toLowerCase().includes(searchTerm)) ||
            (produk.merk && produk.merk.toLowerCase().includes(searchTerm))
        );
    }
    
    // Apply kategori filter if one is selected
    const selectedKategori = window.getSelectedKategori();
    if (selectedKategori && selectedKategori.id !== '') {
        filteredData = filteredData.filter(produk => 
            produk.id_kategori == selectedKategori.id
        );
    }
    
    // Apply stock filter if needed
    const stockFilterValue = document.getElementById('stockFilter').value;
    if (stockFilterValue === 'most') {
        filteredData = [...filteredData].sort((a, b) => (b.jumlah_stok || 0) - (a.jumlah_stok || 0));
    } else if (stockFilterValue === 'least') {
        filteredData = [...filteredData].sort((a, b) => (a.jumlah_stok || 0) - (b.jumlah_stok || 0));
    }
    
    currentPage = 1;
    displayProduk();
    updatePagination();
}

/**
 * Filter produk by stock
 */
function filterByStock() {
    const filterValue = document.getElementById('stockFilter').value;
    
    // Reset to all products first
    filteredData = [...allProdukData];
    
    // Apply kategori filter if one is selected
    const selectedKategori = window.getSelectedKategori();
    if (selectedKategori && selectedKategori.id !== '') {
        filteredData = filteredData.filter(produk => 
            produk.id_kategori == selectedKategori.id
        );
    }
    
    // Apply search filter if there's a search term
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    if (searchTerm !== '') {
        filteredData = filteredData.filter(produk => 
            (produk.kode_produk && produk.kode_produk.toLowerCase().includes(searchTerm)) ||
            (produk.nama_produk && produk.nama_produk.toLowerCase().includes(searchTerm)) ||
            (produk.merk && produk.merk.toLowerCase().includes(searchTerm))
        );
    }
    
    // Apply stock filter
    if (filterValue === 'most') {
        filteredData = [...filteredData].sort((a, b) => (b.jumlah_stok || 0) - (a.jumlah_stok || 0));
    } else if (filterValue === 'least') {
        filteredData = [...filteredData].sort((a, b) => (a.jumlah_stok || 0) - (b.jumlah_stok || 0));
    }
    
    currentPage = 1;
    displayProduk();
    updatePagination();
}

/**
 * Filter produk by kategori
 */
function filterByKategori() {
    const selectedKategori = window.getSelectedKategori();
    
    // Reset to all products first
    filteredData = [...allProdukData];
    
    // Apply kategori filter if one is selected
    if (selectedKategori && selectedKategori.id !== '') {
        filteredData = filteredData.filter(produk => 
            produk.id_kategori == selectedKategori.id
        );
    }
    
    // Reapply search filter if there's a search term
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    if (searchTerm !== '') {
        filteredData = filteredData.filter(produk => 
            (produk.kode_produk && produk.kode_produk.toLowerCase().includes(searchTerm)) ||
            (produk.nama_produk && produk.nama_produk.toLowerCase().includes(searchTerm)) ||
            (produk.merk && produk.merk.toLowerCase().includes(searchTerm))
        );
    }
    
    // Reapply stock filter if needed
    const stockFilterValue = document.getElementById('stockFilter').value;
    if (stockFilterValue === 'most') {
        filteredData = [...filteredData].sort((a, b) => (b.jumlah_stok || 0) - (a.jumlah_stok || 0));
    } else if (stockFilterValue === 'least') {
        filteredData = [...filteredData].sort((a, b) => (a.jumlah_stok || 0) - (b.jumlah_stok || 0));
    }
    
    currentPage = 1;
    displayProduk();
    updatePagination();
}

/**
 * Format Rupiah input field
 */
function formatRupiahInput(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/\D/g, '');
    
    // Convert to number and format with thousand separator
    if (value) {
        let formatted = parseInt(value).toLocaleString('id-ID');
        input.value = formatted;
        
        // Update hidden field
        const hiddenInput = document.getElementById(input.id + '_hidden');
        if (hiddenInput) {
            hiddenInput.value = value;
        }
    } else {
        input.value = '';
        
        // Update hidden field
        const hiddenInput = document.getElementById(input.id + '_hidden');
        if (hiddenInput) {
            hiddenInput.value = '0';
        }
    }
}

/**
 * Switch tabs in modal
 */
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    // Deactivate all buttons
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'text-primary', 'border-primary');
        el.classList.add('text-gray-500', 'border-transparent');
    });
    // Show selected tab
    document.getElementById('content-' + tabId).classList.add('active');
    // Activate button
    const btn = document.getElementById('tab-' + tabId);
    btn.classList.add('active');
    btn.classList.remove('text-gray-500', 'border-transparent');
}

/**
 * Show add form modal
 */
function showAddForm() {
    document.getElementById('modalTitle').textContent = 'Tambah Produk';
    resetProdukForm();
    document.getElementById('produkModal').classList.remove('hidden');
    loadKategori();
}

/**
 * Reset form to default state
 */
function resetProdukForm() {
    document.getElementById('produkForm').reset();
    document.getElementById('id_produk').value = '';
    document.getElementById('harga_aktif_hidden').value = '0';
    document.getElementById('harga_coret_hidden').value = '0';
    document.getElementById('terjual').value = '0';
    
    // Reset image previews
    ['preview1', 'preview2', 'preview3'].forEach((previewId, index) => {
        const previewDiv = document.getElementById(previewId);
        const removeBtn = document.getElementById(`removePreview${index + 1}`);
        
        // Reset preview to default state
        previewDiv.innerHTML = `
            <span class="material-icons-round text-gray-400 text-4xl group-hover:text-primary transition-colors">cloud_upload</span>
            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                <label class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-hover focus-within:outline-none" for="gambar${index + 1}">
                    <span>Upload file</span>
                    <input class="sr-only" id="gambar${index + 1}" name="gambar${index + 1}" type="file" accept=".jpg,.jpeg,.png" onchange="previewImage(this, '${previewId}', 'removePreview${index + 1}')">
                </label>
                <p class="pl-1">atau drag & drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG up to 1MB</p>
        `;
        
        // Hide remove button
        if (removeBtn) {
            removeBtn.classList.add('hidden');
        }
    });
}

/**
 * Edit produk
 */
function editProduk(produk) {
    document.getElementById('modalTitle').textContent = 'Edit Produk';
    populateProdukForm(produk);
    document.getElementById('produkModal').classList.remove('hidden');
}

/**
 * Preview image on file selection
 */
function previewImage(input, previewId, removeBtnId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewDiv = document.getElementById(previewId);
            const removeBtn = document.getElementById(removeBtnId);
            
            // Store file info
            const file = input.files[0];
            
            // Create image preview
            previewDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="max-w-full max-h-48 mx-auto rounded-lg shadow-lg">
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium">${file.name}</p>
                    <p>Size: ${(file.size / 1024).toFixed(2)} KB</p>
                </div>
            `;
            
            // Keep the input file (IMPORTANT: don't remove it!)
            // Append it after the preview but keep it hidden
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.id = input.id;
            newInput.name = input.name;
            newInput.className = 'sr-only';
            newInput.accept = input.accept;
            newInput.files = input.files; // Copy files
            newInput.onchange = () => previewImage(newInput, previewId, removeBtnId);
            previewDiv.appendChild(newInput);
            
            // Show remove button
            removeBtn.classList.remove('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Remove image preview
 */
function removeImagePreview(inputId, previewId, removeBtnId) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    
    // Clear file input
    const fileInput = document.getElementById(inputId);
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Reset preview to default state
    const previewDiv = document.getElementById(previewId);
    if (previewDiv) {
        previewDiv.innerHTML = `
            <span class="material-icons-round text-gray-400 text-4xl group-hover:text-primary transition-colors">cloud_upload</span>
            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                <label class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-hover focus-within:outline-none" for="${inputId}">
                    <span>Upload file</span>
                    <input class="sr-only" id="${inputId}" name="${inputId}" type="file" accept=".jpg,.jpeg,.png" onchange="previewImage(this, '${previewId}', '${removeBtnId}')">
                </label>
                <p class="pl-1">atau drag & drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG up to 1MB</p>
        `;
    }
    
    // Hide remove button
    const removeBtn = document.getElementById(removeBtnId);
    if (removeBtn) {
        removeBtn.classList.add('hidden');
    }
}

/**
 * Populate form with produk data
 */
function populateProdukForm(produk) {
    // Basic fields
    document.getElementById('id_produk').value = produk.id_produk;
    document.getElementById('kode_produk').value = produk.kode_produk || '';
    document.getElementById('nama_produk').value = produk.nama_produk;
    document.getElementById('merk').value = produk.merk || '';
    document.getElementById('deskripsi').value = produk.deskripsi || '';
    document.getElementById('ukuran').value = produk.ukuran || '';
    document.getElementById('warna').value = produk.warna || '';
    document.getElementById('jumlah_stok').value = produk.jumlah_stok || 0;
    document.getElementById('terjual').value = produk.terjual || 0;

    // Price fields (hidden + formatted display)
    document.getElementById('harga_aktif_hidden').value = produk.harga_aktif;
    document.getElementById('harga_coret_hidden').value = produk.harga_coret || '';

    if (produk.harga_aktif) {
        document.getElementById('harga_aktif').value = parseInt(produk.harga_aktif).toLocaleString('id-ID');
    }
    if (produk.harga_coret) {
        document.getElementById('harga_coret').value = parseInt(produk.harga_coret).toLocaleString('id-ID');
    }

    // Checkboxes
    document.getElementById('in_stok').checked = produk.in_stok == 1;
    document.getElementById('favorit').checked = produk.favorit == 1;
    document.getElementById('aktif').checked = produk.aktif == 1;

    // Load kategori and set value
    loadKategori().then(() => {
        document.getElementById('id_kategori').value = produk.id_kategori;
    });

    // Set e-commerce links
    document.getElementById('shopee_link').value = produk.shopee_link || '';
    document.getElementById('tiktok_link').value = produk.tiktok_link || '';

    // Display existing images
    const imageFields = ['gambar1', 'gambar2', 'gambar3'];
    imageFields.forEach((field, index) => {
        const image = produk[field];
        if (image && image.trim() !== '') {
            const previewDiv = document.getElementById(`preview${index + 1}`);
            const removeBtn = document.getElementById(`removePreview${index + 1}`);

            previewDiv.innerHTML = `
                <img src="../public/images/${image}" alt="Preview" class="max-w-full max-h-48 mx-auto rounded-lg shadow-lg">
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium">${image}</p>
                </div>
            `;

            // Show remove button
            removeBtn.classList.remove('hidden');
        }
    });
}

/**
 * Delete produk
 */
function deleteProduk(id, nama, kode_produk) {
    // Show delete confirmation modal
    document.getElementById('deleteProductName').textContent = nama;
    document.getElementById('deleteProductCode').textContent = kode_produk;
    document.getElementById('confirmProductCode').value = '';
    document.getElementById('deleteError').classList.add('hidden');
    document.getElementById('confirmDeleteBtn').disabled = true;
    document.getElementById('deleteModal').classList.remove('hidden');
    
    // Store the ID globally for confirm delete
    window.deleteProductId = id;
}

/**
 * Validate delete code
 */
function validateDeleteCode() {
    const input = document.getElementById('confirmProductCode');
    const expectedCode = document.getElementById('deleteProductCode').textContent;
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    const errorMsg = document.getElementById('deleteError');
    
    if (input.value.trim() === expectedCode) {
        deleteBtn.disabled = false;
        errorMsg.classList.add('hidden');
        input.classList.remove('border-red-500');
        input.classList.add('border-green-500');
    } else if (input.value.length > 0) {
        deleteBtn.disabled = true;
        errorMsg.classList.remove('hidden');
        input.classList.add('border-red-500');
        input.classList.remove('border-green-500');
    } else {
        deleteBtn.disabled = true;
        errorMsg.classList.add('hidden');
        input.classList.remove('border-red-500', 'border-green-500');
    }
}

/**
 * Close delete modal
 */
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    window.deleteProductId = null;
}

/**
 * Confirm delete after validation
 */
async function confirmDelete() {
    const id = window.deleteProductId;
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    const originalText = deleteBtn.innerHTML;
    
    // Show loading state
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="material-icons-round animate-spin text-sm align-middle mr-1">sync</span> Menghapus...';
    
    try {
        const response = await fetch('../api/produk/delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${currentToken}`
            },
            body: JSON.stringify({
                id_produk: id
            })
        });
        
        let result;
        try {
            const responseText = await response.text();
            console.log('Raw response from delete.php:', responseText); // Debug log
            
            // Try to parse as JSON
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error in confirmDelete:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Server returned invalid JSON format when deleting product');
            }
        } catch (error) {
            if (error.message.includes('JSON')) {
                throw error;
            }
            throw new Error('Failed to read response: ' + error.message);
        }
        
        if (result.success) {
            closeDeleteModal();
            showMessage('success', result.message);
            loadProduk(); // Reload the data
        } else {
            showMessage('error', result.message);
        }
    } catch (error) {
        console.error('Error deleting produk:', error);
        showMessage('error', 'Gagal menghapus produk');
    } finally {
        // Restore button state
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    }
}

/**
 * Close modal
 */
function closeModal() {
    document.getElementById('produkModal').classList.add('hidden');
    document.getElementById('modalSuccessMessage').classList.add('hidden');
    document.getElementById('modalErrorMessage').classList.add('hidden');
    resetProdukForm();
}

/**
 * Show message (non-modal)
 */
function showMessage(type, message) {
    const messageEl = type === 'success' ? 
        document.getElementById('successMessage') : 
        document.getElementById('errorMessage');
    const textEl = type === 'success' ? 
        document.getElementById('successText') : 
        document.getElementById('errorText');
    
    textEl.textContent = message;
    messageEl.classList.remove('hidden');
    
    setTimeout(() => {
        messageEl.classList.add('hidden');
    }, 3000);
}

/**
 * Show message in modal
 */
function showModalMessage(type, message) {
    const messageEl = type === 'success' ? 
        document.getElementById('modalSuccessMessage') : 
        document.getElementById('modalErrorMessage');
    const textEl = type === 'success' ? 
        document.getElementById('modalSuccessText') : 
        document.getElementById('modalErrorText');
    
    textEl.textContent = message;
    messageEl.classList.remove('hidden');
    
    if (type === 'error') {
        setTimeout(() => {
            messageEl.classList.add('hidden');
        }, 3000);
    }
}

/**
 * Submit produk form
 */
async function submitProdukForm(e) {
    e.preventDefault();
    console.log('submitProdukForm called'); // Debug log
    
    const id_produk = document.getElementById('id_produk').value;
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading overlay
    showLoading();
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-icons-round animate-spin">sync</span> Menyimpan...';
    
    console.log('Form submission started'); // Debug log
    
    // DEBUG: Check file inputs BEFORE creating FormData
    console.log('BEFORE FormData:');
    ['gambar1', 'gambar2', 'gambar3'].forEach(field => {
        const input = document.getElementById(field);
        console.log(field, 'input:', input);
        console.log(field, 'files:', input ? input.files : 'no input');
        if (input && input.files && input.files.length > 0) {
            console.log(field, 'file:', input.files[0]);
        }
    });
    
    try {
        // Prepare form data
        const formData = new FormData();
        
        // IMPORTANT: Add ID produk first
        if (id_produk && id_produk !== '') {
            formData.append('id_produk', id_produk);
            console.log('Added id_produk to FormData at beginning:', id_produk);
        }
        
        // Add regular form fields
        const fields = ['kode_produk', 'nama_produk', 'merk', 'id_kategori', 'deskripsi', 'ukuran', 'warna', 'jumlah_stok', 'terjual', 'shopee_link', 'tiktok_link'];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                formData.append(field, element.value);
            }
        });
        
        // Handle price fields (use hidden values)
        formData.append('harga_aktif', document.getElementById('harga_aktif_hidden').value);
        formData.append('harga_coret', document.getElementById('harga_coret_hidden').value);
        
        // Handle checkboxes
        formData.append('in_stok', document.getElementById('in_stok').checked ? 1 : 0);
        formData.append('favorit', document.getElementById('favorit').checked ? 1 : 0);
        formData.append('aktif', document.getElementById('aktif').checked ? 1 : 0);
        
        // Add admin ID from current session
        formData.append('id_admin', currentAdmin.id_admin || 1);
        
        // Process and add image files with timestamp renaming
        const timestamp = Date.now();
        const imageFields = ['gambar1', 'gambar2', 'gambar3'];
        
        for (let i = 0; i < imageFields.length; i++) {
            const field = imageFields[i];
            const fileInput = document.getElementById(field);
            
            console.log(`Checking ${field}:`, fileInput);
            console.log(`${field} files:`, fileInput ? fileInput.files : 'No input');
            
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                
                console.log(`Found file for ${field}:`, file.name, file.size, file.type);
                
                // Validate file size (1MB limit)
                if (file.size > 1024 * 1024) {
                    throw new Error(`Ukuran file ${file.name} melebihi 1MB`);
                }
                
                // Validate file type
                if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                    throw new Error(`Format file ${file.name} tidak valid. Hanya JPG dan PNG yang diperbolehkan`);
                }
                
                // Create new file with timestamp name
                const extension = file.name.split('.').pop();
                const newFileName = `produk_${timestamp}_0${i + 1}.${extension}`;
                
                console.log(`Appending ${field} as ${newFileName}`);
                
                // Append file directly with new name
                formData.append(field, file, newFileName);
            } else {
                console.log(`No file found for ${field}`);
            }
        }
        
        // Debug log FormData
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(key, value.name, value.size, value.type);
            } else {
                console.log(key, value);
            }
        }
        
        // Determine if it's update or create
        const isUpdate = id_produk && id_produk !== '';
        const apiUrl = isUpdate ? '../api/produk/update.php' : '../api/produk/new.php';
        
        console.log('Submitting form - isUpdate:', isUpdate);
        console.log('Submitting form - id_produk:', id_produk);
        
        // Add old image filenames for update (to delete old images if replaced)
        if (isUpdate) {
            // Get current product data to preserve old image names
            const currentProduct = allProdukData.find(p => p.id_produk == id_produk);
            if (currentProduct) {
                formData.append('old_gambar1', currentProduct.gambar1 || '');
                formData.append('old_gambar2', currentProduct.gambar2 || '');
                formData.append('old_gambar3', currentProduct.gambar3 || '');
            }
        }
        
        // Make API call
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${currentToken}`
            },
            body: formData
        });
        
        let result;
        try {
            const responseText = await response.text();
            console.log('Raw response:', responseText); // Debug log
            
            // Try to parse as JSON
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Server returned invalid JSON format. Response: ' + responseText.substring(0, 200));
            }
        } catch (error) {
            if (error.message.includes('JSON')) {
                throw error;
            }
            throw new Error('Failed to read response: ' + error.message);
        }
        
        if (result.success) {
            hideLoading();
            const message = isUpdate ? 'Produk berhasil diupdate' : result.message;
            showModalMessage('success', message);
            setTimeout(() => {
                closeModal();
                loadProduk(); // Reload the data
            }, 1500);
        } else {
            hideLoading();
            showModalMessage('error', result.message);
        }
        
    } catch (error) {
        console.error('Error saving produk:', error);
        hideLoading();
        showModalMessage('error', error.message || 'Gagal menyimpan produk');
    } finally {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

/**
 * Logout function
 */
function logout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('admin_data');
        window.location.href = 'login.html';
    }
}