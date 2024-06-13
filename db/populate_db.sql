USE shop;

DELIMITER $$
CREATE PROCEDURE PopulateCategories()
BEGIN
    DECLARE i INT DEFAULT 1;
    WHILE i <= 1000 DO
        INSERT INTO goods_category (name, alias) VALUES (CONCAT('Category ', i), CONCAT('category-', i));
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;

CALL PopulateCategories();
DROP PROCEDURE PopulateCategories;

DELIMITER $$
CREATE PROCEDURE PopulateGoods()
BEGIN
    DECLARE i INT DEFAULT 1;
    WHILE i <= 100000 DO
        INSERT INTO goods (name, alias, article, price, quantity, category_id)
        VALUES (CONCAT('Product ', i), CONCAT('product-', i), CONCAT('SKU', LPAD(i, 6, '0')), ROUND(RAND() * 9900 + 100, 2), FLOOR(RAND() * 100 + 1), FLOOR(RAND() * 1000 + 1));
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;

CALL PopulateGoods();
DROP PROCEDURE PopulateGoods;

DELIMITER $$
CREATE PROCEDURE PopulatePhotos()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE j INT DEFAULT 1;
    DECLARE photo_url_base VARCHAR(255) DEFAULT 'https://a.com/img/photo';
    DECLARE photo_url_extension VARCHAR(4);
    WHILE i <= 100000 DO
        SET j = 1;
        WHILE j <= 3 DO
            SET photo_url_extension = LPAD(FLOOR(RAND() * 100 + 1), 3, '0');
            INSERT INTO goods_photos (goods_id, photo_url)
            VALUES (i, CONCAT(photo_url_base, photo_url_extension, '.jpg'));
            SET j = j + 1;
        END WHILE;
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;

CALL PopulatePhotos();
DROP PROCEDURE PopulatePhotos;
