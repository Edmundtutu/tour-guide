-- Demo Data with Real Uganda Coordinates
-- This script populates the database with realistic Uganda tourism data

USE tour_guide;

-- Clear existing data (in correct order due to foreign keys)
DELETE FROM reviews;
DELETE FROM events;
DELETE FROM bookings;
DELETE FROM rooms;
DELETE FROM subscriptions;
DELETE FROM hotels;
DELETE FROM destinations;
DELETE FROM users;

-- Reset auto increment
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE destinations AUTO_INCREMENT = 1;
ALTER TABLE hotels AUTO_INCREMENT = 1;
ALTER TABLE rooms AUTO_INCREMENT = 1;
ALTER TABLE bookings AUTO_INCREMENT = 1;
ALTER TABLE subscriptions AUTO_INCREMENT = 1;
ALTER TABLE reviews AUTO_INCREMENT = 1;
ALTER TABLE events AUTO_INCREMENT = 1;

-- ======================
-- USERS (Demo Users)
-- ======================
INSERT INTO users (name, email, password, role, status, phone) VALUES
-- Admin
('Admin User', 'admin@tourguide.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', '+256700000001'),

-- Tourists
('John Tourist', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tourist', 'active', '+256700000002'),
('Sarah Explorer', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tourist', 'active', '+256700000003'),
('Mike Adventurer', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tourist', 'active', '+256700000004'),

-- Hosts
('Kampala Hotel Owner', 'host1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'active', '+256700000005'),
('Entebbe Resort Manager', 'host2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'active', '+256700000006'),
('Jinja Lodge Owner', 'host3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'active', '+256700000007'),
('Murchison Safari Lodge', 'host4@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'host', 'active', '+256700000008');

-- ======================
-- DESTINATIONS (Real Uganda Tourist Attractions)
-- ======================
INSERT INTO destinations (name, description, location, latitude, longitude, image_url, entry_fee) VALUES
('Murchison Falls National Park', 'Uganda\'s largest national park, famous for the dramatic Murchison Falls where the Nile River forces its way through a narrow gorge.', 'Murchison Falls National Park, Uganda', 2.3333, 32.5000, 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop', 45.00),

('Bwindi Impenetrable Forest', 'Home to half of the world\'s remaining mountain gorillas. A UNESCO World Heritage Site offering incredible gorilla trekking experiences.', 'Bwindi Impenetrable Forest, Uganda', -1.2833, 29.7167, 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop', 700.00),

('Queen Elizabeth National Park', 'Diverse wildlife including tree-climbing lions, elephants, hippos, and over 600 bird species in a beautiful savanna landscape.', 'Queen Elizabeth National Park, Uganda', -0.1833, 30.0833, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800&h=600&fit=crop', 40.00),

('Kibale National Park', 'The primate capital of the world, home to 13 species of primates including chimpanzees. Perfect for chimpanzee tracking.', 'Kibale National Park, Uganda', 0.4500, 30.4000, 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop', 50.00),

('Lake Bunyonyi', 'The deepest lake in Uganda, surrounded by terraced hills and 29 islands. Known for its stunning beauty and peaceful atmosphere.', 'Lake Bunyonyi, Uganda', -1.2667, 29.9167, 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop', 0.00),

('Jinja - Source of the Nile', 'Where the Nile River begins its journey to the Mediterranean. Adventure capital with white-water rafting and bungee jumping.', 'Jinja, Uganda', 0.4244, 33.2042, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop', 10.00),

('Kampala City', 'Uganda\'s vibrant capital city with rich history, cultural sites, and modern amenities. Visit Kasubi Tombs, Uganda Museum, and more.', 'Kampala, Uganda', 0.3476, 32.5825, 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800&h=600&fit=crop', 0.00),

('Sipi Falls', 'Three beautiful waterfalls in the foothills of Mount Elgon. Perfect for hiking, coffee tours, and enjoying stunning views.', 'Sipi Falls, Uganda', 1.3667, 34.3667, 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop', 15.00);

-- ======================
-- HOTELS (Realistic Uganda Hotels with Coordinates)
-- ======================
INSERT INTO hotels (host_id, name, description, location, latitude, longitude, price_per_night, image_url, status) VALUES
-- Kampala Hotels
(5, 'Kampala Serena Hotel', 'Luxury hotel in the heart of Kampala with world-class amenities, fine dining, and business facilities.', 'Kampala City Center, Uganda', 0.3476, 32.5825, 250.00, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop', 'approved'),

(5, 'Speke Resort Munyonyo', 'Lakeside resort with stunning views of Lake Victoria, multiple restaurants, and recreational facilities.', 'Munyonyo, Kampala, Uganda', 0.3000, 32.6500, 180.00, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop', 'approved'),

(5, 'Hotel Africana', 'Modern business hotel in Kampala with conference facilities, restaurant, and comfortable rooms.', 'Kampala City Center, Uganda', 0.3500, 32.5800, 120.00, 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&h=600&fit=crop', 'approved'),

-- Entebbe Hotels
(6, 'Lake Victoria Serena Resort', 'Luxury resort on the shores of Lake Victoria with beautiful gardens, spa, and water activities.', 'Entebbe, Uganda', 0.3206, 32.5811, 300.00, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop', 'approved'),

(6, 'Airport Guesthouse Entebbe', 'Convenient accommodation near Entebbe Airport with comfortable rooms and airport shuttle service.', 'Entebbe, Uganda', 0.3100, 32.5700, 80.00, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop', 'approved'),

-- Jinja Hotels
(7, 'Jinja Nile Resort', 'Riverside resort at the source of the Nile with adventure activities and beautiful river views.', 'Jinja, Uganda', 0.4244, 33.2042, 150.00, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop', 'approved'),

(7, 'Wild Waters Lodge', 'Luxury eco-lodge on an island in the Nile with stunning views and adventure activities.', 'Jinja, Uganda', 0.4200, 33.2000, 200.00, 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop', 'approved'),

-- Murchison Falls Hotels
(8, 'Paraa Safari Lodge', 'Luxury lodge in Murchison Falls National Park with game drives and river cruises.', 'Murchison Falls National Park, Uganda', 2.3333, 32.5000, 400.00, 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop', 'approved'),

(8, 'Red Chilli Rest Camp', 'Budget-friendly accommodation near Murchison Falls with camping and basic rooms.', 'Murchison Falls National Park, Uganda', 2.3000, 32.4800, 60.00, 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop', 'approved'),

-- Queen Elizabeth National Park
(8, 'Mweya Safari Lodge', 'Luxury lodge in Queen Elizabeth National Park with stunning views and wildlife experiences.', 'Queen Elizabeth National Park, Uganda', -0.1833, 30.0833, 350.00, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800&h=600&fit=crop', 'approved');

-- ======================
-- ROOMS (Hotel Rooms)
-- ======================
INSERT INTO rooms (hotel_id, room_type, price, capacity, availability_status) VALUES
-- Kampala Serena Hotel
(1, 'Deluxe Room', 250.00, 2, 'available'),
(1, 'Executive Suite', 400.00, 2, 'available'),
(1, 'Presidential Suite', 800.00, 4, 'available'),

-- Speke Resort Munyonyo
(2, 'Standard Room', 180.00, 2, 'available'),
(2, 'Lake View Room', 220.00, 2, 'available'),
(2, 'Family Room', 280.00, 4, 'available'),

-- Hotel Africana
(3, 'Standard Room', 120.00, 2, 'available'),
(3, 'Deluxe Room', 150.00, 2, 'available'),

-- Lake Victoria Serena Resort
(4, 'Garden View Room', 300.00, 2, 'available'),
(4, 'Lake View Room', 350.00, 2, 'available'),
(4, 'Presidential Suite', 600.00, 4, 'available'),

-- Airport Guesthouse Entebbe
(5, 'Standard Room', 80.00, 2, 'available'),
(5, 'Family Room', 120.00, 4, 'available'),

-- Jinja Nile Resort
(6, 'Standard Room', 150.00, 2, 'available'),
(6, 'Nile View Room', 180.00, 2, 'available'),

-- Wild Waters Lodge
(7, 'Standard Room', 200.00, 2, 'available'),
(7, 'Luxury Tent', 250.00, 2, 'available'),

-- Paraa Safari Lodge
(8, 'Standard Room', 400.00, 2, 'available'),
(8, 'Luxury Suite', 600.00, 2, 'available'),

-- Red Chilli Rest Camp
(9, 'Tent', 60.00, 2, 'available'),
(9, 'Basic Room', 80.00, 2, 'available'),

-- Mweya Safari Lodge
(10, 'Standard Room', 350.00, 2, 'available'),
(10, 'Luxury Suite', 500.00, 2, 'available');

-- ======================
-- SUBSCRIPTIONS (Host Subscriptions)
-- ======================
INSERT INTO subscriptions (host_id, plan, amount, status, start_date, end_date) VALUES
(5, 'monthly', 50.00, 'active', '2024-01-01', '2024-12-31'),
(6, 'annual', 500.00, 'active', '2024-01-01', '2024-12-31'),
(7, 'monthly', 50.00, 'active', '2024-01-01', '2024-12-31'),
(8, 'annual', 500.00, 'active', '2024-01-01', '2024-12-31');

-- ======================
-- BOOKINGS (Sample Bookings)
-- ======================
INSERT INTO bookings (hotel_id, room_id, tourist_id, check_in, check_out, guests, total_price, status, payment_status) VALUES
(1, 1, 2, '2024-03-15', '2024-03-18', 2, 750.00, 'approved', 'paid'),
(4, 9, 3, '2024-03-20', '2024-03-22', 2, 600.00, 'pending', 'unpaid'),
(8, 15, 4, '2024-04-01', '2024-04-05', 2, 1600.00, 'approved', 'paid'),
(6, 13, 2, '2024-04-10', '2024-04-12', 2, 300.00, 'pending', 'unpaid');

-- ======================
-- REVIEWS (Sample Reviews)
-- ======================
INSERT INTO reviews (hotel_id, tourist_id, rating, comment) VALUES
(1, 2, 5, 'Excellent service and beautiful location in the heart of Kampala!'),
(4, 3, 4, 'Great resort with amazing lake views. Highly recommended!'),
(8, 4, 5, 'Incredible safari experience. The lodge was perfect for our wildlife adventure.');

-- ======================
-- EVENTS (Sample Events)
-- ======================
INSERT INTO events (hotel_id, title, description, start_date, end_date) VALUES
(1, 'Cultural Night', 'Traditional Ugandan music and dance performance with local cuisine.', '2024-03-25', '2024-03-25'),
(4, 'Sunset Cruise', 'Evening boat cruise on Lake Victoria with dinner and drinks.', '2024-03-30', '2024-03-30'),
(8, 'Game Drive Safari', 'Early morning game drive to see the Big Five in Murchison Falls.', '2024-04-02', '2024-04-02');
