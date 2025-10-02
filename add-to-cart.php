<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Validate product exists
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }
    
    $product = $result->fetch_assoc();
    
    // Initialize cart session if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add item to cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    $_SESSION['success'] = "Product added to cart successfully!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header("Location: products.php");
    exit();
}
?>