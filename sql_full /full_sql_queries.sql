--total_queries from php files. Not to be run
--queries from delete_shop.php
DELETE FROM Store WHERE store_id LIKE $_POST; -- deletes shop based on its id, that it is given as post parameter
--queries from fetch_selected.php, fetches all stores in database along with its parameters in order to edit whichone the user chooses
SELECT category_id FROM sells_product_of WHERE store_id LIKE $current;--selects the categories that a store can sell based on its id that its given in the $current parameter
SELECT * FROM Store;--select all stores in datbase
SELECT * FROM Category; --selects all categories
SELECT phone_number FROM Store_phone_number WHERE store_id LIKE $shop_id_list[$i];--select all phone numbers of this shop_list
--queries from insert_shop.php
START TRANSACTION --starts mysql transaction
INSERT INTO  Store(city,street,number,postal_code,opening_hours,size) VALUES
 ('$_POST[0]','$_POST[1]',$_POST[2],$_POST[3],'$_POST[4]',$_POST[5]);-- inserts new shop with post parameters
 INSERT INTO  Store_phone_number(phone_number,store_id) VALUES ($to_insert[$i],$inserted_id); --query that executes multiple insert(i from 0 to given telephones) to Store_phone_number. $inserted_id is the id of the last inserted element on the Store table, which has these phone numbers
 INSERT INTO sells_product_of VALUES ($inserted_id,$to_insert); -- like the previous query, it inserts in the table sells_product_of the categories that the last inseted shop can sell, with a multiple insert. $to_insert  holds the category to be inserted
COMMIT --commits the transaction if fail ROLLBACK is performed

--queries from update_store.php
START TRANSACTION --starts mysql transaction
DELETE FROM Store_phone_number WHERE store_id LIKE $_POST[6]; --deletes all phone numbers that correspond to the updated store, given in post[6] parameter in order for the new ones to be inserted
INSERT INTO  Store_phone_number(phone_number,store_id) VALUES ($selected[0],$_POST[6])--,....,($selected[$i],$_POST[6]) inserts the new phone numbers for the updated store

UPDATE Store
SET
city = '$_POST[0]',
street ='$_POST[1]',
number = $_POST[2],
postal_code = $_POST[3],
opening_hours = '$_POST[4]',
size = $_POST[5]
WHERE store_id LIKE $_POST[6]; -- updates Store based on post parameters
DELETE FROM sells_product_of WHERE store_id LIKE $_POST[6];--exactly like the first query
INSERT INTO sells_product_of VALUES ($_POST[6],$to_insert);--exactly like the second query
COMMIT --commits the transaction if fail ROLLBACK is performed

--cust.php: in general the page fetches products that are sold in a choosen store:
SELECT category_id FROM sells_product_of WHERE store_id = $store; --select categories that choosen store sells
SELECT * FROM Product WHERE category_id = $category_list[0] and price IS NOT NULL; -- mulitple select, in the following lines of php file it expands for category_id = $category_list[$i] e.t.c. select products that are not deleted and belong to the categories that the store sells
--cust_start.php
SELECT store_id,street,city FROM Store;
--get_order.php
INSERT INTO Transaction(card_id,store_id,date_time,payment_method) VALUES($_POST[4],$_POST[6],'$_POST[3]','$_POST[5]'); --insert new record in Transaction table, with null total_cost and total_number_of_products
INSERT INTO includes VALUES ($quant,($fid),'$id'); -- multiple insert of products that belong to last inserted transaction: quant ->quantity. $fid ->transaction id/ $id ->barcode of product
--delete_customer.php
DELETE  FROM Customer WHERE card_id LIKE $_POST;
--fetch_customer.php
SELECT * FROM Customer;
SELECT phone_number FROM Customer_phone_number WHERE card_id LIKE $user_card_list[$i];--$user_card_list array that holds all card ids - extracted from the previous select query
--insert_customer.php
START TRANSACTION
INSERT INTO  Customer(card_id,points,name,email,number_of_children,city,street,postal_code,sex,marriage_status,pet,number,birth_date) VALUES
 ($_POST[0],$_POST[1],'$_POST[2]','$_POST[3]',$_POST[4],'$_POST[5]','$_POST[6]',$_POST[7],'$_POST[8]','$_POST[9]','$_POST[10]',$_POST[11],$_POST[12]); --insert new customer based on post parameters
INSERT INTO  Customer_phone_number(phone_number,card_id) VALUES ($to_insert[0],$inserted_id);--multiple insert, in same file bellow are added ($to_insert[$i],$inserted_id)of customer's phone numbers
COMMIT --commits the transaction if fail ROLLBACK is performed
--update_customer.php
START TRANSACTION
UPDATE Customer
SET card_id = $_POST[0],
points = $_POST[1],
name ='$_POST[2]',
email = '$_POST[3]',
number_of_children = $_POST[4],
city = '$_POST[5]',
street = '$_POST[6]',
postal_code = $_POST[7],
sex = '$_POST[8]',
marriage_status = '$_POST[9]',
pet = '$_POST[10]',
number = $_POST[11],
age = $_POST[13],
birth_date = $_POST[12]
WHERE card_id LIKE $_POST[14];

DELETE FROM Customer_phone_number WHERE card_id LIKE $_POST[0]; -- deletes Customer's old phone numbers
INSERT INTO  Customer_phone_number(phone_number,card_id) VALUES ($selected[0],$_POST[0]);--uploads new Customer's phone numbers. Later ($selected[$i], $_POST[0]) is added for i in all phone numbers
--delete_product.php
START TRANSACTION
UPDATE Product SET price = NULL WHERE barcode LIKE '$_POST';
--fetch_history.php
SELECT start_date,price,end_date FROM Price_history WHERE barcode LIKE '$_POST';
--quick_fetch_category.php
SELECT category_id,name FROM Category;
--quick_fetch_sho.php
SELECT store_id,city,street FROM Store;
--fetch_product.php
SELECT * FROM Product;
SELECT * FROM Price_history WHERE barcode LIKE $barcodes[$k];--$barcodes holds every product's barcode,fetched from previous query
SELECT * FROM sells WHERE barcode LIKE '$barcodes[$k]'; --same

--upload_product.php
START TRANSACTION
INSERT INTO  Product(chain_tag ,price ,extra_points,barcode,brand,name,category_id) VALUES
 ('$_POST[0]','$_POST[1]','$_POST[2]','$_POST[3]','$_POST[4]','$_POST[5]','$_POST[6]');--inserts new product

 INSERT INTO  sells(corridor ,shelf ,stock,store_id,barcode) VALUES
 ('$cor' , '$shelf' , '$stock' , '$cur' , '$_POST[3]');

 --update_product.php
 START TRANSACTION
UPDATE Product
SET chain_tag = '$_POST[0]',
price = '$_POST[1]',
extra_points ='$_POST[2]',
barcode = '$_POST[3]',
brand = '$_POST[4]',
name = '$_POST[5]',
category_id = $_POST[6] WHERE barcode = $_POST[8];--updates selected product with given post parameters

DELETE FROM sells WHERE barcode LIKE '$_POST[3]';--deletes old records from sells table based on their barcode given in $_POST[3]

INSERT INTO sells(corridor,shelf,stock,store_id,barcode) VALUES('$cor','$shelf','$stock','$cur','$_POST[3]');--insert new sells values '$cor','$shelf','$stock','$cur' that are given as post parameters. in the same file beloe  other tuples are added to same query

--fetch_add.php
SELECT city,street FROM Store;

--fetch_trans.php
--first criterion. first is POST[1] and its an array of low limit and sec which is POST[2] has up limits.POST[3] has store id
SELECT * FROM Transaction WHERE DATE(Transaction.date_time) BETWEEN '$first[0]' AND '$sec[0]' AND store_id = ".$_POST[3]";
SELECT * FROM Transaction WHERE total_cost BETWEEN ".$first[0]." AND ".$sec[0]." AND store_id = $_POST[3];
SELECT * FROM Transaction WHERE payment_method = '$first[0]' AND store_id = $_POST[3];
SELECT * FROM Transaction WHERE total_number_of_products BETWEEN ".$first[0]." AND ".$sec[0]." AND store_id = $_POST[3];
--rest criteria. second criterion chooses from the table returned from first criterion, third from second, fourth from third etc.
SELECT * FROM ($sql) AS T WHERE DATE(Transaction.date_time) BETWEEN  '$first[0]' AND '$sec[0]';
SELECT * FROM ($sql) AS T WHERE total_cost BETWEEN ".$first[$i]." AND $sec[$i];
SELECT * FROM ($sql) AS T WHERE payment_method = '$first[$i]';
SELECT * FROM ($sql) AS T WHERE total_number_of_products BETWEEN ".$first[$i]." AND $sec[$i];
--fetches all transactions that fullfill previous criteria :
SELECT * FROM Transaction WHERE transaction_id IN (SELECT DISTINCT i.transaction_id  FROM ($sql) AS T ,includes as i,  Product as p  WHERE p.barcode = i.barcode AND i.transaction_id IN (SELECT transaction_id FROM ($sql) AS T ));
--fetches all transactions that fullfill previous criteria, applying category criterion as well p.category_id = $cur[0] , cur is array of wanted categories and in same file p.category_id = $cur[$i] is added:
SELECT * FROM Transaction WHERE transaction_id IN (SELECT DISTINCT i.transaction_id  FROM ($sql) AS T ,includes as i,  Product as p  WHERE p.barcode = i.barcode AND i.transaction_id IN (SELECT transaction_id FROM ($sql) AS T  )AND p.category_id = $cur[0];

--get_address_of_stores.php
select Store.store_id as store_id,Store.city as city,Store.street as street,Store.number as number,Store.postal_code as postal_code
            from Store
            where Store.store_id in (select Transaction.store_id
                        from Transaction
                        where Transaction.card_id='$Card_id');
--get_avg_cost_per_week_month.php
select FORMAT(avg(T.total_cost),2) as total , T.week_of_month as week_of_month
            from
            (select Transaction.total_cost as total_cost, EXTRACT(YEAR FROM Transaction.date_time) as year, EXTRACT(MONTH FROM Transaction.date_time) as month, EXTRACT(WEEK FROM Transaction.date_time) as week, 1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7) as week_of_month
                from Transaction
                where Transaction.card_id='$Card_id' and (EXTRACT(YEAR FROM Transaction.date_time))='$y' and (EXTRACT(MONTH FROM Transaction.date_time))='$m' ) as T

            group by T.week_of_month,T.month,T.year
            order by T.week_of_month;
--get_avg_cost_per_whole_month.php
select FORMAT(avg(T.total_cost),2) as total
            from
            (select Transaction.total_cost as total_cost, EXTRACT(YEAR FROM Transaction.date_time) as year, EXTRACT(MONTH FROM Transaction.date_time) as month
                from Transaction
                where Transaction.card_id='$Card_id' and (EXTRACT(YEAR FROM Transaction.date_time))='$y' and (EXTRACT(MONTH FROM Transaction.date_time))='$m' ) as T

            group by T.month,T.year;
--get_avg_num.php
select FORMAT(avg(T.num),2) as number, T.week_of_month  from (
            select count(Transaction.transaction_id) as num, 1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7) as week_of_month
            from Transaction
            where Transaction.card_id='$Card_id' and EXTRACT(MONTH FROM Transaction.date_time)='$m'
            group by (1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7)),EXTRACT(YEAR FROM Transaction.date_time)
            ) as T
            group by T.week_of_month
            order by T.week_of_month;
--get_categories.php
select category_id, name from Category;
--get_customers.php
select card_id, name from Customer;
--get_diagram.php
select count(Transaction.transaction_id) as count,EXTRACT(HOUR FROM Transaction.date_time) as time
            from Transaction
            where Transaction.card_id='$Card_id' and Transaction.store_id='$s'
            group by EXTRACT(HOUR FROM Transaction.date_time)
            order by time ASC;

            select Store.opening_hours as open
                           from Store
                           where Store.store_id='$s';
--get_most_popular_products.php
select T1.name, T1.brand, T1.barcode, T1.total from
(select Product.name as name, Product.brand as brand, includes.barcode as barcode,sum(includes.quantity) as total
                    from includes,Product
                    where (includes.transaction_id in (select Transaction.transaction_id
                                                from Transaction
                                                where Transaction.card_id='$Card_id'
                                                )
                        and product.barcode=includes.barcode)
                    group by includes.barcode) as T1
left join (select Product.name as name, Product.brand as brand, includes.barcode as barcode,sum(includes.quantity) as total
                    from includes,Product
                    where (includes.transaction_id in (select Transaction.transaction_id
                                                from Transaction
                                                where Transaction.card_id='$Card_id'
                                                )
                        and product.barcode=includes.barcode)
                    group by includes.barcode) as T2
on (T1.total<T2.total)
GROUP by (T1.barcode)
HAVING COUNT(*)<=10
ORDER by T1.total DESC;
--get_num_per_whole_month.php
select FORMAT(avg(T.num),2) as total from (
            select count(Transaction.transaction_id) as num
            from Transaction
            where Transaction.card_id='$Card_id' and EXTRACT(MONTH FROM Transaction.date_time)='$m'
            group by EXTRACT(YEAR FROM Transaction.date_time)
            ) as T;
--get_number_of_stores.php
select count(distinct store_id ) as count
             from Transaction
             where card_id='$Card_id';
--get_age_groups.php
SELECT table1.store_id,table1.Hour,
                  SUM(CASE WHEN table1.age < 18 THEN 1 ELSE 0 END) AS 'Age_Under_18',
                  SUM(CASE WHEN table1.age BETWEEN 18 AND 24 THEN 1 ELSE 0 END) AS '18-24',
                  SUM(CASE WHEN table1.age BETWEEN 25 AND 34 THEN 1 ELSE 0 END) AS '25-34',
                  SUM(CASE WHEN table1.age BETWEEN 35 AND 42 THEN 1 ELSE 0 END) AS '35-42',
                  SUM(CASE WHEN table1.age BETWEEN 43 AND 50 THEN 1 ELSE 0 END) AS '43-50',
                  SUM(CASE WHEN table1.age BETWEEN 51 AND 64 THEN 1 ELSE 0 END) AS '51-64',
                  SUM(CASE WHEN table1.age > 65 THEN 1 ELSE 0 END) AS 'Age_Over_65'
           FROM (  SELECT c.age, Transaction.store_id,EXTRACT(HOUR FROM Transaction.date_time) as Hour
             from Customer as c,Transaction
             where c.card_id=Transaction.card_id
      ) table1
      group by table1.Hour,table1.store_id
      order by  table1.store_id asc,table1.Hour;
--get_hours.php
select store_id,max(Total_cost) as Total_cost,Hour
from(select Transaction.store_id,sum(ROUND(Transaction.total_cost,2)) as Total_cost,EXTRACT(HOUR FROM Transaction.date_time) as Hour
from Transaction
group by EXTRACT(HOUR FROM Transaction.date_time),store_id
order by Transaction.store_id asc,Total_cost Desc)T
group by store_id;
--get_percentage.php
select P.category_id,sum(table3.value_occurrence) as product_num
          from Product as P left join (select table2.barcode,table2.value_occurrence
          FROM Product as P join (SELECT i.barcode ,sum(i.quantity) as value_occurrence
                                  from includes as i
                                  group by i.barcode
                                  order by value_occurrence DESC
          ) table2
          on P.barcode=table2.barcode and P.chain_tag=1) table3
          on P.barcode=table3.barcode
          group by P.category_id;

          select  P.category_id, sum(table2.value_occurrence) as product_num
                    from Product as P left join (SELECT i.barcode ,sum(i.quantity) as value_occurrence
                    from includes as i
                    group by i.barcode
                    order by value_occurrence DESC
                    )table2
                    on P.barcode=table2.barcode
                    group by P.category_id;
--get_popular_positions.php
select sells.store_id,sells.corridor,sells.shelf,max(table2.value_occurrence),table2.name,table2.brand,table2.barcode
                from sells inner join (SELECT i.barcode, S.store_id ,count(i.barcode) as value_occurrence, P.name, P.brand
                                      from includes as i,Store as S,Transaction as T,Product as P
                                      where S.store_id=T.store_id and i.transaction_id=T.transaction_id and P.barcode=i.barcode
                                      group by i.barcode,T.store_id
                                      order by value_occurrence DESC) table2
                on sells.barcode=table2.barcode and sells.store_id=table2.store_id
                where table2.barcode is not null
                group by sells.store_id
                order by sells.store_id;
--get_popular_products.php
select T1.Product1,T1.Name1,T1.Brand1,T1.Name2, T1.Brand2 ,T1.Product2,T1.Occurance from
 (select a.barcode as Product1,P1.name as Name1,P1.brand as Brand1,P2.name as Name2, P2.brand as Brand2 ,b.barcode as Product2,Count(*) as Occurance
                from includes as a, includes as b, Product as P1, Product as P2
                where b.barcode=P2.barcode and a.barcode=P1.barcode and a.transaction_id=b.transaction_id and a.barcode<>b.barcode
                group by Product1,Product2
                order by Occurance desc) as T1
                left join (select a.barcode as Product1,P1.name as Name1,P1.brand as Brand1,P2.name as Name2, P2.brand as Brand2 ,b.barcode as Product2,Count(*) as Occurance
                                from includes as a, includes as b, Product as P1, Product as P2
                                where b.barcode=P2.barcode and a.barcode=P1.barcode and a.transaction_id=b.transaction_id and a.barcode<>b.barcode
                                group by Product1,Product2
                                order by Occurance desc) as T2
                on (T1.Occurance<T2.Occurance)
                group by T1.Product1,T1.Product2
                HAVING COUNT(*)<=1
                ORDER by T1.Occurance DESC;
                #Βρίσκει πόσες φορές αγοράστηκε το προιον κάθε μήνα
                select  EXTRACT(MONTH FROM T.date_time)as MONTH,P.barcode, sum(a.quantity),P.category_id
                  from includes as a,Transaction as T,Product as P
                  where a.transcaction_id=T.transcaction_id and a.barcode=P.barcode
                group by MONTH,P.barcode


--ΕΜΦΑΝΙΖΕΙ ΑΝΑ ΚΑΤΗΓΟΡΙΑ ΠΟΣΑ ΠΡΟΙΟΝΤΑ ΠΩΛΟΥΝΤΑΙ ΚΑΘΕ ΠΕΡΙΟΔΟ
SELECT table1.Category as Category,
SUM(CASE WHEN table1.MONTH BETWEEN 3 AND 5 THEN table1.quantity ELSE 0 END) AS SPRING,
SUM(CASE WHEN table1.MONTH BETWEEN 6 AND 8 THEN table1.quantity ELSE 0 END) AS SUMMER,
SUM(CASE WHEN table1.MONTH BETWEEN 9 AND 11 THEN table1.quantity ELSE 0 END) AS AUTUMN,
SUM(CASE WHEN table1.MONTH BETWEEN 1 AND 2  OR table1.MONTH=12 THEN table1.quantity ELSE 0 END) AS WINTER
FROM (select  EXTRACT(MONTH FROM T.date_time)as MONTH,P.barcode, sum(a.quantity) as quantity,P.category_id as Category
from includes as a,Transaction as T,Product as P
where a.transcaction_id=T.transcaction_id and a.barcode=P.barcode
group by MONTH,P.barcode
) table1
GROUP BY table1.Category



--Σύνολο υγιεινών προιόντων ανα αριθμό παιδιών
SELECT COUNT(P.category_id) AS OCCURANCIES,C.number_of_children
FROM Transaction as T,includes as i,Customer as C,Product as P
WHERE i.transcaction_id=T.transcaction_id and C.card_id=T.card_id and P.barcode=i.barcode and P.category_id=(Select category_id from Category where name="Fresh products")
group by C.number_of_children





--Σύνολο προιόντων που έχουν αυτοί που έχουν ο μηδέν παιδία vs περισσότερα #από ένα
SELECT SUM(CASE WHEN C.number_of_children=0 then 1 else 0 END) AS NO_CHILDREN,
SUM(CASE WHEN C.number_of_children>0 then 1 else 0 END) AS MORE_THAN_ONE
FROM Transaction as T,includes as i,Customer as C
WHERE i.transcaction_id=T.transcaction_id and C.card_id=T.card_id




--Σύνολο υγιεινών προιόντων που έχουν αυτοί που έχουν ο μηδέν παιδία vs περισσότερα από ένα
SELECT
SUM(CASE WHEN table1.number_of_children=0 then table1.OCCURANCIES else 0 END) AS NO_CHILDREN_HEALTHY,
SUM(CASE WHEN table1.number_of_children>0 then table1.OCCURANCIES else 0 END) AS MORE_THAN_ONE_CHILDREN_HEALTHY
FROM (SELECT COUNT(P.category_id) AS OCCURANCIES,C.number_of_children
FROM Transaction as T,includes as i,Customer as C,Product as P
WHERE i.transcaction_id=T.transcaction_id and C.card_id=T.card_id and P.barcode=i.barcode and P.category_id=(Select category_id from Category where name="Fresh products")
group by C.number_of_children
)table1;

--get_category_data_view.php

select store_id,category_name, total_quantity
             from sales_per_category;
select * from customer_data where card_id='$Card_id';
