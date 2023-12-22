CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50),
                       email VARCHAR(255),
                       password VARCHAR(255),
                       created DATETIME,
                       modified DATETIME
);
