DELIMITER $$
CREATE TRIGGER `check_store_number_same` BEFORE INSERT ON `Store_phone_number`
 FOR EACH ROW BEGIN
IF(SELECT COUNT(*) FROM Store_phone_number WHERE store_id LIKE New.store_id AND phone_number LIKE New.phone_number )
THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = "number entered again";
END IF;
END
$$
DELIMITER ;
DELIMITER $$

CREATE TRIGGER `check_same` BEFORE INSERT ON `Customer_phone_number`
 FOR EACH ROW BEGIN
IF(SELECT COUNT(*) FROM Customer_phone_number WHERE card_id LIKE New.card_id AND phone_number LIKE New.phone_number )
THEN
SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT =  "number entered again";
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calc_age` BEFORE INSERT ON `Customer` FOR EACH ROW BEGIN
IF((CAST(YEAR(CURDATE())AS UNSIGNED) - CAST(SUBSTRING(New.birth_date,1,4) AS UNSIGNED))>18)
THEN
SET New.Age = CAST(YEAR(CURDATE())AS UNSIGNED) - CAST(SUBSTRING(New.birth_date,1,4) AS UNSIGNED);
ELSE
SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT =  "too young";
END IF;
END
$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `add_total_cost` AFTER INSERT ON `includes` FOR EACH ROW begin
declare quant int;
declare bar varchar(11);
declare done int default false;
declare my_cursor cursor for select barcode,quantity from includes where
		   transaction_id = New.transaction_id;
declare continue handler for sqlstate '02000' set done = 1;
SET @total = 0;
SET @iter = 0;


open my_cursor;


cursor_loop:repeat
    fetch my_cursor into bar,quant;
    if done then
        leave cursor_loop;
    end if;
    	SET @iter = @iter +1;
    	SET @total = @total + (SELECT price FROM Product WHERE barcode LIKE bar)*quant;
    until done
end repeat;

UPDATE Transaction SET total_cost = @total WHERE Transaction.transaction_id LIKE New.transaction_id;

close my_cursor;

end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `add_total_number_of_products` AFTER INSERT ON `includes` FOR EACH ROW begin
declare quant int;
declare done int default false;
declare my_cursor cursor for select quantity from includes where transaction_id = New.transaction_id;
declare continue handler for sqlstate '02000' set done = 1;
SET @v1 = 0;


open my_cursor;


cursor_loop:repeat
    fetch my_cursor into quant;
    if done then
        leave cursor_loop;
    end if;
    	SET @v1 = @v1+ quant;

    until done
end repeat;

UPDATE Transaction SET total_number_of_products = @v1 WHERE Transaction.transaction_id LIKE New.transaction_id;

close my_cursor;

end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_product_sold_here` BEFORE INSERT ON `includes` FOR EACH ROW BEGIN
SET @v1 = (SELECT category_id FROM Product
   WHERE barcode LIKE New.barcode);
SET @v2 = (SELECT store_id FROM Transaction WHERE transaction_id LIKE New.transaction_id);
IF @v1 NOT IN (SELECT category_id FROM sells_product_of WHERE store_id LIKE @v2)
   THEN
   		 SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT =  @v1;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$

CREATE TRIGGER `check_stock` BEFORE INSERT ON `includes` FOR EACH ROW BEGIN
SET @v2 = (SELECT store_id FROM Transaction WHERE transaction_id LIKE New.transaction_id);
SET @v1 = (SELECT stock FROM sells WHERE barcode LIKE New.barcode AND store_id LIKE @v2) ;

IF (@v1 - New.quantity) <=0
THEN

	UPDATE sells SET stock = 0 WHERE barcode LIKE New.barcode AND store_id LIKE @v2;
    SET New.quantity = @v1;
ELSE
	UPDATE sells SET stock = stock - New.quantity WHERE barcode LIKE New.barcode AND store_id LIKE @v2;

END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `do_extra_points_to_user` AFTER INSERT ON `includes` FOR EACH ROW begin

declare bar varchar(11);
declare quant int;


declare done int default false;
declare my_cursor cursor for select barcode,quantity from includes where transaction_id = New.transaction_id;
declare continue handler for sqlstate '02000' set done = 1;

open my_cursor;


cursor_loop:repeat
   fetch my_cursor into bar,quant;
    if done then
        leave cursor_loop;
    end if;
    	UPDATE CUSTOMER SET points = points+(SELECT extra_points FROM Product WHERE barcode LIKE bar)*quant WHERE card_id LIKE (SELECT card_id FROM Transaction WHERE Transaction.transaction_id LIKE New.transaction_id);
    until done
end repeat;

close my_cursor;

end
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `history_on_insert` AFTER INSERT ON `Product` FOR EACH ROW BEGIN
INSERT INTO  Price_history(start_date ,price ,barcode) VALUES(CURRENT_TIMESTAMP(),New.price, New.barcode);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `on_update` BEFORE UPDATE ON `Product` FOR EACH ROW BEGIN
UPDATE Price_history SET end_date =CURRENT_TIMESTAMP() WHERE ((barcode LIKE Old.barcode) AND (end_date is NULL));
IF(New.price is NULL)
THEN
DELETE FROM sells WHERE barcode LIKE Old.barcode;
ELSE
INSERT INTO Price_history(start_date,price,end_date,barcode) VALUES( CURRENT_TIMESTAMP(),New.price,NULL, New.barcode);
END IF;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `check_sells` BEFORE INSERT ON `sells` FOR EACH ROW BEGIN
      IF ((SELECT category_id FROM Product WHERE Product.barcode = NEW.barcode)
        NOT IN(SELECT category_id FROM sells_product_of WHERE sells_product_of.store_id = NEW.store_id))

      THEN

      SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT =  NEW.store_id;




      END IF;
  END
$$
DELIMITER ;



DELIMITER $$
CREATE TRIGGER `check_hour_date` BEFORE INSERT ON `Transaction` FOR EACH ROW BEGIN

SET @v4 = (SELECT opening_hours FROM Store WHERE store_id LIKE NEW.store_id);
SET @v5 = CAST(SUBSTR(@v4,1,2) AS UNSIGNED);
SET @v6 = CAST(SUBSTR(@v4,7,2) AS UNSIGNED);



SET @v3 := CAST(SUBSTRING((New.date_time),12,2) AS UNSIGNED);
IF @v3<@v5 OR @v3>@v6
THEN
 SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT =  "date_time";


END IF;
END
$$
DELIMITER ;
