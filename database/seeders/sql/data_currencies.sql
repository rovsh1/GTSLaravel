INSERT INTO `r_currencies` (`id`, `code_num`, `code_char`, `sign`)
VALUES (1, 860, 'UZS', 'сўм'),
       (2, 643, 'RUB', '₽'),
       (3, 840, 'USD', '$'),
       (4, 398, 'KZT', 'тңг'),
       (10, 978, 'EUR', '€');

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