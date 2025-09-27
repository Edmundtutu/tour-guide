-- Migration: Add latitude and longitude coordinates to hotels and destinations
-- This enables map integration with Leaflet

USE tour_guide;

-- Add coordinates to destinations table
ALTER TABLE destinations 
ADD COLUMN latitude DECIMAL(10, 8) NULL,
ADD COLUMN longitude DECIMAL(11, 8) NULL;

-- Add coordinates to hotels table  
ALTER TABLE hotels 
ADD COLUMN latitude DECIMAL(10, 8) NULL,
ADD COLUMN longitude DECIMAL(11, 8) NULL;

-- Add some sample coordinates for Uganda locations
-- Update destinations with real Uganda coordinates
UPDATE destinations SET 
    latitude = 0.3476, 
    longitude = 32.5825,
    location = 'Kampala, Uganda'
WHERE name LIKE '%Kampala%' OR location LIKE '%Kampala%';

UPDATE destinations SET 
    latitude = 2.3333, 
    longitude = 32.5000,
    location = 'Murchison Falls National Park, Uganda'
WHERE name LIKE '%Murchison%' OR location LIKE '%Murchison%';

UPDATE destinations SET 
    latitude = -1.2833, 
    longitude = 29.7167,
    location = 'Bwindi Impenetrable Forest, Uganda'
WHERE name LIKE '%Bwindi%' OR location LIKE '%Bwindi%';

UPDATE destinations SET 
    latitude = -0.1833, 
    longitude = 30.0833,
    location = 'Queen Elizabeth National Park, Uganda'
WHERE name LIKE '%Queen Elizabeth%' OR location LIKE '%Queen Elizabeth%';

-- Update hotels with sample coordinates in Kampala area
UPDATE hotels SET 
    latitude = 0.3476, 
    longitude = 32.5825,
    location = 'Kampala City Center, Uganda'
WHERE location LIKE '%Kampala%' OR name LIKE '%Kampala%';

UPDATE hotels SET 
    latitude = 0.3206, 
    longitude = 32.5811,
    location = 'Entebbe, Uganda'
WHERE location LIKE '%Entebbe%' OR name LIKE '%Entebbe%';

UPDATE hotels SET 
    latitude = 0.3136, 
    longitude = 32.5811,
    location = 'Jinja, Uganda'
WHERE location LIKE '%Jinja%' OR name LIKE '%Jinja%';

-- Add index for better performance on coordinate queries
CREATE INDEX idx_destinations_coords ON destinations(latitude, longitude);
CREATE INDEX idx_hotels_coords ON hotels(latitude, longitude);
