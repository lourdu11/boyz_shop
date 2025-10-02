<?php
include 'config.php';
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magizhchi Garments - Boys' Fashion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <!-- Toast Container for Notifications -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    <!-- Display Success/Error Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9998;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9998;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="bg-primary text-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center">
                    <a class="navbar-brand text-white fw-bold" href="index.php">
                        <img src="images/logo.jpg" alt="Magizhchi Garments Logo" class="navbar-logo">
                        </i>Magizhchi Garments
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <a href="https://www.instagram.com/magizhchi_garments_official" target="_blank" class="text-white me-3">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="https://wa.me/9344881275" target="_blank" class="text-white me-3">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </a>
                    <?php if($isLoggedIn): ?>
                        <span class="me-3">Welcome, <?php echo $_SESSION['username']; ?>!</span>
                        <?php if($isAdmin): ?>
                            <a href="admin.php" class="btn btn-outline-light btn-sm me-2">Admin Panel</a>
                        <?php endif; ?>
                        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
                        <a href="register.php" class="btn btn-light btn-sm">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="btn btn-outline-primary position-relative">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php
                        // Display cart item count
                        $cartCount = 0;
                        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $quantity) {
                                $cartCount += $quantity;
                            }
                        }
                        if($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cartCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>