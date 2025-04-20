-- Database schema for Hospital Management System

CREATE DATABASE IF NOT EXISTS hospital_management;
USE hospital_management;

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address VARCHAR(255),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    medical_history TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Doctors table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status ENUM('Scheduled', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE RESTRICT
);

-- Sample data for patients
INSERT INTO patients (first_name, last_name, date_of_birth, gender, address, phone, email, medical_history) VALUES
('John', 'Doe', '1980-05-15', 'Male', '123 Main St, Anytown', '555-123-4567', 'john.doe@email.com', 'Hypertension, Diabetes'),
('Jane', 'Smith', '1975-08-22', 'Female', '456 Oak Ave, Somewhere', '555-987-6543', 'jane.smith@email.com', 'Asthma'),
('Michael', 'Johnson', '1990-03-10', 'Male', '789 Pine Rd, Nowhere', '555-456-7890', 'michael.j@email.com', 'No significant history'),
('Emily', 'Williams', '1985-11-30', 'Female', '321 Elm St, Anywhere', '555-789-0123', 'emily.w@email.com', 'Allergies to penicillin');

-- Sample data for doctors
INSERT INTO doctors (first_name, last_name, specialization, qualification, phone, email, address) VALUES
('Robert', 'Brown', 'Cardiology', 'MD, FACC', '555-111-2222', 'dr.brown@hospital.com', '100 Hospital Blvd, Medtown'),
('Sarah', 'Davis', 'Pediatrics', 'MD, FAAP', '555-333-4444', 'dr.davis@hospital.com', '100 Hospital Blvd, Medtown'),
('James', 'Wilson', 'Orthopedics', 'MD, FAAOS', '555-555-6666', 'dr.wilson@hospital.com', '100 Hospital Blvd, Medtown'),
('Lisa', 'Miller', 'Neurology', 'MD, PhD', '555-777-8888', 'dr.miller@hospital.com', '100 Hospital Blvd, Medtown');

-- Sample data for appointments
INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, notes) VALUES
(1, 1, '2023-06-15', '09:00:00', 'Annual checkup', 'Completed', 'Patients blood pressure is normal'),
(2, 2, '2023-06-16', '10:30:00', 'Flu symptoms', 'Completed', 'Prescribed antibiotics'),
(3, 3, '2023-06-20', '14:00:00', 'Knee pain', 'Scheduled', 'MRI might be needed'),
(4, 4, '2023-06-22', '11:15:00', 'Headaches', 'Scheduled', 'Possible migraine');