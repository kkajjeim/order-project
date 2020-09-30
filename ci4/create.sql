CREATE TABLE user
(
id INT UNSIGNED AUTO_INCREMENT,
name VARCHAR(20) NOT NULL,
nickname VARCHAR(30) NOT NULL UNIQUE ,
password VARCHAR(80) NOT NULL,
phone VARCHAR(20) NOT NULL,
email VARCHAR(100) NOT NULL,
gender VARCHAR(10) NOT NULL DEFAULT '',
created_at DATETIME NOT NULL,
updated_at DATETIME NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order`
(
id INT UNSIGNED AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
order_no VARCHAR(12) NOT NULL,
product_name VARCHAR(100) NOT NULL,
created_at DATETIME NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX phone_idx ON user (phone);
CREATE INDEX email_idx ON user (email);
CREATE INDEX order_idx_1 ON `order` (user_id,created_at);

ALTER TABLE `order` ADD FOREIGN KEY user_id_idxfk (user_id) REFERENCES user (id);

CREATE INDEX order_no_idx ON `order` (order_no);