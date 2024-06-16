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

-- DELIMITER $$
-- CREATE PROCEDURE PopulatePhotos()
-- BEGIN
--     DECLARE i INT DEFAULT 1;
--     DECLARE j INT DEFAULT 1;
--     DECLARE photo_url_base VARCHAR(255) DEFAULT 'https://a.com/img/photo';
--     DECLARE photo_url_extension VARCHAR(4);
--     WHILE i <= 100000 DO
--         SET j = 1;
--         WHILE j <= 3 DO
--             SET photo_url_extension = LPAD(FLOOR(RAND() * 100 + 1), 3, '0');
--             INSERT INTO goods_photos (goods_id, photo_url)
--             VALUES (i, CONCAT(photo_url_base, photo_url_extension, '.jpg'));
--             SET j = j + 1;
--         END WHILE;
--         SET i = i + 1;
--     END WHILE;
-- END$$
-- DELIMITER ;

-- CALL PopulatePhotos();
-- DROP PROCEDURE PopulatePhotos;

DELIMITER $$

CREATE PROCEDURE PopulatePhotos(IN photo_urls TEXT)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE j INT DEFAULT 1;
    DECLARE num_photos INT;
    DECLARE chosen_url VARCHAR(255);

    SET num_photos = LENGTH(photo_urls) - LENGTH(REPLACE(photo_urls, ',', '')) + 1;

    WHILE i <= 100000 DO
        SET j = 1;
        WHILE j <= 3 DO
            SET chosen_url = SUBSTRING_INDEX(SUBSTRING_INDEX(photo_urls, ',', FLOOR(RAND() * num_photos) + 1), ',', -1);
            INSERT INTO goods_photos (goods_id, photo_url)
            VALUES (i, chosen_url);
            SET j = j + 1;
        END WHILE;
        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

CALL PopulatePhotos('
    https://47.img.avito.st/432x324/5380857147.jpg,
    https://game-boys.ru/media/cache/f4/fc/f4fcbb8e3177d5681022f06be438e9fb.jpg,
    https://i.pinimg.com/736x/8e/af/87/8eaf87b2bafa3dc7ab6f7b5ec3b91997.jpg,
    https://2016juinternshipseminar.files.wordpress.com/2016/04/9b4f883b5f9125905c740ff0cd4969e7.jpg,
    https://www.dogidogi.ru/images/1/8/kak-opredelit-porodu-23D9782.jpg,
    https://image01.wallrgb.com/cr/nl/c65aec6fbf2a169ddb4682e206f29510_300.jpg,
    https://bilim-all.kz/uploads/images/2016/02/17/400x276/8d6acaa843d6ccfe0fa99d889e8c5f4c.jpg,
    https://avatars.dzeninfra.ru/get-zen-logos/201842/pub_60b74e326271a14fa04fade8_60b74e97de903c65f5505949/xxh,
    https://zastavok.net/ts/eda/1314389518.jpg,
    https://kulturarb.ru/media/zoo/images/880d487dee0bfa295994a63b785d54de_107663090c3b3855b3a9c3163b26d851.png,
    https://cs6.pikabu.ru/avatars/1750/v1750607-583376418.jpg,
    https://0a986293a0.cbaul-cdnwnd.com/e7c22b42ff73b2fff25dde7cae75c29c/200000005-d8cb2d9c4b/fb848bbdc576abfc98dc853118b30543.jpg
    ');
DROP PROCEDURE PopulatePhotos;
