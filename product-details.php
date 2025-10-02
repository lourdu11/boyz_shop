<?php
include 'config.php';
include 'header.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();
?>

<!-- Product Details Section -->
<section class="product-details py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php echo $product['image']; ?>" 
                     class="img-fluid rounded" 
                     alt="<?php echo $product['name']; ?>"
                     onerror="this.src='https://via.placeholder.com/500x600/007bff/ffffff?text=Product+Image'">
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item active"><?php echo $product['name']; ?></li>
                    </ol>
                </nav>
                
                <h1 class="display-5 fw-bold"><?php echo $product['name']; ?></h1>
                <p class="text-muted">Category: <?php echo $product['category']; ?></p>
                <h3 class="text-primary mb-4">$<?php echo $product['price']; ?></h3>
                
                <p class="lead"><?php echo $product['description']; ?></p>
                
                <div class="mb-4">
                    <h5>Product Details:</h5>
                    <ul>
                        <li>High-quality materials</li>
                        <li>Comfortable fit</li>
                        <li>Durable construction</li>
                        <li>Easy to care for</li>
                    </ul>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="10" style="width: 80px;">
                    </div>
                    <div class="stock-status">
                        <?php if($product['stock_quantity'] > 0): ?>
                            <span class="badge bg-success">In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <form method="POST" action="add-to-cart.php" class="me-2 flex-grow-1">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" id="quantity-input" value="1">
                        <button type="submit" class="btn btn-primary btn-lg w-100" <?php echo $product['stock_quantity'] == 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </form>
                    <a href="products.php" class="btn btn-outline-primary btn-lg">Continue Shopping</a>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Related Products</h3>
                <div class="row">
                    <?php
                    $sql_related = "SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4";
                    $stmt_related = $conn->prepare($sql_related);
                    $stmt_related->bind_param("si", $product['category'], $product_id);
                    $stmt_related->execute();
                    $result_related = $stmt_related->get_result();
                    
                    if ($result_related->num_rows > 0) {
                        while($related = $result_related->fetch_assoc()) {
                            echo '
                            <div class="col-md-3 mb-4">
                                <div class="card product-card h-100">
                                    <img src="images/' . $related['image'] . '" 
                                         class="card-img-top" 
                                         alt="' . $related['name'] . '"
                                         style="height: 200px; object-fit: cover;"
                                         onerror="this.src=\'https://via.placeholder.com/300x400/007bff/ffffff?text=Product+Image\'">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">' . $related['name'] . '</h5>
                                        <p class="card-text flex-grow-1">' . substr($related['description'], 0, 80) . '...</p>
                                        <div class="mt-auto">
                                            <p class="card-text fw-bold text-primary">$' . $related['price'] . '</p>
                                            <a href="product-details.php?id=' . $related['id'] . '" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<div class="col-12"><p class="text-center">No related products found.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Update quantity input when user changes the quantity
document.getElementById('quantity').addEventListener('change', function() {
    document.getElementById('quantity-input').value = this.value;
});
</script>

<?php include 'footer.php'; ?>