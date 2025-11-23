-- Expense Tracker System Database
-- Create Database
CREATE DATABASE IF NOT EXISTS expense_tracker;
USE expense_tracker;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50) DEFAULT '📁',
    color VARCHAR(20) DEFAULT '#6c757d'
);

-- Expenses Table
CREATE TABLE IF NOT EXISTS expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Insert Default Categories
INSERT INTO categories (name, icon, color) VALUES
('Food & Dining', '🍔', '#dc3545'),
('Transportation', '🚗', '#007bff'),
('Shopping', '🛒', '#28a745'),
('Entertainment', '🎬', '#ffc107'),
('Bills & Utilities', '💡', '#17a2b8'),
('Healthcare', '⚕️', '#e83e8c'),
('Education', '📚', '#6610f2'),
('Others', '📋', '#6c757d');

-- Create Index for Better Performance
CREATE INDEX idx_user_expenses ON expenses(user_id, expense_date);
CREATE INDEX idx_category ON expenses(category_id);
