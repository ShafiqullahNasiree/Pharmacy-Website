-- Create database
CREATE DATABASE IF NOT EXISTS pharmacy_db;
USE pharmacy_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    password_changed BOOLEAN DEFAULT 0
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table (tracks products added to cart by users)
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Orders table (tracks completed purchases)
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table (tracks products in each order)
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert default admin user
INSERT INTO users (email, password, role) 
VALUES ('admin@pharmacy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Note: The password is 'password123' (hashed)

-- Insert sample products
INSERT INTO products (name, description, price, image, category, stock) VALUES
('Paracetamol 500mg', 'Pain reliever and fever reducer. Ideal for headaches, muscle aches, and fever.', 5.99, 'images/paracetamol.jpg', 'Pain Relief', 100),
('Ibuprofen 200mg', 'Anti-inflammatory pain reliever. Effective for pain and swelling.', 7.99, 'images/ibuprofen.jpg', 'Pain Relief', 80),
('Vitamin C 500mg', 'Immune support supplement. Contains 60 chewable tablets.', 12.99, 'images/vitamin_c.jpg', 'Supplements', 120),
('Flu Relief Syrup', 'Combination medicine for flu symptoms. Contains antihistamine and decongestant.', 14.99, 'images/flu_relief.jpg', 'Cold & Flu', 75),
('Antibiotic Ointment', 'Topical antibiotic cream for minor cuts and burns.', 8.99, 'images/antibiotic.jpg', 'First Aid', 90),
('Allergy Relief', 'Antihistamine tablets for seasonal allergies.', 9.99, 'images/allergy_relief.jpg', 'Allergy', 110);
