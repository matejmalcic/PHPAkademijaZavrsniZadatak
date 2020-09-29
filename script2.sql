USE polaznik20;

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

create table orders (
    id 		    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user 	    INT NOT NULL,
    cart	    INT NOT NULL UNIQUE,
    price       DECIMAL(10,2),
    time        TIME DEFAULT time(),
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


CREATE TRIGGER new_user
    AFTER UPDATE ON user FOR EACH ROW
    BEGIN
        IF !(NEW.sessionId <=> OLD.sessionId) AND OLD.status = 'Guest' THEN
            INSERT INTO cart(sessionId, userId) value (new.sessionId, old.id);
        END IF;
    END;

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
