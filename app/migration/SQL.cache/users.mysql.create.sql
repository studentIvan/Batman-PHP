CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(32) NOT NULL, password VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB