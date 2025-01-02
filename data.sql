
CREE DATABASE BLOG ;

USE BLOG;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(250) NOT NULL,
    lastName VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    role ENUM('visteur','admin' , 'auteur'),
    
);

CREATE TABLE catagugry (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
);

CREATE TABLE article (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    catagugry_id INT NOT NULL,
    image VARCHAR(500),
    date_creation DATE,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (catagugry_id) REFERENCES catagugry(id)
);
