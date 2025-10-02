-- Create database
CREATE DATABASE IF NOT EXISTS boys_shop;
USE boys_shop;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    address TEXT,
    phone VARCHAR(20),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart table (for logged-in users)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- Insert sample products
INSERT INTO products (name, description, price, image, category, stock_quantity) VALUES
('Blue Denim Jacket', 'Stylish blue denim jacket for boys, perfect for casual outings.', 45.99, 'jacket-blue.jpg', 'Outerwear', 25),
('Graphic T-Shirt', 'Cool graphic print t-shirt with superhero design.', 19.99, 'tshirt-graphic.jpg', 'Tops', 50),
('Cargo Pants', 'Comfortable cargo pants with multiple pockets.', 34.99, 'pants-cargo.jpg', 'Bottoms', 30),
('Sneakers', 'Trendy sneakers with comfortable sole for active boys.', 49.99, 'shoes-sneakers.jpg', 'Footwear', 40),
('Baseball Cap', 'Adjustable baseball cap with team logo.', 14.99, 'accessory-cap.jpg', 'Accessories', 60),
('Hooded Sweatshirt', 'Warm hooded sweatshirt for chilly days.', 39.99, 'sweatshirt-hooded.jpg', 'Outerwear', 35),
('Striped Polo Shirt', 'Classic striped polo shirt for formal occasions.', 24.99, 'shirt-polo.jpg', 'Tops', 45),
('Jeans', 'Durable denim jeans with stretch for comfort.', 29.99, 'pants-jeans.jpg', 'Bottoms', 50);

-- Create admin user (password: admin123)
INSERT INTO users (username, email, password, first_name, last_name, is_admin) VALUES
('admin', 'admin@magizhchi.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 1);