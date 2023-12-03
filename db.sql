create database multiuser;
use multiuser;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    nim VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

CREATE TABLE nilai (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    asg INT NOT NULL,
    uts INT NOT NULL,
    uas INT NOT NULL
);

CREATE TABLE dosen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL
);

-- Admin
INSERT INTO users (username, nim, password, role) VALUES ('admin', 'admin123', 'adminpassword', 'admin');

-- User
INSERT INTO users (username, nim, password, role) VALUES ('user1', 'user123', 'userpassword', 'user');
