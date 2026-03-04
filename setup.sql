CREATE DATABASE IF NOT EXISTS forum_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE forum_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','mod','admin') NOT NULL DEFAULT 'user',
    bio TEXT DEFAULT NULL,
    is_online TINYINT(1) DEFAULT 0,
    last_seen DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-comments',
    sort_order INT DEFAULT 0
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_pinned TINYINT(1) DEFAULT 0,
    is_locked TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    session_token VARCHAR(255) NOT NULL,
    last_activity DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO categories (name, description, icon, sort_order) VALUES
('General Discussion','Talk about anything','fa-globe',1),
('Tech Talk','Programming and tech','fa-laptop-code',2),
('Gaming','Games and culture','fa-gamepad',3),
('Announcements','Official updates','fa-bullhorn',4),
('Off Topic','Everything else','fa-random',5);

-- Test accounts SHOULD have password as their passwords.
-- create new mods and admins in phpMyAdmin by making an account on register and changing their roles.
INSERT INTO users (username,email,password,role) VALUES
('admin','admin@forum.local','$2y$10$eyO8vjIDeAz0Fy1uI0sC.OsQ0/s8ExY/EAn1kA92UStVSz71qDkh.','admin'),
('moderator','mod@forum.local','$2y$10$eyO8vjIDeAz0Fy1uI0sC.OsQ0/s8ExY/EAn1kA92UStVSz71qDkh.','mod'),
('john_doe','john@forum.local','$2y$10$eyO8vjIDeAz0Fy1uI0sC.OsQ0/s8ExY/EAn1kA92UStVSz71qDkh.','user');

INSERT INTO posts (user_id,category_id,title,content,is_pinned) VALUES
(1,4,'Yep, the brand name','That brand name is indeed a parody on Reddit',1),
(1,1,'New Forum who dis?','This was a hassle to work on',0),
(2,2,'AI sucks at coding','I actually did use AI to work on this on some parts that seem too complex, but they couldnt even do it properly so I end up being the one doing the code anyways with the broken output they had and i just use them as a way to understand some functions and tags on php and icon libraries.',0),
(3,3,'The new pokemon game','Oh right theres also a new pokemon game coming soon thats based on SEA, but its mostly around indonesia, the philippines doesnt have a dedicated island but eh, at lease SEA represent RAAAHHH',0),
(2,2,'I worked on theis before','This isnt actually a completely new project that i made, this was something i dabbled in before but i never really got to make it until i had to complete my activities so i just translated all the work to the requirements',0);
