USE polaznik20;

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
