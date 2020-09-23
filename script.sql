DROP DATABASE IF EXISTS restaurant;
CREATE DATABASE restaurant CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE restaurant;

CREATE TABLE user (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name  VARCHAR(50) NOT NULL,
    last_name   VARCHAR(50) NOT NULL,
    username    VARCHAR(50) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    password    CHAR(60) NOT NULL,
    status      VARCHAR(15) NOT NULL DEFAULT 'Guest',
    sessionId   VARCHAR(255)
);
CREATE UNIQUE INDEX emailindex ON user(email);

CREATE TABLE category (
    id      INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name    VARCHAR(59) NOT NULL
);

CREATE TABLE product (
     id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     name 		VARCHAR(255) NOT NULL,
     category  	INT NOT NULL,
     price 	    DECIMAL(10,2) NOT NULL DEFAULT '0.00',
     FOREIGN KEY (category) REFERENCES category(id) ON DELETE CASCADE
);

CREATE TABLE cart (
      id 			INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
      sessionId		VARCHAR(255),
      price			DECIMAL(10,2) DEFAULT 0.00
      #ordered       tinyint default 0
);

create table orders (
    id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user 	    INT NOT NULL,
    cart	    INT NOT NULL,
    time        DATETIME DEFAULT now(),
    status      TINYINT(2) DEFAULT 0,
    FOREIGN KEY (user) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (cart) REFERENCES cart(id) ON DELETE CASCADE
);

CREATE TABLE product_cart (
    id 		   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cartId     INT NOT NULL,
    productId  INT NOT NULL,
    amount     INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cartId) REFERENCES cart (id) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES product (id) ON DELETE CASCADE
);

### TRIGGER ###
# 1. Create cart for user after registration
CREATE TRIGGER new_user
    AFTER INSERT ON user
    FOR EACH ROW
    INSERT INTO cart(sessionId) value (new.sessionId);

# 2. Calculate new price for cart after adding new product into it (price*amount)
CREATE TRIGGER calculate_price
    AFTER INSERT
    ON product_cart
    FOR EACH ROW
    UPDATE cart SET  price = price + NEW.amount*(
        SELECT p.price FROM product p
        INNER JOIN product_cart pc ON p.id = pc.productId
        WHERE pc.id = NEW.id
    )
    WHERE cart.id = NEW.cartId;

INSERT INTO user (first_name, last_name, username, email, status, password)
VALUES
('Matej', 'Malčić', 'maci', 'matej.malcic3@gmail.com', 'Administrator', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Test', 'Tester', 'testing', 'test@example.com', 'Cooker', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Gost', 'Gostić', 'gostoje', 'gost@example.com', 'Guest', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta');