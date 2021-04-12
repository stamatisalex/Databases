
CREATE VIEW customer_data AS
    select Customer.name, Customer.card_id, Customer.birth_date, Customer.age, Customer.email, Customer.sex, Customer.marriage_status, Customer.number_of_children, Customer.pet, Customer.city, Customer.street, Customer.number, Customer.postal_code, Customer_phone_number.phone_number
    from Customer, Customer_phone_number
    where Customer.card_id=Customer_phone_number.card_id;
CREATE VIEW sales_per_category AS
    select Q.store_id, Category.name as category_name, Q.total_quantity
    from Category, ( select sum(includes.quantity) as total_quantity, Product.category_id as category_id, Transaction.store_id as store_id
                                    from includes, Product, Transaction
                                    where includes.barcode=Product.barcode and includes.transaction_id=Transaction.transaction_id
                                    group by Product.category_id, Transaction.store_id) as Q
    where Q.category_id=Category.category_id
    order by Q.store_id, Category.name
