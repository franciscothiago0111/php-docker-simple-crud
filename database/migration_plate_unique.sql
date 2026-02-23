-- Migration: Add UNIQUE constraint to plate_number column
-- Run this if you already have the cars table created

-- Add unique constraint to plate_number
ALTER TABLE cars 
ADD UNIQUE KEY unique_plate_number (plate_number);

-- Optional: Add indexes for better performance
-- (These are already in init.sql, only run if missing)
-- CREATE INDEX idx_user_id ON cars(user_id);
-- CREATE INDEX idx_plate_number ON cars(plate_number);
