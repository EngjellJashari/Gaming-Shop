-- Create database if not exists
CREATE DATABASE IF NOT EXISTS project;

-- Use the database
USE project;

-- Create accounts table
CREATE TABLE IF NOT EXISTS accounts (
    aid INT AUTO_INCREMENT PRIMARY KEY,
    afname VARCHAR(50) NOT NULL,
    alname VARCHAR(50) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    cnic VARCHAR(20),
    dob DATE,
    username VARCHAR(50) UNIQUE NOT NULL,
    gender VARCHAR(10),
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    remember_token VARCHAR(255)
);

-- Alter table to add role if not exists (for existing databases)
ALTER TABLE accounts ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user';
ALTER TABLE accounts ADD COLUMN IF NOT EXISTS remember_token VARCHAR(255);

-- Insert default admin user if not exists
INSERT IGNORE INTO accounts (afname, alname, username, password, role) VALUES ('Admin', 'User', 'admin1', 'adminpass', 'admin');

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    pid INT AUTO_INCREMENT PRIMARY KEY,
    pname VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    qtyavail INT NOT NULL,
    img VARCHAR(255),
    brand VARCHAR(50),
    created_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES accounts(aid)
);
ALTER TABLE products ADD COLUMN IF NOT EXISTS created_by INT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS created_at DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    aid INT NOT NULL,
    pid INT NOT NULL,
    cqty INT NOT NULL,
    PRIMARY KEY (aid, pid),
    FOREIGN KEY (aid) REFERENCES accounts(aid),
    FOREIGN KEY (pid) REFERENCES products(pid)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    oid INT AUTO_INCREMENT PRIMARY KEY,
    dateod DATE NOT NULL,
    datedel DATE,
    aid INT NOT NULL,
    address TEXT,
    city VARCHAR(50),
    country VARCHAR(50),
    account VARCHAR(50),
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (aid) REFERENCES accounts(aid)
);

-- Create order-details table
CREATE TABLE IF NOT EXISTS `order-details` (
    oid INT NOT NULL,
    pid INT NOT NULL,
    qty INT NOT NULL,
    PRIMARY KEY (oid, pid),
    FOREIGN KEY (oid) REFERENCES orders(oid),
    FOREIGN KEY (pid) REFERENCES products(pid)
);

-- Create wishlist table
CREATE TABLE IF NOT EXISTS wishlist (
    aid INT NOT NULL,
    pid INT NOT NULL,
    PRIMARY KEY (aid, pid),
    FOREIGN KEY (aid) REFERENCES accounts(aid),
    FOREIGN KEY (pid) REFERENCES products(pid)
);

-- Create reviews table
CREATE TABLE IF NOT EXISTS reviews (
    rid INT AUTO_INCREMENT PRIMARY KEY,
    pid INT NOT NULL,
    aid INT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pid) REFERENCES products(pid) ON DELETE CASCADE,
    FOREIGN KEY (aid) REFERENCES accounts(aid) ON DELETE SET NULL
);

-- Create contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aid INT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aid) REFERENCES accounts(aid)
);