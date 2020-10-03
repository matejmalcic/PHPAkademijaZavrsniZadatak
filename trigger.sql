USE restaurant;
# 1. Create cart for Guest after generating sessionId
CREATE TRIGGER new_user
    AFTER UPDATE
    ON user
    FOR EACH ROW
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

# 5. Copy cart price to order price after update
CREATE TRIGGER copy_price
    AFTER UPDATE
    ON cart
    FOR EACH ROW
BEGIN
    IF NEW.ordered = 1 THEN
        UPDATE orders SET price = NEW.price
        WHERE NEW.id = orders.cart;
    END IF;
END;

# 6. Add points after order
CREATE TRIGGER points_calculate
    AFTER INSERT
    ON orders FOR EACH ROW
    UPDATE user SET  points = points + (SELECT FLOOR(NEW.price/10))
    WHERE user.id = NEW.user;