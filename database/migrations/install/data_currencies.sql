INSERT INTO `r_currencies` (`id`, `code_num`, `code_char`, `sign`, `rate`)
VALUES (1, 860, 'UZS', 'сўм', '1.00'),
       (2, 643, 'RUB', '₽', '149.33'),
       (3, 840, 'USD', '$', '11417.98'),
       (4, 398, 'KZT', 'тңг', '24.64'),
       (10, 978, 'EUR', '€', '12123.61');

INSERT INTO `r_currencies_translation` (`translatable_id`, `language`, `name`)
VALUES (1, 'en', 'Uzbekistan sum'),
       (1, 'ru', 'Узбекский сум'),
       (2, 'en', 'Russian Ruble'),
       (2, 'ru', 'Российский рубль'),
       (3, 'en', 'Dollar USA'),
       (3, 'ru', 'Доллар США'),
       (4, 'en', 'Tenge'),
       (4, 'ru', 'Казахстанский тенге'),
       (10, 'en', 'Euro'),
       (10, 'ru', 'Евро');