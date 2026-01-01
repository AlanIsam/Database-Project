-- Database Schema for Wellness Center Management System

CREATE DATABASE IF NOT EXISTS wellness_center;
USE wellness_center;

-- Table for Members
CREATE TABLE members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    join_date DATE NOT NULL,
    membership_status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Table for Memberships
CREATE TABLE memberships (
    membership_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    membership_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    fee DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- Table for Trainers
CREATE TABLE trainers (
    trainer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    hire_date DATE NOT NULL
);

-- Table for Program Categories
CREATE TABLE program_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table for Programs
CREATE TABLE programs (
    program_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    duration_weeks INT,
    fee DECIMAL(10,2),
    FOREIGN KEY (category_id) REFERENCES program_categories(category_id) ON DELETE CASCADE
);

-- Table for Classes
CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    trainer_id INT NOT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    capacity INT DEFAULT 20,
    FOREIGN KEY (program_id) REFERENCES programs(program_id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE CASCADE
);

-- Table for Enrollments
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    class_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('enrolled', 'completed', 'dropped') DEFAULT 'enrolled',
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Table for Payments
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_type ENUM('membership', 'program') NOT NULL,
    description TEXT,
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- Table for Trainer Program History
CREATE TABLE trainer_program_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NOT NULL,
    category_id INT NOT NULL,
    assigned_date DATE NOT NULL,
    end_date DATE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES program_categories(category_id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO members (first_name, last_name, email, phone, join_date) VALUES
('John', 'Doe', 'john.doe@example.com', '123-456-7890', '2023-01-01'),
('Jane', 'Smith', 'jane.smith@example.com', '098-765-4321', '2023-02-01');

INSERT INTO trainers (first_name, last_name, email, phone, hire_date) VALUES
('Alice', 'Johnson', 'alice.johnson@example.com', '111-222-3333', '2022-01-01'),
('Bob', 'Brown', 'bob.brown@example.com', '444-555-6666', '2022-03-01');

INSERT INTO program_categories (name, description) VALUES
('Fitness', 'General fitness programs'),
('Yoga', 'Yoga and meditation classes'),
('Nutrition', 'Diet and nutrition guidance');

INSERT INTO programs (name, category_id, description, duration_weeks, fee) VALUES
('Weight Loss Program', 1, 'A program to help lose weight', 8, 200.00),
('Yoga Basics', 2, 'Introduction to yoga', 4, 100.00);

INSERT INTO classes (program_id, trainer_id, scheduled_date, scheduled_time, status, capacity) VALUES
(1, 1, '2023-10-01', '10:00:00', 'active', 20),
(2, 2, '2023-10-02', '14:00:00', 'active', 15);

INSERT INTO enrollments (member_id, class_id, enrollment_date) VALUES
(1, 1, '2023-09-01'),
(2, 2, '2023-09-02');

INSERT INTO payments (member_id, amount, payment_date, payment_type, description) VALUES
(1, 200.00, '2023-09-01', 'program', 'Payment for Weight Loss Program'),
(2, 100.00, '2023-09-02', 'program', 'Payment for Yoga Basics');

INSERT INTO memberships (member_id, membership_type, start_date, end_date, fee) VALUES
(1, 'Premium', '2023-01-01', '2024-01-01', 500.00),
(2, 'Basic', '2023-02-01', '2024-02-01', 300.00);

INSERT INTO trainer_program_history (trainer_id, category_id, assigned_date) VALUES
(1, 1, '2022-01-01'),
(2, 2, '2022-03-01');