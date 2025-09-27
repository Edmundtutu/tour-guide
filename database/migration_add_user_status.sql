-- Migration: Add status and last_login columns to users table
-- Run this if you already have a users table without these columns

USE tour_guide;

-- Add status column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status ENUM('active','inactive','blocked') DEFAULT 'active' 
AFTER role;

-- Add last_login column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL 
AFTER phone;

-- Update existing users to have active status
UPDATE users SET status = 'active' WHERE status IS NULL;

-- Create some demo users with different statuses for testing
INSERT IGNORE INTO users (name, email, password, role, status, phone) VALUES
('Demo Tourist', 'tourist@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tourist', 'active', '+256700000001'),
('Demo Host', 'host@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'active', '+256700000002'),
('Demo Admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', '+256700000003'),
('Blocked User', 'blocked@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tourist', 'blocked', '+256700000004'),
('Inactive User', 'inactive@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'inactive', '+256700000005');

-- Create some demo hotels
INSERT IGNORE INTO hotels (host_id, name, description, location, price_per_night, status) VALUES
(2, 'Kampala Hotel', 'Luxury hotel in the heart of Kampala', 'Kampala, Uganda', 150000.00, 'approved'),
(2, 'Entebbe Resort', 'Beachfront resort near Entebbe Airport', 'Entebbe, Uganda', 200000.00, 'approved'),
(5, 'Jinja Lodge', 'Riverside lodge in Jinja', 'Jinja, Uganda', 100000.00, 'pending');

-- Create some demo bookings
INSERT IGNORE INTO bookings (hotel_id, tourist_id, check_in, check_out, guests, total_price, status) VALUES
(1, 1, '2024-02-01', '2024-02-03', 2, 300000.00, 'approved'),
(2, 1, '2024-02-10', '2024-02-12', 1, 400000.00, 'pending'),
(1, 4, '2024-02-15', '2024-02-17', 2, 300000.00, 'rejected');

-- Create some demo subscriptions
INSERT IGNORE INTO subscriptions (host_id, plan, amount, status, start_date, end_date) VALUES
(2, 'monthly', 50000.00, 'active', '2024-01-01', '2024-02-01'),
(5, 'annual', 500000.00, 'active', '2024-01-01', '2025-01-01');

-- Create some demo destinations
INSERT IGNORE INTO destinations (name, description, location, entry_fee) VALUES
('Murchison Falls National Park', 'Uganda\'s largest national park with spectacular waterfalls', 'Northern Uganda', 40000.00),
('Bwindi Impenetrable Forest', 'Home to mountain gorillas', 'Southwestern Uganda', 60000.00),
('Queen Elizabeth National Park', 'Diverse wildlife and scenic landscapes', 'Western Uganda', 40000.00);

SELECT 'Migration completed successfully!' as message;
