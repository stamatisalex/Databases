DROP DATABASE IF EXISTS super_market;
CREATE DATABASE super_market;
USE super_market;

CREATE TABLE Store
(
  store_id int NOT NULL AUTO_INCREMENT,
  city enum("Athens","Thessaloniki","Patras") NOT NULL,
  street varchar(50) NOT NULL CHECK(street REGEXP  '^[A-Z][a-zA-Z ]+$'),
  number varchar(10) NOT NULL CHECK(number REGEXP '^-?[0-9]+$' AND (LENGTH(number) >= 1) AND (LENGTH(number) <6) ),
  postal_code varchar(10) NOT NULL CHECK(postal_code REGEXP '^-?[0-9]+$' AND (LENGTH(postal_code) = 5)),
  opening_hours varchar(20) NOT NULL CHECK(opening_hours REGEXP  '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$'
  AND (CAST(SUBSTRING(opening_hours, 1, 2) AS UNSIGNED) < CAST(SUBSTRING(opening_hours, 7, 2) AS UNSIGNED))
  AND (LENGTH(opening_hours) = 11)
 ),
  size decimal(6,2) NOT NULL CHECK(size>0.0),
  PRIMARY KEY (store_id)
);

CREATE TABLE Customer
(
  card_id int NOT NULL AUTO_INCREMENT,
  points varchar(10) NOT NULL DEFAULT 0 CHECK(points REGEXP '^-?[0-9]+$' AND (LENGTH(points) >= 1) AND (LENGTH(points) <7) ),
  name varchar(50) NOT NULL CHECK(name REGEXP '^[A-Z][a-z]+ [A-Z][a-z ]+$') ,
  birth_date DATE NOT NULL,
  sex enum("Female", "Male", "Other") NOT NULL,
  email varchar(50) CHECK(email  REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*@[a-zA-Z0-9][a-zA-Z0-9._-]*\\.[a-zA-Z]{2,4}$'),
  age varchar(10) CHECK(age REGEXP '^-?[0-9]+$' AND CAST(age AS UNSIGNED)>=18 AND CAST(age AS UNSIGNED)<=120),
  marriage_status enum("Single","Married","Divorced","Widowed") NOT NULL,
  number_of_children varchar(10) NOT NULL DEFAULT 0 CHECK(number_of_children REGEXP '^-?[0-9]+$' AND (LENGTH(number_of_children) >= 1) AND (LENGTH(number_of_children) <3) ),
  city varchar(50) NOT NULL CHECK(city REGEXP  '^[A-Z][a-zA-Z ]+$'),
  street varchar(50) NOT NULL CHECK(street REGEXP  '^[A-Z][a-zA-Z ]+$'),
  number varchar(10) NOT NULL DEFAULT 0 CHECK(number REGEXP '^-?[0-9]+$' AND (LENGTH(number) >= 1) AND (LENGTH(number) <5) ),
  postal_code varchar(10) NOT NULL DEFAULT 0 CHECK(postal_code REGEXP '^-?[0-9]+$' AND (LENGTH(postal_code) = 5)),
  pet enum("Cat","Dog","Bird","Other"),
  PRIMARY KEY (card_id)
);

CREATE TABLE Transaction
(
  payment_method enum("Card","Cash") NOT NULL,
  date_time timestamp NOT NULL,
  transaction_id int NOT NULL AUTO_INCREMENT,
  total_number_of_products int(4) CHECK(total_number_of_products>0 AND total_number_of_products<1000),
  total_cost numeric(6,2)  CHECK(total_cost>=0),
  card_id int NOT NULL,
  store_id int NOT NULL,
  PRIMARY KEY (transaction_id),
  FOREIGN KEY (card_id) REFERENCES Customer(card_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (store_id) REFERENCES Store(store_id) ON DELETE CASCADE  ON UPDATE CASCADE
);

CREATE TABLE Category
(
  name varchar(50) NOT NULL UNIQUE CHECK(name REGEXP  '^[A-Z][a-zA-Z ]+$'),
  category_id int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (category_id)
);

CREATE TABLE sells_product_of
(
  store_id int NOT NULL,
  category_id int NOT NULL,
  PRIMARY KEY (store_id, category_id),
  FOREIGN KEY (store_id) REFERENCES Store(store_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Customer_phone_number
(
  phone_number varchar(10) NOT NULL CHECK(char_length(phone_number)=10 and phone_number REGEXP '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]'),
  card_id int NOT NULL,
  PRIMARY KEY (phone_number, card_id),
  FOREIGN KEY (card_id) REFERENCES Customer(card_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Store_phone_number
(
  phone_number varchar(10) NOT NULL CHECK(char_length(phone_number)=10 and phone_number REGEXP '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]'),
  store_id int NOT NULL,
  PRIMARY KEY (phone_number, store_id),
  FOREIGN KEY (store_id) REFERENCES Store(store_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Product
(
  chain_tag enum("1","0") NOT NULL,
  price numeric(6,2) DEFAULT 0 CHECK(price>=0 and price<=9999),
  extra_points varchar(10) NOT NULL DEFAULT 0 CHECK(extra_points REGEXP '^-?[0-9]+$' AND (LENGTH(extra_points) >= 1) AND (LENGTH(extra_points) <7) ),
  barcode varchar(9) NOT NULL CHECK((char_length(barcode)=8 or ((substring(barcode,1,1)) ='0' and (char_length(barcode)=7)))  and barcode REGEXP '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]'),
  brand varchar(50),
  name varchar(50) NOT NULL,
  category_id int NOT NULL,
  PRIMARY KEY (barcode),
  FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Price_history
(
  start_date timestamp NOT NULL,
  price numeric(6,2) DEFAULT 0 CHECK(price>=0 and price<=9999),
  end_date timestamp,
  barcode varchar(9) NOT NULL,
  PRIMARY KEY (start_date, barcode),
  FOREIGN KEY (barcode) REFERENCES Product(barcode) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE sells
(
  corridor int(2) NOT NULL CHECK(corridor>0),
  shelf varchar(10) NOT NULL DEFAULT 1 CHECK(shelf REGEXP '^-?[0-9]+$' AND (LENGTH(shelf) >= 1) AND (LENGTH(shelf) <3) AND (CAST(shelf AS UNSIGNED)>0 )),
  stock varchar(10) NOT NULL DEFAULT 1 CHECK(stock REGEXP '^-?[0-9]+$' AND  (LENGTH(stock) <4) AND (CAST(stock AS UNSIGNED)>=0 )),
  store_id int NOT NULL,
  barcode varchar(9) NOT NULL,
  PRIMARY KEY (store_id, barcode),
  FOREIGN KEY (store_id) REFERENCES Store(store_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (barcode) REFERENCES Product(barcode) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE includes
(
  quantity int(4) NOT NULL CHECK(quantity>0),
  transaction_id int NOT NULL,
  barcode varchar(9) NOT NULL,
  PRIMARY KEY (transaction_id, barcode),
  FOREIGN KEY (transaction_id) REFERENCES Transaction(transaction_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (barcode) REFERENCES Product(barcode) ON DELETE NO ACTION ON UPDATE CASCADE
);
SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
