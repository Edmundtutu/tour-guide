-- Database: tour_guide
CREATE DATABASE IF NOT EXISTS tour_guide;
USE tour_guide;

-- ======================
-- USERS (Tourists, Hosts, Admins)
-- ======================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- hashed password
    role ENUM('tourist','host','admin') DEFAULT 'tourist',
    status ENUM('active','inactive','blocked') DEFAULT 'active',
    phone VARCHAR(20),
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ======================
-- DESTINATIONS (Tourist attractions)
-- ======================
CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    location VARCHAR(200),
    image_url VARCHAR(255),
    entry_fee DECIMAL(10,2) DEFAULT 0.00,
    map_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ======================
-- HOTELS / ACCOMMODATIONS (Managed by Hosts)
-- ======================
CREATE TABLE hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host_id INT NOT NULL, -- linked to users table
    name VARCHAR(150) NOT NULL,
    description TEXT,
    location VARCHAR(200),
    price_per_night DECIMAL(10,2),
    image_url VARCHAR(255),
    status ENUM('pending','approved','blocked') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================
-- ROOMS (Specific rooms inside hotels)
-- ======================
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_type VARCHAR(100), -- e.g., Single, Double, Deluxe
    price DECIMAL(10,2),
    capacity INT DEFAULT 1,
    availability_status ENUM('available','unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- ======================
-- BOOKINGS (Tourists booking hotels/rooms)
-- ======================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_id INT,
    tourist_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT DEFAULT 1,
    total_price DECIMAL(10,2),
    status ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid','paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================
-- SUBSCRIPTIONS (Hosts paying to be listed)
-- ======================
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host_id INT NOT NULL,
    plan ENUM('monthly','annual') DEFAULT 'monthly',
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('active','expired','cancelled') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================
-- OPTIONAL: REVIEWS (Tourists reviewing hotels)
-- ======================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    tourist_id INT NOT NULL,
    rating INT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================
-- OPTIONAL: EVENTS (Hosts can schedule events)
-- ======================
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    title VARCHAR(150),
    description TEXT,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);
