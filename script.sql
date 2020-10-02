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

### TRIGGER'S ###
# 1. Create cart for Guest after generating sessionId
CREATE TRIGGER new_user
    AFTER UPDATE ON user FOR EACH ROW
    BEGIN
        IF !(NEW.sessionId <=> OLD.sessionId) AND OLD.status = 'Guest' THEN
            INSERT INTO cart(sessionId, userId) value (new.sessionId, old.id);
        END IF;
    END;

# 2. Calculate new price for cart after adding new product into it (price*amount)
CREATE TRIGGER calculate_price_on_insert
    AFTER INSERT
    ON product_cart
    FOR EACH ROW
    UPDATE cart SET  price = price + NEW.amount*(
        SELECT p.price FROM product p
        INNER JOIN product_cart pc ON p.id = pc.productId
        WHERE pc.id = NEW.id
    )
    WHERE cart.id = NEW.cartId;

# 3. Calculate new price for cart after removing product from cart (price*amount)
CREATE TRIGGER calculate_price_on_delete
    BEFORE DELETE
    ON product_cart
    FOR EACH ROW
    UPDATE cart SET  price = price - OLD.amount*(
        SELECT p.price FROM product p
        INNER JOIN product_cart pc ON p.id = pc.productId
        WHERE pc.id = OLD.id
    )
    WHERE cart.id = OLD.cartId;


# 4. Calculate new price for cart after changing amount (price*amount)
CREATE TRIGGER calculate_price_on_amount_update
    BEFORE UPDATE
    ON product_cart
    FOR EACH ROW
    UPDATE cart SET  price = price + (NEW.amount - OLD.amount)*(
        SELECT p.price FROM product p
        INNER JOIN product_cart pc ON p.id = pc.productId
        WHERE pc.id = OLD.id
    )
    WHERE cart.id = OLD.cartId;

# 5. Add points after order
CREATE TRIGGER points_calculate
    AFTER INSERT
    ON orders FOR EACH ROW
    UPDATE user SET  points = points + (SELECT FLOOR(NEW.price/10))
    WHERE user.id = NEW.user;

INSERT INTO user (first_name, last_name, email, status, password)
VALUES
('Matej', 'Malčić', 'matej.malcic3@gmail.com', 'Admin', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Stefan', 'Staffer', 'sefan@staff.com', 'Staff', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Regular', 'Guest', 'noLogin@user.com', 'Guest', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta'),
('Ivo', 'Ivic', 'ivo@example.com', 'Guest', '$2y$10$QmBXi5FYaLNsgrbiDxTq/ORCPsPPomWdsUUOhcvqUInfP/vC4fmta');

INSERT INTO category (name) VALUES
('Wine'),
('Beer');

INSERT INTO status (name) VALUES
('Preparing'),
('Ready to take'),
('Finished');

INSERT INTO product (name, description, category, price) VALUES
('Pinot', 'Description text', 1, 4.00),
('Merlot', 'Description text', 1, 9.00),
('Rizling', 'Description text', 1, 7.50);


