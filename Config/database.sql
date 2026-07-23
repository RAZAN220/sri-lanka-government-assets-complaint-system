-- database.sql
CREATE DATABASE IF NOT EXISTS complaint_system;
USE complaint_system;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('citizen','district_officer','province_officer','admin') DEFAULT 'citizen',
    province_id INT NULL,
    district_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Provinces
CREATE TABLE provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Districts
CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    province_id INT NOT NULL,
    FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE
);

-- Complaint Categories
CREATE TABLE complaint_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Complaints
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    province_id INT NOT NULL,
    district_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location_lat DECIMAL(10,8) NULL,
    location_lng DECIMAL(11,8) NULL,
    address VARCHAR(255) NULL,
    status ENUM('pending','under_review','assigned','in_progress','resolved','rejected') DEFAULT 'pending',
    assigned_to INT NULL, -- district_officer user_id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES complaint_categories(id),
    FOREIGN KEY (province_id) REFERENCES provinces(id),
    FOREIGN KEY (district_id) REFERENCES districts(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- Complaint Images
CREATE TABLE complaint_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);

-- Insert default data
INSERT INTO provinces (name) VALUES 
('Western'), ('Central'), ('Southern'), ('Northern'), ('Eastern'),
('North Western'), ('North Central'), ('Uva'), ('Sabaragamuwa');

INSERT INTO districts (name, province_id) VALUES
('Colombo', 1), ('Gampaha', 1), ('Kalutara', 1),
('Kandy', 2), ('Matale', 2), ('Nuwara Eliya', 2),
('Galle', 3), ('Matara', 3), ('Hambantota', 3),
('Jaffna', 4), ('Kilinochchi', 4), ('Mannar', 4),
('Trincomalee', 5), ('Batticaloa', 5), ('Ampara', 5),
('Kurunegala', 6), ('Puttalam', 6),
('Anuradhapura', 7), ('Polonnaruwa', 7),
('Badulla', 8), ('Monaragala', 8),
('Ratnapura', 9), ('Kegalle', 9);

INSERT INTO complaint_categories (name) VALUES
('Road Damage'), ('Streetlight Issue'), ('Water Leak'), 
('Garbage Collection'), ('Bridge Damage'), ('Fallen Tree'),
('Bus Stop Damage'), ('Government Building Damage'), ('Traffic Signal');

-- Default admin (password: admin123)
INSERT INTO users (fullname, email, password, role) VALUES
('Super Admin', 'admin@system.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');