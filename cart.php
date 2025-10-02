<?php
include 'config.php';
include 'header.php';

// Handle cart actions
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if ($quantity <= 0) {
        // Remove item
        unset($_SESSION['cart'][$product_id]);
    } else {
        // Update quantity
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // Refresh page to show updated cart
    header("Location: cart.php");
    exit();
}

if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    
    // Refresh page
    header("Location: cart.php");
    exit();
}

// Get cart items
$cart_items = [];
$total_price = 0;

if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Get cart items from session
    foreach($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($product = $result->fetch_assoc()) {
                $product['quantity'] = $quantity;
                $cart_items[] = $product;
                $total_price += $product['price'] * $quantity;
            }
        }
    }
}
?>

<!-- Cart Section -->
<section class="cart-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Your Shopping Cart</h2>
        
        <?php if(empty($cart_items)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Browse our products and add items to your cart.</p>
                <a href="products.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <?php foreach($cart_items as $item): ?>
                                <div class="row align-items-center mb-4 pb-4 border-bottom">
                                    <div class="col-md-2">
                                        <img src="images/<?php echo $item['image']; ?>" 
                                             class="img-fluid rounded" 
                                             alt="<?php echo $item['name']; ?>"
                                             onerror="this.src='https://via.placeholder.com/100x100/007bff/ffffff?text=Product'">
                                    </div>
                                    <div class="col-md-4">
                                        <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="text-muted">$<?php echo $item['price']; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" max="10" style="width: 80px;">
                                            <button type="submit" name="update_cart" class="btn btn-outline-primary btn-sm ms-2">Update</button>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                    </div>
                                    <div class="col-md-1">
                                        <form method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>$5.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tax:</span>
                                <span>$<?php echo number_format($total_price * 0.08, 2); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>$<?php echo number_format($total_price + 5 + ($total_price * 0.08), 2); ?></strong>
                            </div>
                            
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <p class="mb-2">Please <a href="login.php">login</a> to proceed with checkout.</p>
                                    <a href="login.php" class="btn btn-primary w-100">Login to Checkout</a>
                                </div>
                            <?php endif; ?>
                            
                            <a href="products.php" class="btn btn-outline-primary w-100 mt-2">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>