CREATE DATABASE IF NOT EXISTS cake_shop;
USE cake_shop;

-- Admins Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cakes Table
CREATE TABLE cakes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    flavor VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    weight VARCHAR(20) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default-cake.png',
    description TEXT,
    availability BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    delivery_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
    order_status ENUM('Pending', 'Confirmed', 'Preparing', 'Ready', 'Completed', 'Cancelled') DEFAULT 'Pending',
    custom_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    cake_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (cake_id) REFERENCES cakes(id) ON DELETE CASCADE
);
-- Insert customers table into schema.sql for future setups
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
