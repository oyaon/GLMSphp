-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'librarian', 'member') NOT NULL DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(13) UNIQUE NOT NULL,
    category VARCHAR(50) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    available_quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Special offers table
CREATE TABLE IF NOT EXISTS special_offers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    discount_percent INT NOT NULL,
    coupon_code VARCHAR(20) UNIQUE NOT NULL,
    usage_limit INT,
    usage_count INT DEFAULT 0,
    min_books INT DEFAULT 1,
    category_restriction VARCHAR(50),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Borrowings table
CREATE TABLE IF NOT EXISTS borrowings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    status ENUM('borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'borrowed',
    coupon_code VARCHAR(20),
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (coupon_code) REFERENCES special_offers(coupon_code)
);

-- Insert admin user
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewYpR1IOBYyGqKHy', 'admin@library.com', 'admin');

-- Insert sample books
INSERT INTO books (title, author, isbn, category, quantity, available_quantity, price) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 'Fiction', 5, 5, 19.99),
('To Kill a Mockingbird', 'Harper Lee', '9780446310789', 'Fiction', 3, 3, 15.99),
('1984', 'George Orwell', '9780451524935', 'Fiction', 4, 4, 14.99),
('The Art of War', 'Sun Tzu', '9780140439199', 'Non-Fiction', 2, 2, 12.99),
('A Brief History of Time', 'Stephen Hawking', '9780553380163', 'Non-Fiction', 3, 3, 18.99);

-- Insert sample special offers
INSERT INTO special_offers (title, description, discount_percent, coupon_code, usage_limit, min_books, category_restriction, start_date, end_date, is_active) VALUES
('Summer Reading Sale', 'Get 20% off on all fiction books', 20, 'SUMMER20', 100, 1, 'Fiction', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
('Non-Fiction Special', '15% off on all non-fiction books', 15, 'NONFIC15', 50, 2, 'Non-Fiction', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), TRUE); 