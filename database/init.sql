-- Database initialization script
-- Run this after creating the database

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create brands table
CREATE TABLE IF NOT EXISTS brands (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL
);

-- Create cars table
CREATE TABLE IF NOT EXISTS cars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    brand_id INT NOT NULL,
    category_id INT NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    plate_number VARCHAR(50) NOT NULL,
    color VARCHAR(50) NOT NULL,
    mileage INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE RESTRICT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Insert sample brands
INSERT INTO brands (name) VALUES 
    ('Toyota'),
    ('Honda'),
    ('Ford'),
    ('Chevrolet'),
    ('BMW'),
    ('Mercedes-Benz'),
    ('Volkswagen'),
    ('Nissan'),
    ('Audi'),
    ('Hyundai')
ON DUPLICATE KEY UPDATE name=name;

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
    ('Sedan', 'Four-door passenger cars'),
    ('SUV', 'Sport Utility Vehicles'),
    ('Truck', 'Pickup trucks and commercial vehicles'),
    ('Coupe', 'Two-door sports cars'),
    ('Hatchback', 'Compact cars with rear hatch door'),
    ('Convertible', 'Cars with retractable roofs'),
    ('Minivan', 'Family vans with multiple rows of seating'),
    ('Electric', 'Electric-powered vehicles')
ON DUPLICATE KEY UPDATE name=name;

-- Create a demo user (password: demo123)
INSERT INTO users (name, email, password) VALUES 
    ('Demo User', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE name=name;

-- Note: The password for demo@example.com is "demo123"
