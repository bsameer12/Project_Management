--drop customer audit table
DROP TABLE CUSTOMER_AUDIT CASCADE CONSTRAINTS;
--create customer audit table 
CREATE TABLE CUSTOMER_AUDIT (
    audit_id NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    audit_date TIMESTAMP,
    audit_user VARCHAR2(20),
    audit_operation VARCHAR2(20),
    customer_id INTEGER,
    customer_date_joined DATE,
    verification_code INTEGER,
    date_updated DATE,
    verified_customer CHAR(1),
    user_id INTEGER,
    profile_picture BLOB,
    CONSTRAINT FK_CUSTOMER_AUDIT_ID FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id),
    CONSTRAINT FK_CUSTOMER_AUDIT_USER_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id)
);

--drop trigger 
DROP TRIGGER TRIG_AUDIT_CUSTOMER;
--create trigger 
CREATE OR REPLACE TRIGGER TRIG_AUDIT_CUSTOMER
AFTER INSERT OR UPDATE OR DELETE ON CUSTOMER
FOR EACH ROW
DECLARE
    v_audit_operation VARCHAR2(20);
BEGIN
    IF INSERTING THEN
        v_audit_operation := 'INSERT';
    ELSIF UPDATING THEN
        v_audit_operation := 'UPDATE';
    ELSIF DELETING THEN
        v_audit_operation := 'DELETE';
    END IF;

    INSERT INTO CUSTOMER_AUDIT (
        audit_date,
        audit_user,
        audit_operation,
        customer_id,
        customer_date_joined,
        verification_code,
        date_updated,
        verified_customer,
        user_id,
        profile_picture
    ) VALUES (
        SYSTIMESTAMP,
        USER,
        v_audit_operation,
        :NEW.customer_id,
        :NEW.customer_date_joined,
        :NEW.verification_code,
        :NEW.date_updated,
        :NEW.verified_customer,
        :NEW.user_id,
        :NEW.profile_picture
    );
END;
/

--drop table cart audit
DROP TABLE CART_AUDIT CASCADE CONSTRAINTS;
--create table cart audit
CREATE TABLE CART_AUDIT (
    audit_id NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    audit_date TIMESTAMP,
    audit_user VARCHAR2(20),
    audit_operation VARCHAR2(20),
    cart_id INTEGER,
    cart_item VARCHAR2(100),
    total_price INTEGER,
    customer_id INTEGER,
    order_product_id INTEGER,
    CONSTRAINT FK_CART_AUDIT_CUSTOMER_ID FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id),
    CONSTRAINT FK_CART_AUDIT_ORDER_PRODUCT_ID FOREIGN KEY (order_product_id) REFERENCES ORDER_PRODUCT(order_product_id)
);
--drop trigger 
DROP TRIGGER TRIG_AUDIT_CART;
--create trigger 
CREATE OR REPLACE TRIGGER TRIG_AUDIT_CART
AFTER INSERT OR UPDATE OR DELETE ON CART
FOR EACH ROW
DECLARE
    v_audit_operation VARCHAR2(20);
BEGIN
    IF INSERTING THEN
        v_audit_operation := 'INSERT';
    ELSIF UPDATING THEN
        v_audit_operation := 'UPDATE';
    ELSIF DELETING THEN
        v_audit_operation := 'DELETE';
    END IF;

    INSERT INTO CART_AUDIT (
        audit_date,
        audit_user,
        audit_operation,
        cart_id,
        cart_item,
        total_price,
        customer_id,
        order_product_id
    ) VALUES (
        SYSTIMESTAMP,
        USER,
        v_audit_operation,
        :NEW.cart_id,
        :NEW.cart_item,
        :NEW.total_price,
        :NEW.customer_id,
        :NEW.order_product_id
    );
END;
/

--drop table trader audit
DROP TABLE TRADER_AUDIT CASCADE CONSTRAINTS;
--create table trader audit
CREATE TABLE TRADER_AUDIT (
    audit_id NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    audit_date TIMESTAMP,
    audit_user VARCHAR2(20),
    audit_operation VARCHAR2(20),
    trader_id INTEGER,
    trader_type VARCHAR2(100),
    shop_name VARCHAR2(100),
    verification_status CHAR(1),
    user_id INTEGER,
    profile_picture BLOB,
    CONSTRAINT FK_TRADER_AUDIT_USER_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id)
);

--drop trigger 
DROP TRIGGER TRIG_AUDIT_TRADER;
--create trigger 
CREATE OR REPLACE TRIGGER TRIG_AUDIT_TRADER
AFTER INSERT OR UPDATE OR DELETE ON TRADER
FOR EACH ROW
DECLARE
    v_audit_operation VARCHAR2(20);
BEGIN
    IF INSERTING THEN
        v_audit_operation := 'INSERT';
    ELSIF UPDATING THEN
        v_audit_operation := 'UPDATE';
    ELSIF DELETING THEN
        v_audit_operation := 'DELETE';
    END IF;

    INSERT INTO TRADER_AUDIT (
        audit_date,
        audit_user,
        audit_operation,
        trader_id,
        trader_type,
        shop_name,
        verification_status,
        user_id,
        profile_picture
    ) VALUES (
        SYSTIMESTAMP,
        USER,
        v_audit_operation,
        :NEW.trader_id,
        :NEW.trader_type,
        :NEW.shop_name,
        :NEW.verification_status,
        :NEW.user_id,
        :NEW.profile_picture
    );
END;
/

--drop table wishlist audit
DROP TABLE WISHLIST_AUDIT CASCADE CONSTRAINTS;
--create table wishlist audit
CREATE TABLE WISHLIST_AUDIT (
    audit_id NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    audit_date TIMESTAMP,
    audit_user VARCHAR2(20),
    audit_operation VARCHAR2(20),
    wishlist_id INTEGER,
    wishlist_created_date DATE,
    wishlist_updated_date DATE,
    wishlist_item VARCHAR2(100),
    customer_id INTEGER,
    CONSTRAINT FK_WISHLIST_AUDIT_CUSTOMER_ID FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id)
);
--drop trigger 
DROP TRIGGER TRIG_AUDIT_WISHLIST;
--create trigger 
CREATE OR REPLACE TRIGGER TRIG_AUDIT_WISHLIST
AFTER INSERT OR UPDATE OR DELETE ON WISHLIST
FOR EACH ROW
DECLARE
    v_audit_operation VARCHAR2(20);
BEGIN
    IF INSERTING THEN
        v_audit_operation := 'INSERT';
    ELSIF UPDATING THEN
        v_audit_operation := 'UPDATE';
    ELSIF DELETING THEN
        v_audit_operation := 'DELETE';
    END IF;

    INSERT INTO WISHLIST_AUDIT (
        audit_date,
        audit_user,
        audit_operation,
        wishlist_id,
        wishlist_created_date,
        wishlist_updated_date,
        wishlist_item,
        customer_id
    ) VALUES (
        SYSTIMESTAMP,
        USER,
        v_audit_operation,
        :NEW.wishlist_id,
        :NEW.wishlist_created_date,
        :NEW.wishlist_updated_date,
        :NEW.wishlist_item,
        :NEW.customer_id
    );
END;
/

--drop table user audit
DROP TABLE HUDDER_USER_AUDIT cascade constraints;
--create tabel user audit
CREATE TABLE HUDDER_USER_AUDIT(
    audit_id NUMBER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    user_id INTEGER,
    first_name VARCHAR2(100),
    last_name VARCHAR2(100),
    user_address VARCHAR2(100),
    user_email VARCHAR2(100),
    user_age INTEGER,
    user_gender CHARACTER(8),
    user_password VARCHAR2(100),
    user_profile_picture BLOB,
    user_type VARCHAR2(10),
    user_contact_no INTEGER
);

--drop trigger
DROP TRIGGER TRG_HUDDER_USER_AUDIT;
COMMIT;
--create trigger
CREATE OR REPLACE TRIGGER TRG_HUDDER_USER_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON HUDDER_USER
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF updating THEN
    v_trg_action := 'UPDATE';
  ELSIF deleting THEN
    v_trg_action := 'DELETE';
  ELSIF inserting THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO HUDDER_USER_AUDIT
    (audit_date, audit_user, audit_operation, user_id, first_name, last_name, user_address, user_email, user_age, user_gender, user_password, user_profile_picture, user_type, user_contact_no)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action,:OLD.user_id,:OLD.first_name,:OLD.last_name,:OLD.user_address,:OLD.user_email, :OLD.user_age, :OLD.user_gender, :OLD.user_password, :OLD.user_profile_picture, :OLD.user_type, :OLD.user_contact_no);
  ELSE
    INSERT INTO HUDDER_USER_AUDIT
    (audit_date, audit_user, audit_operation, user_id, first_name, last_name, user_address, user_email, user_age, user_gender, user_password, user_profile_picture, user_type, user_contact_no)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action,:NEW.user_id, :NEW.first_name, :NEW.last_name, :NEW.user_address, :NEW.user_email, :NEW.user_age, :NEW.user_gender, :NEW.user_password, :NEW.user_profile_picture, :NEW.user_type, :NEW.user_contact_no);
  END IF;
END TRG_HUDDER_USER_AUDIT;
/
--drop table shop audit
DROP TABLE SHOP_AUDIT cascade constraints;
--create table shop audit
CREATE TABLE SHOP_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    shop_id INTEGER,
    shop_name VARCHAR2(100),
    shop_description VARCHAR2(500),
    user_id INTEGER,
    verified_shop NUMBER(1),
    CONSTRAINT FK_SHOP_USER_AUDIT_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id)
);

--drop trigger 
DROP TRIGGER TRG_SHOP_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_SHOP_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON SHOP
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO SHOP_AUDIT
    (audit_date, audit_user, audit_operation, shop_id, shop_name, user_id, verified_shop)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.shop_id, :OLD.shop_name, :OLD.user_id, :OLD.verified_shop);
  ELSE
    INSERT INTO SHOP_AUDIT
    (audit_date, audit_user, audit_operation, shop_id, shop_name, user_id, verified_shop)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.shop_id, :NEW.shop_name, :NEW.user_id, :NEW.verified_shop);
  END IF;
END;
/
--drop table review audit
DROP TABLE REVIEW_AUDIT cascade constraints;
--create table review audit
CREATE TABLE REVIEW_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    review_id INTEGER,
    review_date DATE,
    review_score INTEGER,
    feedback VARCHAR2(500),
    product_id INTEGER,
    user_id INTEGER,
    CONSTRAINT FK_REVIEW_USER_AUDIT_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id),
    CONSTRAINT FK_REVIEW_PRODUCT_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
);

--drop trigger 
DROP TRIGGER TRG_REVIEW_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_REVIEW_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON REVIEW
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO REVIEW_AUDIT
    (audit_date, audit_user, audit_operation, review_id, review_date, review_score, feedback, product_id, user_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.review_id, :OLD.review_date, :OLD.review_score, :OLD.feedback, :OLD.product_id, :OLD.user_id);
  ELSE
    INSERT INTO REVIEW_AUDIT
     (audit_date, audit_user, audit_operation, review_id, review_date, review_score, feedback, product_id, user_id)
    VALUES
     (SYSTIMESTAMP, USER, v_trg_action, :NEW.review_id, :NEW.review_date, :NEW.review_score, :NEW.feedback, :NEW.product_id, :NEW.user_id);
  END IF;
END;
/

--drop table product audit
DROP TABLE PRODUCT_AUDIT cascade constraints;
--create table product audit
CREATE TABLE PRODUCT_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    product_id INTEGER,
    product_name VARCHAR2(100),
    product_description VARCHAR2(500),
    product_price INTEGER,
    product_quantity INTEGER,
    stock_available VARCHAR2(100),
    min_order INTEGER,
    max_order INTEGER,
    allergy_information VARCHAR2(100),
    product_picture BLOB,
    product_added_date DATE,
    product_update_date DATE,
    category_id INTEGER,
    user_id INTEGER,
    CONSTRAINT FK_CATEGORY_AUDIT_ID FOREIGN KEY (category_id) REFERENCES PRODUCT_CATEGORY(category_id),
    CONSTRAINT FK_USER_PRODUCT_AUDIT_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id)
);

--drop trigger 
DROP TRIGGER TRG_PRODUCT_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_PRODUCT_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON PRODUCT
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO PRODUCT_AUDIT
    (audit_date, audit_user, audit_operation, product_id, product_name, product_description, product_price, product_quantity, stock_available, min_order, max_order, allergy_information, product_picture, product_added_date, product_update_date, category_id, user_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.product_id, :OLD.product_name, :OLD.product_description, :OLD.product_price, :OLD.product_quantity, :OLD.stock_available, :OLD.min_order, :OLD.max_order, :OLD.allergy_information, :OLD.product_picture, :OLD.product_added_date, :OLD.product_update_date, :OLD.category_id, :OLD.user_id);
  ELSE
    INSERT INTO PRODUCT_AUDIT
     (audit_date, audit_user, audit_operation, product_id, product_name, product_description, product_price, product_quantity, stock_available, min_order, max_order, allergy_information, product_picture, product_added_date, product_update_date, category_id, user_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.product_id, :NEW.product_name, :NEW.product_description, :NEW.product_price, :NEW.product_quantity, :NEW.stock_available, :NEW.min_order, :NEW.max_order, :NEW.allergy_information, :NEW.product_picture, :NEW.product_added_date, :NEW.product_update_date, :NEW.category_id, :NEW.user_id);
  END IF;
END;
/
--drop table payment audit
DROP TABLE PAYMENT_AUDIT cascade constraints;
--create table payment audit
CREATE TABLE PAYMENT_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    payment_id INTEGER,
    payment_date DATE,
    payment_type VARCHAR2(100),
    payment_amount INTEGER,
    customer_id INTEGER,
    order_product_id INTEGER,
    CONSTRAINT FK_PAYMENT_CUSTOMER_AUDIT_ID FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id),
    CONSTRAINT FK_PAYMENT_ORDER_PRODUCT_AUDIT_ID FOREIGN KEY (order_product_id) REFERENCES ORDER_PRODUCT(order_product_id)
);

--drop trigger 
DROP TRIGGER TRG_PAYMENT_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_PAYMENT_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON PAYMENT
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO PAYMENT_AUDIT
    (audit_date, audit_user, audit_operation, payment_id, payment_date, payment_type, payment_amount, customer_id, order_product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.payment_id, :OLD.payment_date, :OLD.payment_type, :OLD.payment_amount, :OLD.customer_id, :OLD.order_product_id);
  ELSE
    INSERT INTO PAYMENT_AUDIT
    (audit_date, audit_user, audit_operation, payment_id, payment_date, payment_type, payment_amount, customer_id, order_product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.payment_id, :NEW.payment_date, :NEW.payment_type, :NEW.payment_amount, :NEW.customer_id, :NEW.order_product_id);
  END IF;
END;
/

--drop table collection slot audit
DROP TABLE COLLECTION_SLOT_AUDIT cascade constraints;
--create table collection slot audit
CREATE TABLE COLLECTION_SLOT_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    slot_id INTEGER,
    slot_date DATE,
    slot_time TIMESTAMP,
    slot_day VARCHAR2(10),
    order_product_id INTEGER,
    CONSTRAINT FK_ORDER_PRODUCT_AUDIT_ID FOREIGN KEY (order_product_id) REFERENCES ORDER_PRODUCT(order_product_id)
);

--drop trigger 
DROP TRIGGER TRG_COLLECTION_SLOT_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_COLLECTION_SLOT_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON COLLECTION_SLOT
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO COLLECTION_SLOT_AUDIT
    (audit_date, audit_user, audit_operation, slot_id, slot_date, slot_time, slot_day, order_product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.slot_id, :OLD.slot_date, :OLD.slot_time, :OLD.slot_day, :OLD.order_product_id);
  ELSE
    INSERT INTO COLLECTION_SLOT_AUDIT
    (audit_date, audit_user, audit_operation, slot_id, slot_date, slot_time, slot_day, order_product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.slot_id, :OLD.slot_date, :OLD.slot_time, :OLD.slot_day, :OLD.order_product_id);
  END IF;
END;
/

--drop table report audit
DROP TABLE REPORT_AUDIT cascade constraints;
--create table report audit
CREATE TABLE REPORT_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    report_id INTEGER,
    report_type VARCHAR2(100),
    report_name VARCHAR2(100),
    report_date DATE,
    product_id INTEGER,
    user_id INTEGER,
    CONSTRAINT FK_REPORT_USER_AUDIT_ID FOREIGN KEY (user_id) REFERENCES HUDDER_USER(user_id),
    CONSTRAINT FK_REPORT_PRODUCT_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
);

--drop trigger 
DROP TRIGGER TRG_REPORT_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_REPORT_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON REPORT
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO REPORT_AUDIT
    (audit_date, audit_user, audit_operation, report_id, report_type, report_name, report_date, product_id, user_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.report_id, :OLD.report_type, :OLD.report_name, :OLD.report_date, :OLD.product_id, :OLD.user_id);
  ELSE
    INSERT INTO REPORT_AUDIT
    (audit_date, audit_user, audit_operation, report_id, report_type, report_name, report_date, product_id, user_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.report_id, :NEW.report_type, :NEW.report_name, :NEW.report_date, :NEW.product_id, :NEW.user_id);
  END IF;
END;
/

--drop table discount audit
DROP TABLE DISCOUNT_AUDIT cascade constraints;
--create table discount audit
CREATE TABLE DISCOUNT_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    discount_id INTEGER,
    discount_occassion VARCHAR2(100),
    discount_percent VARCHAR2(10),
    product_id INTEGER,
    CONSTRAINT FK_PRODUCT_DISCOUNT_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
);

--drop trigger 
DROP TRIGGER TRG_DISCOUNT_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_DISCOUNT_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON DISCOUNT
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO DISCOUNT_AUDIT
    (audit_date, audit_user, audit_operation, discount_id, discount_occassion, discount_percent, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.discount_id, :OLD.discount_occassion, :OLD.discount_percent, :OLD.product_id );
  ELSE
    INSERT INTO DISCOUNT_AUDIT
    (audit_date, audit_user, audit_operation, discount_id, discount_occassion, discount_percent, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.discount_id, :NEW.discount_occassion, :NEW.discount_percent, :NEW.product_id );
  END IF;
END;
/
--drop table order details audit 
DROP TABLE ORDER_DETAILS_AUDIT cascade constraints;
--create table order details audit 
CREATE TABLE ORDER_DETAILS_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    order_date DATE,
    order_time TIMESTAMP,
    order_product_id INTEGER,
    product_id INTEGER,
    CONSTRAINT FK_ORDER_PRODUCT_DETAILS_AUDIT_ID FOREIGN KEY (order_product_id) REFERENCES ORDER_PRODUCT(order_product_id),
    CONSTRAINT FK_PRODUCT_DETAILS_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
);

--drop trigger 
DROP TRIGGER TRG_ORDER_DETAILS_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_ORDER_DETAILS_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON ORDER_DETAILS
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO ORDER_DETAILS_AUDIT
    (audit_date, audit_user, audit_operation, order_date, order_time, order_product_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.order_date, :OLD.order_time, :OLD.order_product_id, :OLD.product_id);
  ELSE
    INSERT INTO ORDER_DETAILS_AUDIT
    (audit_date, audit_user, audit_operation, order_date, order_time, order_product_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.order_date, :NEW.order_time, :NEW.order_product_id, :NEW.product_id);
  END IF;
END;
/
--drop table cart item audit
DROP TABLE CART_ITEM_AUDIT cascade constraints;
--create cart item audit
CREATE TABLE CART_ITEM_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    no_of_products INTEGER,
    cart_id INTEGER,
    product_id INTEGER,
    CONSTRAINT FK_CART_ITEM_AUDIT_ID FOREIGN KEY (cart_id) REFERENCES CART(cart_id),
    CONSTRAINT FK_PRODUCT_CART_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
);

--drop trigger 
DROP TRIGGER TRG_CART_ITEM_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_CART_ITEM_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON CART_ITEM
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO CART_ITEM_AUDIT
    (audit_date, audit_user, audit_operation, no_of_products, cart_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.no_of_products, :OLD.cart_id, :OLD.product_id);
  ELSE
    INSERT INTO CART_ITEM_AUDIT
   (audit_date, audit_user, audit_operation, no_of_products, cart_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.no_of_products, :NEW.cart_id, :NEW.product_id);
  END IF;
END;
/
--drop table wishlist item audit
DROP TABLE WISHLIST_ITEM_AUDIT cascade constraints;
--create table wishlist item audit 
CREATE TABLE WISHLIST_ITEM_AUDIT(
    audit_id INTEGER,
    audit_user VARCHAR2(100),
    audit_date DATE,
    audit_operation VARCHAR2(100),
    wishlist_id INTEGER,
    product_id INTEGER,
    CONSTRAINT FK_WISHLIST_ITEM_AUDIT_ID FOREIGN KEY (wishlist_id) REFERENCES WISHLIST(wishlist_id),
    CONSTRAINT FK_PRODUCT_WISHLIST_AUDIT_ID FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)

);

--drop trigger 
DROP TRIGGER TRG_WISHLIST_ITEM_AUDIT;
COMMIT;
--create trigger 
CREATE OR REPLACE TRIGGER TRG_WISHLIST_ITEM_AUDIT
AFTER INSERT OR DELETE OR UPDATE ON WISHLIST_ITEM
FOR EACH ROW
DECLARE
  v_trg_action VARCHAR2(6);
BEGIN
  IF UPDATING THEN
    v_trg_action := 'UPDATE';
  ELSIF DELETING THEN
    v_trg_action := 'DELETE';
  ELSIF INSERTING THEN
    v_trg_action := 'INSERT';
  ELSE
    v_trg_action := NULL;
  END IF;
  
  IF v_trg_action IN ('DELETE', 'UPDATE') THEN
    INSERT INTO WISHLIST_ITEM_AUDIT
    (audit_date, audit_user, audit_operation, wishlist_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :OLD.wishlist_id, :OLD.product_id);
  ELSE
    INSERT INTO WISHLIST_ITEM_AUDIT
   (audit_date, audit_user, audit_operation, wishlist_id, product_id)
    VALUES
    (SYSTIMESTAMP, USER, v_trg_action, :NEW.wishlist_id, :NEW.product_id);
  END IF;
END;
/





















