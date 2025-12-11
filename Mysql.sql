CREATE DATABASE clinic_db;
USE clinic_db;

CREATE TABLE users (
id INT auto_increment PRIMARY KEY,
email VARCHAR(100) UNIQUE NOT NULL,
username VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL
);

CREATE TABLE appointments (
 id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    treatment VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time VARCHAR(20) NOT NULL
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users ADD COLUMN role VARCHAR(10) DEFAULT 'user';



