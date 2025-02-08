 -- Database Schema for Football Jersey Store with Login System

-- Create database
CREATE DATABASE football_store;
USE football_store;

-- Create users table for login
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    user_id INT,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- Да се поврзе со корисникот што е најавен
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- Insert sample categories
INSERT INTO categories (name) VALUES
('Men'),
('Women'),
('Kids'),
('Special Editions'),
('National Teams'), -- Додадено за дресови на национални тимови
('Retro Jerseys'), -- Додадено за ретро дресови
('Club Jerseys'); -- Додадено за клубски дресови


-- Insert sample products with new names and corrected syntax
INSERT INTO products (name, description, price, image, category_id) VALUES
('Manchester United Home Jersey', 'Official home jersey of Manchester United', 89.99, 'images/team_a_home.jpg', 1),
('Real Madrid Away Jersey', 'Official away jersey of Real Madrid', 74.99, 'images/team_b_away.jpg', 1),
('Barcelona Kids Jersey', 'Kids jersey for Barcelona', 49.99, 'images/team_c_kids.jpg', 3),
('Liverpool Home Jersey', 'Official home jersey of Liverpool', 79.99, 'images/team_d_home.jpg', 1),
('Chelsea Away Jersey', 'Official away jersey of Chelsea', 69.99, 'images/team_e_away.jpg', 1),
('Juventus Kids Jersey', 'Kids jersey for Juventus', 54.99, 'images/team_f_kids.jpg', 3),
('PSG Special Edition Jersey', 'Special edition jersey of PSG', 99.99, 'images/team_g_special.jpg', 4),
('Arsenal Retro Jersey', 'Retro jersey of Arsenal', 39.99, 'images/1.jpg', 3),
('AC Milan Anniversary Jersey', 'Special anniversary jersey of AC Milan', 59.99, 'images/arsenal3.jpg', 3),
('Belgium National Jersey', 'Jersey of the Belgium national team', 99.99, 'images/belgium.jpg', 5),
('Jamaica National Jersey', 'Jersey of the Jamaica national team', 89.99, 'images/jamaca.jpg', 3),
('Mexico National Jersey', 'Jersey of the Mexico national team', 25.99, 'images/mexiko.jpg', 2),
('Real Madrid Third Jersey', 'Third jersey of Real Madrid', 65.99, 'images/real.jpg', 3),
('Barcelona Retro Jersey', 'Retro jersey of Barcelona', 99.99, 'images/retro.jpg', 3),
('Manchester City Home Jersey', 'Official home jersey of Manchester City', 71.99, 'images/real4.jpg', 1),
('Soviet Union Retro Jersey', 'Retro jersey of the Soviet Union national team', 86.99, 'images/sssr.jpg', 3),
('Aston Villa Vintage Jersey', 'Vintage jersey of Aston Villa', 58.99, 'images/vilavila.jpg', 3),
('Real Madrid Kids Jersey', 'Kids jersey for Real Madrid', 49.99, 'images/real3.jpg', 3),
('Chelsea Vintage Jersey', 'Vintage jersey of Chelsea', 49.99, 'images/vila.jpg', 1);

-- Insert sample users with updated hashed passwords
INSERT INTO users (username, password, email) VALUES
('john_doe', '$2y$10$fbPwIiTGqePYEKoxS40rd.bRXA3Nj8z5YHMZRmY/4m4yCDL9dTBkO', 'john.doe@example.com'), -- пасворт: John_Doe123
('jane_smith', '$2a$12$1lStQ5OrWkNBd3z4DkEHfu8YZL73DieM.DHEVI3zNkE1/xERSA8yW', 'jane.smith@example.com'); --pasvord: Jane_Smith234

-- Insert sample data for orders and order_items
INSERT INTO orders (customer_name, customer_email, user_id, total_price) VALUES
('John Doe', 'john.doe@example.com', 1, 164.98),
('Jane Smith', 'jane.smith@example.com', 2, 49.99);

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 89.99),
(1, 2, 1, 74.99),
(2, 3, 1, 49.99);