
CREE DATABASE BLOG ;

USE BLOG;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(250) NOT NULL,
    lastName VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    image VARCHAR(600),
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


CREATE TABLE tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tagsname VARCHAR(250) NOT NULL,
);


CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL, 
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    FOREIGN KEY (article_id) REFERENCES article(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,    
    article_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (article_id) REFERENCES article(id)
);


--script



SELECT c.name AS category_name, COUNT(a.id) AS total_articles
FROM catagugry c
LEFT JOIN article a ON c.id = a.catagugry_id
GROUP BY c.name;



SELECT u.firstName, u.lastName, COUNT(a.id) AS total_articles
FROM users u
JOIN article a ON u.id = a.user_id
WHERE u.role = 'auteur'
GROUP BY u.id
ORDER BY total_articles DESC;


SELECT c.name AS category_name
FROM catagugry c
LEFT JOIN article a ON c.id = a.catagugry_id
WHERE a.id IS NULL;


CREATE TABLE article_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES article(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);



DELIMITER $$

CREATE PROCEDURE banuser(IN user_id INT)
BEGIN
    UPDATE users
    SET isactive = 0
    WHERE id = user_id;
END$$

DELIMITER $$ ;


CREATE VIEW most_liked_articles AS
SELECT 
    a.id AS article_id,
    a.title AS article_title,
    COUNT(l.id) AS like_count,
    c.name AS category_name
FROM article a
JOIN catagugry c ON a.catagugry_id = c.id
JOIN likes l ON a.id = l.article_id
GROUP BY a.id, a.title, c.name
ORDER BY like_count DESC;
