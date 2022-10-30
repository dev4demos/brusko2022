-- users
INSERT INTO user (name, data_create) VALUES ('Maria Anders', '1980-02-05');
INSERT INTO user (name, data_create) VALUES ('billy bob', '1980-01-12');
INSERT INTO user (name, data_create) VALUES ('jane doe', '1985-01-01');
INSERT INTO user (name, data_create) VALUES ('tim cook', '2000-03-01');
INSERT INTO user (name, data_create) VALUES ('Ana Trujillo', '2001-01-02');
INSERT INTO user (name, data_create) VALUES ('dav koz', '1989-08-01');
INSERT INTO user (name, data_create) VALUES ('tony stack', '1990-03-04');
INSERT INTO user (name, data_create) VALUES ('Antonio Moreno', '1995-02-10');

-- comments

INSERT INTO comments (user_id, comment) VALUES (2, 'Superb customer. Great feedback');
INSERT INTO comments (user_id, comment) VALUES (4, 'Good rating from this customer');
INSERT INTO comments (user_id, comment) VALUES (5, 'Chocolate Chip customer');
INSERT INTO comments (user_id, comment) VALUES (7, 'Referral customer');
INSERT INTO comments (user_id, comment) VALUES (8, 'Cake fancy customer');

-- orders

INSERT INTO orders (user_id, price, data_create) VALUES (1, 5200, '1980-01-01');
INSERT INTO orders (user_id, price, data_create) VALUES (3, 7420, '1985-07-01');
INSERT INTO orders (user_id, price, data_create) VALUES (6, 4300, '1989-01-04');
INSERT INTO orders (user_id, price, data_create) VALUES (7, 6000, '1990-03-06');
INSERT INTO orders (user_id, price, data_create) VALUES (8, 1000, '1995-02-01');

INSERT INTO orders (user_id, price, data_create) VALUES (1, 2400, '2000-01-01');
INSERT INTO orders (user_id, price, data_create) VALUES (3, 1200, '2005-07-02');
INSERT INTO orders (user_id, price, data_create) VALUES (1, 4100, '2005-01-01');
INSERT INTO orders (user_id, price, data_create) VALUES (7, 1700, '2010-04-01');
INSERT INTO orders (user_id, price, data_create) VALUES (3, 2300, '2012-01-02');

INSERT INTO orders (user_id, price, data_create) VALUES (1, 6500, '2015-08-01');
INSERT INTO orders (user_id, price, data_create) VALUES (3, 8240, '2015-10-03');
INSERT INTO orders (user_id, price, data_create) VALUES (1, 9670, '2016-09-05');
INSERT INTO orders (user_id, price, data_create) VALUES (7, 1320, '2017-01-02');
INSERT INTO orders (user_id, price, data_create) VALUES (8, 1572, '2019-05-07');
