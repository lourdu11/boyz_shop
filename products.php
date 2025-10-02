<?php
include 'config.php';
include 'header.php';

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if (!empty($category) && $category != 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

// Prepare and execute
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!-- Products Section -->
<section class="products-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Products</h2>
        
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" class="d-flex">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?php echo $category == 'all' ? 'selected' : ''; ?>>All Categories</option>
                        <option value="Tops" <?php echo $category == 'Tops' ? 'selected' : ''; ?>>Tops</option>
                        <option value="Outerwear" <?php echo $category == 'Outerwear' ? 'selected' : ''; ?>>Outerwear</option>
                        <option value="Bottoms" <?php echo $category == 'Bottoms' ? 'selected' : ''; ?>>Bottoms</option>
                        <option value="Footwear" <?php echo $category == 'Footwear' ? 'selected' : ''; ?>>Footwear</option>
                        <option value="Accessories" <?php echo $category == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card h-100">
                            <img src="images/' . $row['image'] . '" 
                                 class="card-img-top" 
                                 alt="' . $row['name'] . '"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.src=\'https://via.placeholder.com/300x400/007bff/ffffff?text=Product+Image\'">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">' . $row['name'] . '</h5>
                                <p class="card-text flex-grow-1">' . substr($row['description'], 0, 100) . '...</p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold text-primary">$' . $row['price'] . '</p>
                                    <div class="d-grid gap-2">
                                        <a href="product-details.php?id=' . $row['id'] . '" class="btn btn-primary">View Details</a>
                                        <form method="POST" action="add-to-cart.php">
                                            <input type="hidden" name="product_id" value="' . $row['id'] . '">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-primary w-100">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-center">No products found matching your criteria.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>