<?php
include 'config.php';
include 'header.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Handle product actions
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];
    
    // Handle image upload
    $image = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully
        }
    }
    
    $sql = "INSERT INTO products (name, description, price, image, category, stock_quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $name, $description, $price, $image, $category, $stock_quantity);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add product.";
    }
}

if (isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];
    
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, stock_quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $name, $description, $price, $category, $stock_quantity, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product.";
    }
}

if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete product.";
    }
}

// Fetch all products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- Admin Panel -->
<section class="admin-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Admin Panel</h2>
        
        <!-- Display messages -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add New Product</h5>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Tops">Tops</option>
                                    <option value="Outerwear">Outerwear</option>
                                    <option value="Bottoms">Bottoms</option>
                                    <option value="Footwear">Footwear</option>
                                    <option value="Accessories">Accessories</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                            <button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product List</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($product = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td>
                                                <img src="images/<?php echo $product['image']; ?>" 
                                                     alt="<?php echo $product['name']; ?>" 
                                                     width="50" height="50" 
                                                     class="rounded"
                                                     onerror="this.src='https://via.placeholder.com/50x50/007bff/ffffff?text=Image'">
                                            </td>
                                            <td><?php echo $product['name']; ?></td>
                                            <td>$<?php echo $product['price']; ?></td>
                                            <td><?php echo $product['category']; ?></td>
                                            <td><?php echo $product['stock_quantity']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="admin.php?delete_product=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        
                                        <!-- Edit Product Modal -->
                                        <div class="modal fade" id="editProductModal<?php echo $product['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                            <div class="mb-3">
                                                                <label for="name<?php echo $product['id']; ?>" class="form-label">Product Name</label>
                                                                <input type="text" class="form-control" id="name<?php echo $product['id']; ?>" name="name" value="<?php echo $product['name']; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description<?php echo $product['id']; ?>" class="form-label">Description</label>
                                                                <textarea class="form-control" id="description<?php echo $product['id']; ?>" name="description" rows="3" required><?php echo $product['description']; ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="price<?php echo $product['id']; ?>" class="form-label">Price</label>
                                                                <input type="number" step="0.01" class="form-control" id="price<?php echo $product['id']; ?>" name="price" value="<?php echo $product['price']; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="category<?php echo $product['id']; ?>" class="form-label">Category</label>
                                                                <select class="form-select" id="category<?php echo $product['id']; ?>" name="category" required>
                                                                    <option value="Tops" <?php echo $product['category'] == 'Tops' ? 'selected' : ''; ?>>Tops</option>
                                                                    <option value="Outerwear" <?php echo $product['category'] == 'Outerwear' ? 'selected' : ''; ?>>Outerwear</option>
                                                                    <option value="Bottoms" <?php echo $product['category'] == 'Bottoms' ? 'selected' : ''; ?>>Bottoms</option>
                                                                    <option value="Footwear" <?php echo $product['category'] == 'Footwear' ? 'selected' : ''; ?>>Footwear</option>
                                                                    <option value="Accessories" <?php echo $product['category'] == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="stock_quantity<?php echo $product['id']; ?>" class="form-label">Stock Quantity</label>
                                                                <input type="number" class="form-control" id="stock_quantity<?php echo $product['id']; ?>" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Stats -->
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>Total Products</h5>
                                <?php
                                $sql = "SELECT COUNT(*) as total FROM products";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo '<h3>' . $row['total'] . '</h3>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>Total Users</h5>
                                <?php
                                $sql = "SELECT COUNT(*) as total FROM users";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo '<h3>' . $row['total'] . '</h3>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>Total Orders</h5>
                                <?php
                                $sql = "SELECT COUNT(*) as total FROM orders";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo '<h3>' . $row['total'] . '</h3>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>