DROP DATABASE IF EXISTS restaurant;
CREATE DATABASE restaurant CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE restaurant;

CREATE TABLE user (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name  VARCHAR(50) NOT NULL,
    last_name   VARCHAR(50) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    password    CHAR(60) NOT NULL,
    status      VARCHAR(15) NOT NULL DEFAULT 'Guest',
    points      INT DEFAULT 0,
    sessionId   VARCHAR(255)
);
CREATE UNIQUE INDEX emailindex ON user(email);

CREATE TABLE category (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(50) NOT NULL
);

CREATE TABLE product (
     id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     name 		    VARCHAR(255) NOT NULL,
     image 		    VARCHAR(255) NOT NULL DEFAULT '/images/products/unknownProduct.jpg',
     description    TEXT,
     category  	    INT NOT NULL,
     price 	        DECIMAL(10,2) NOT NULL DEFAULT '0.00',
     FOREIGN KEY (category) REFERENCES category(id) ON DELETE CASCADE
);

CREATE TABLE cart (
      id 			INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
      userId		INT NOT NULL,
      sessionId		VARCHAR(255) NOT NULL UNIQUE,
      price			DECIMAL(10,2) DEFAULT 0.00,
      ordered		BOOL DEFAULT 0,
      FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE status (
    id      INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name    VARCHAR(50) UNIQUE
);

create table orders (
    id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user 	    INT NOT NULL,
    cart	    INT NOT NULL UNIQUE,
    price       DECIMAL(10,2),
    time        TIME,
    status      VARCHAR(50) NOT NULL DEFAULT 'Preparing',
    FOREIGN KEY (user) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (cart) REFERENCES cart(id) ON DELETE CASCADE,
    FOREIGN KEY (status) REFERENCES status(name) ON DELETE CASCADE
);

CREATE TABLE product_cart (
    id 		   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cartId     INT NOT NULL,
    productId  INT NOT NULL,
    amount     INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cartId) REFERENCES cart (id) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES product (id) ON DELETE CASCADE
);