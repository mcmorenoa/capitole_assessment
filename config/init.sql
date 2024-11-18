CREATE TABLE category
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL UNIQUE,
    discount INT DEFAULT NULL
);

INSERT INTO category (name, discount)
VALUES ('boots', 30);

INSERT INTO category (name)
VALUES ('sandals');

INSERT INTO category (name)
VALUES ('sneakers');


CREATE TABLE product
(
    sku         VARCHAR(6)     NOT NULL UNIQUE PRIMARY KEY,
    name        VARCHAR(255)   NOT NULL,
    category_id INT            NOT NULL,
    price       DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
);

CREATE TABLE product_discount
(
    sku      VARCHAR(6) NOT NULL UNIQUE PRIMARY KEY,
    discount INT        NOT NULL,
    FOREIGN KEY (sku) REFERENCES product (sku) ON DELETE CASCADE
);

INSERT INTO product (sku, name, category_id, price)
VALUES ('000001', 'BV Lean leather ankle boots', 1, 89000),
       ('000002', 'BV Lean leather ankle boots', 1, 99000),
       ('000003', 'Ashlington leather ankle boots', 1, 71000),
       ('000004', 'Naima embellished suede sandals', 2, 79500),
       ('000005', 'Nathane leather sneakers', 3, 59000);

INSERT INTO product_discount (sku, discount) VALUES ('000003', 15);
