-- Create database
CREATE DATABASE IF NOT EXISTS blog_db;
USE blog_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

INSERT INTO posts (title, content, category) VALUES 
('Getting Started with Web Development', 'Web development is a rapidly growing field with many opportunities. In this post, we will explore the basics of getting started with web development, including HTML, CSS, and JavaScript fundamentals.', 'Technology'),
('The Future of Artificial Intelligence', 'Artificial Intelligence is transforming industries across the globe. From healthcare to finance, AI is revolutionizing how we work and live. In this article, we discuss the latest trends and future predictions.', 'Technology'),
('Minimalist Design Principles', 'Minimalism in design focuses on simplicity and functionality. By removing unnecessary elements, we can create more effective and aesthetically pleasing designs. This post explores key principles of minimalist design.', 'Design');