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
    sessionId   VARCHAR(255)
);
CREATE UNIQUE INDEX emailindex ON user(email);

CREATE TABLE subcategory (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(50) NOT NULL
);

CREATE TABLE product (
     id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     name 		    VARCHAR(255) NOT NULL,
     image 		    VARCHAR(255),
     description    TEXT,
     category  	    VARCHAR(10) NOT NULL,
     subcategory    INT NOT NULL,
     price 	        DECIMAL(10,2) NOT NULL DEFAULT '0.00',
     FOREIGN KEY (subcategory) REFERENCES subcategory(id) ON DELETE CASCADE
);

CREATE TABLE cart (
      id 			INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
      userId		INT NOT NULL,
      sessionId		VARCHAR(255) NOT NULL UNIQUE,
      price			DECIMAL(10,2) DEFAULT 0.00,
      FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE
      #ordered       tinyint default 0
);

create table orders (
    id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user 	    INT NOT NULL,
    cart	    INT NOT NULL UNIQUE,
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
    AFTER UPDATE ON user
    FOR EACH ROW
    INSERT INTO cart(sessionId, userId) value (new.sessionId, old.id);

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

INSERT INTO subcategory (name) VALUES
('Wine'),
('Beer'),
('Cocktail'),
('Hot Drinks'),
('Juice'),
('Soup'),
('Breakfast'),
('Lunch'),
('Dinner'),
('Sea food'),
('Salad'),
('Desert');

INSERT INTO product (name, category, subcategory, price) VALUES
('Wine1', 'drink', 1, 4.99),
('Wine2', 'drink', 1, 9.99),
('Wine3', 'drink', 1, 14.99),
('Wine4', 'drink', 1, 19.99),
('Beer1', 'drink', 2, 4.99),
('Beer2', 'drink', 2, 4.99),
('Beer3', 'drink', 2, 4.99),
('Beer4', 'drink', 2, 4.99),
('Cocktail1', 'drink', 3, 24.99),
('Cocktail2', 'drink', 3, 24.99),
('Cocktail3', 'drink', 3, 24.99),
('Coffee', 'drink', 4, 2.99),
('Cappuccino', 'drink', 4, 2.99),
('Tea', 'drink', 4, 2.99),
('Coca-cola', 'drink', 5, 3.99),
('Pepsi', 'drink', 5, 3.99),
('Sprite', 'drink', 5, 3.99),
('Chicken Soup', 'eat', 6, 5.99),
('Coyote Soup', 'eat', 6, 5.99),
('Chinese Soup', 'eat', 6, 5.99),
('Double Egg', 'eat', 7, 5.99),
('Natural Break', 'eat', 7, 5.99),
('Spicy Start', 'eat', 7, 5.99),
('Star Lunch', 'eat', 8, 5.99),
('Hap Lunch', 'eat', 8, 5.99),
('Mardeljone', 'eat', 8, 5.99),
('Thai Way', 'eat', 9, 5.99),
('Pasta', 'eat', 9, 5.99),
('Golden shrimp', 'eat', 10, 5.99),
('Octopus', 'eat', 10, 5.99),
('Swordfish', 'eat', 10, 5.99),
('Green salad', 'eat', 11, 5.99),
('Mexican', 'eat', 11, 5.99),
('Mixed', 'eat', 11, 5.99),
('Biskvit', 'eat', 12, 5.99),
('Madarone', 'eat', 12, 5.99),
('Creampie', 'eat', 12, 5.99);

INSERT INTO product_cart (cartId, productId) VALUES
(1,2), (1,5), (1,6),
(1,31), (1,10), (1,12),
(1,17), (1,23), (1,24);