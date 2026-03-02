<?php
// Get category filter from URL
$category_filter = isset($_GET['kategori']) ? $_GET['kategori'] : null;

// Fetch product data using the helper function (works on both Linux and Windows)
require_once __DIR__ . '/../config/api_helper.php';

$product_api_file = __DIR__ . '/../api/produk/list.php';
$products = [];

if (file_exists($product_api_file)) {
    $data = @fetchApiData($product_api_file);
    if (isset($data['success']) && $data['success'] === true && isset($data['data'])) {
        $all_products = $data['data'];

        // Apply category filter if provided
        if ($category_filter) {
            foreach ($all_products as $product) {
                if ($product['nama_kategori'] &&
                    strtolower($product['nama_kategori']) === strtolower($category_filter)) {
                    $products[] = $product;
                }
            }
        } else {
            $products = $all_products;
        }
    }
}

// Pagination setup
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($products);
$total_pages = ceil($total_items / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

// Get products for current page
$current_products = array_slice($products, $offset, $items_per_page);
?>

<!-- Product Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8 md:gap-x-6 md:gap-y-10">
    <?php if (!empty($current_products)): ?>
        <?php foreach ($current_products as $product): ?>
            <div class="group flex flex-col gap-3 cursor-pointer" onclick="openProductModal(<?php echo $product['id_produk']; ?>)">
                <div class="relative overflow-hidden rounded-xl">
                    <?php if ($product['terjual'] > 0): ?>
                        <div class="absolute bottom-2 left-2 z-10 rounded-lg bg-black/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            <?php echo number_format($product['terjual']); ?> sold
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['jumlah_stok'] > 0): ?>
                        <div class="absolute bottom-2 right-2 z-10 rounded-lg bg-green-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            In Stock
                        </div>
                    <?php else: ?>
                        <div class="absolute bottom-2 right-2 z-10 rounded-lg bg-red-600/50 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            Out of Stock
                        </div>
                    <?php endif; ?>

                    <div class="absolute inset-0 aspect-[3/4] bg-cover bg-center transition-opacity duration-500 group-hover:opacity-0 bg-gray-200"
                         style="background-image: url('images/<?php echo htmlspecialchars($product['gambar1']); ?>');">
                    </div>

                    <div class="aspect-[3/4] bg-cover bg-center opacity-0 transition-opacity duration-500 group-hover:opacity-100 bg-gray-200"
                         style="background-image: url('images/<?php echo htmlspecialchars($product['gambar2']); ?>');">
                    </div>
                </div>

                <div>
                    <p class="text-base font-medium leading-normal">
                        <?php echo htmlspecialchars($product['nama_produk']); ?>
                    </p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-normal text-primary">
                            Rp <?php echo number_format($product['harga_aktif'], 0, ',', '.'); ?>
                        </p>
                        <?php if ($product['harga_coret'] > 0): ?>
                            <p class="text-sm font-normal line-through text-secondary-light dark:text-secondary-dark">
                                Rp <?php echo number_format($product['harga_coret'], 0, ',', '.'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-12">
            <p class="text-secondary-light dark:text-secondary-dark">No products found.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div class="flex justify-center mt-12">
        <nav class="flex items-center gap-2">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>" class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light dark:text-secondary-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
            <?php else: ?>
                <button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
            <?php endif; ?>

            <?php
            // Show page numbers with ellipsis
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if ($start_page > 1) {
                echo '<a href="?page=1" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">1</a>';
                if ($start_page > 2) {
                    echo '<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>';
                }
            }
            
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <button class="h-10 w-10 flex items-center justify-center rounded-full bg-primary text-white font-medium">
                        <?php echo $i; ?>
                    </button>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; 
            
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<span class="px-2 text-secondary-light dark:text-secondary-dark">...</span>';
                }
                echo '<a href="?page=' . $total_pages . '" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors font-medium">' . $total_pages . '</a>';
            }
            ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>" class="h-10 w-10 flex items-center justify-center rounded-full text-foreground-light dark:text-foreground-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            <?php else: ?>
                <button class="h-10 w-10 flex items-center justify-center rounded-full text-secondary-light/50 dark:text-secondary-dark/50" disabled>
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            <?php endif; ?>
        </nav>
    </div>
<?php endif; ?>
