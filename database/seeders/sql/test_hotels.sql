INSERT INTO hotels (id, supplier_id, city_id, type_id, currency, status, visibility, rating, name, zipcode, address, address_en, address_lat, address_lon, city_distance, markup_settings, time_settings, created_at, updated_at, deleted_at) VALUES
    (565, 32, 1, 10, 'UZS', 2, 1, 4.00, 'test_hotel', '100353', 'Ташкент', 'Tashkent', 41.29949580, 69.24007340, 0, '{"id":565,"vat":15,"touristTax":10,"earlyCheckIn":[{"timePeriod":{"from":"07:00","to":"09:00"},"priceMarkup":15}],"lateCheckOut":[{"timePeriod":{"from":"12:00","to":"14:00"},"priceMarkup":30}],"cancelPeriods":[{"period":"2023-12-31T19:00:00+00:00\/P1D\/2024-12-30T19:00:00+00:00","noCheckInMarkup":{"percent":10,"cancelPeriodType":2},"dailyMarkups":[{"percent":5,"cancelPeriodType":1,"daysCount":2}]}]}', '{"checkInAfter":"09:00","checkOutBefore":"12:00","breakfastPeriod":{"from":"08:00","to":"09:00"}}', '2024-02-07 11:15:20', '2024-02-08 08:31:47', NULL);

INSERT INTO `hotel_rooms` (id, hotel_id, type_id, rooms_number, guests_count, square, position, markup_settings)
VALUES (2605, 565, 1, 50, 1, 30, 0, NULL),
     (2606, 565, 2, 30, 2, 40, 0, NULL);

INSERT INTO `hotel_rooms_translation` (translatable_id, language, name, text)
VALUES
      (2605, 'en', 'Standard room Single use', NULL),
      (2605, 'ru', 'Стандартный одноместный', '<p>одноместный номер на одного человека</p>'),
      (2605, 'uz', 'Standard room Single use', NULL),
      (2606, 'en', 'Standart Room Double or Twin  use', NULL),
      (2606, 'ru', 'Стандартный двухместный', '<p>двухместный номер для двоих человек</p>'),
      (2606, 'uz', 'Standart Room Double or Twin  use', NULL);

INSERT INTO hotel_administrators (hotel_id, status, presentation, login, password, remember_token, email, phone) VALUES (565, 1, 'test20','test20','$2y$10$AGAG.HXuzLOfVyct7mMDm.S99p6hbFg7gw3jTgEQhV9xWjfkEVGsy',null,'test202020@mail.ru', '595695');

INSERT INTO `hotel_contracts` (`id`, `hotel_id`, `status`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES (10002, 565, 1, '2024-01-01', '2024-12-31', '2024-02-08 08:26:43', '2024-02-08 08:26:43');

INSERT INTO `hotel_seasons` (`id`, `contract_id`, `name`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES
       (1922, 10002, 'LOW', '2024-02-01', '2024-05-31', NULL, NULL),
       (1923, 10002, 'HIGH', '2024-06-01', '2024-12-31', NULL, NULL);

INSERT INTO r_hotel_meal_plans (id, type) VALUES (1, 1);
INSERT INTO r_hotel_meal_plans_translation (translatable_id, language, name) VALUES (1, 'ru', 'Без питания');

INSERT INTO `hotel_price_rates` (id, hotel_id, meal_plan_id)
VALUES
    (524, 565, 1),
    (525, 565, 1);

INSERT INTO `hotel_price_rate_rooms` (rate_id, room_id)
VALUES
    (524, 2605),
    (525, 2605),
    (524, 2606),
    (525, 2606);

INSERT INTO `hotel_price_rates_translation` (translatable_id, language, name, description)
VALUES
    (524, 'en', 'с завтраком', 'с завтраком'),
    (524, 'ru', 'с завтраком', 'с завтраком'),
    (524, 'uz', 'с завтраком', 'с завтраком'),
    (525, 'en', 'без завтрака', 'без завтрака'),
    (525, 'ru', 'без завтрака', 'без завтрака'),
    (525, 'uz', 'без завтрака', 'без завтрака');

INSERT INTO `hotel_price_groups` (`id`, `rate_id`, `guests_count`, `is_resident`)
VALUES (1, 251, 1, 1),
       (2, 251, 1, 0),
       (4, 251, 2, 0),
       (3, 251, 2, 1),
       (6, 251, 3, 0),
       (5, 251, 3, 1),
       (8, 317, 1, 0),
       (7, 317, 1, 1),
       (9, 317, 2, 1),
       (10, 317, 2, 0),
       (23, 524, 1, 0),
       (22, 524, 1, 1),
       (27, 524, 2, 0),
       (26, 524, 2, 1),
       (25, 525, 1, 0),
       (24, 525, 1, 1),
       (29, 525, 2, 0),
       (28, 525, 2, 1);

INSERT INTO `hotel_season_prices` (`id`, `season_id`, `group_id`, `room_id`, `price`)
VALUES
    (58, 1922, 22, 2605, 500000.00),
    (59, 1923, 22, 2605, 700000.00),
    (60, 1922, 23, 2605, 650000.00),
    (61, 1923, 23, 2605, 850000.00),
    (62, 1922, 24, 2605, 600000.00),
    (63, 1922, 25, 2605, 700000.00),
    (64, 1923, 24, 2605, 900000.00),
    (65, 1923, 25, 2605, 1000000.00),
    (66, 1922, 22, 2606, 1200000.00),
    (67, 1922, 26, 2606, 1500000.00),
    (68, 1923, 22, 2606, 2000000.00),
    (69, 1923, 26, 2606, 2500000.00),
    (70, 1922, 23, 2606, 1300000.00),
    (71, 1922, 27, 2606, 1600000.00),
    (72, 1923, 23, 2606, 2200000.00),
    (73, 1923, 27, 2606, 2700000.00),
    (74, 1922, 24, 2606, 500000.00),
    (75, 1922, 25, 2606, 600000.00),
    (76, 1922, 28, 2606, 700000.00),
    (77, 1922, 29, 2606, 800000.00),
    (78, 1923, 24, 2606, 900000.00),
    (79, 1923, 25, 2606, 1000000.00),
    (80, 1923, 28, 2606, 1000000.00),
    (81, 1923, 29, 2606, 1100000.00);


#Traveline
INSERT INTO suppliers (id, name, type_id, currency) VALUES (556, 'Traveline', 334, 'UZS');

INSERT INTO supplier_cities (city_id, supplier_id) VALUES (1, 556);

INSERT INTO hotels (id, supplier_id, city_id, type_id, currency, status, visibility, rating, name, zipcode, address, address_en, address_lat, address_lon, city_distance, markup_settings, time_settings, created_at, updated_at) VALUES (564,556,1,10,'UZS',2,2,3,'api_hotel',null,'Tashkent','Tashkent','41.2994958','69.2400734',0,'{"id":564,"vat":12,"touristTax":15,"earlyCheckIn":[{"timePeriod":{"from":"05:00","to":"14:00"},"priceMarkup":50},{"timePeriod":{"from":"00:00","to":"05:00"},"priceMarkup":100}],"lateCheckOut":[{"timePeriod":{"from":"12:00","to":"18:00"},"priceMarkup":50},{"timePeriod":{"from":"18:00","to":"24:00"},"priceMarkup":100}],"cancelPeriods":[{"period":"2022-10-01T00:00:00+00:00\/P1D\/2023-12-31T00:00:00+00:00","noCheckInMarkup":{"percent":100,"cancelPeriodType":1},"dailyMarkups":[{"percent":100,"cancelPeriodType":1,"daysCount":2}]},{"period":"2023-12-31T21:00:00+00:00\/P1D\/2024-12-30T21:00:00+00:00","noCheckInMarkup":{"percent":100,"cancelPeriodType":1},"dailyMarkups":[{"percent":100,"cancelPeriodType":1,"daysCount":2}]}]}',null,'2024-01-23 07:14:15','2024-01-23 07:15:37');

INSERT INTO `hotel_rooms` (id, hotel_id, type_id, rooms_number, guests_count) VALUES
    (2603,	564, 1,	50,	1),
    (2604,	564, 2,	30,	2);

INSERT INTO `hotel_rooms_translation` (`translatable_id`, `language`, `name`, `text`) VALUES
    (2603, 'en', 'Standard room Single use', NULL),
    (2603, 'ru', 'Стандартный одноместный', NULL),
    (2603, 'uz', 'Standard room Single use', NULL),
    (2604, 'en', 'Standard room Double use', NULL),
    (2604, 'ru', 'Стандартный двухместный', NULL),
    (2604, 'uz', 'Standard room Double use', NULL);

INSERT INTO hotel_contracts (id, hotel_id, status, date_start, date_end, created_at, updated_at) VALUES (10000, 564,1,'2024-01-01','2024-12-31','2024-01-23 07:15:25','2024-01-23 07:15:25');

INSERT INTO `hotel_seasons` (`id`, `contract_id`, `name`, `date_start`, `date_end`, `created_at`, `updated_at`) VALUES
                                                                                                                    (1917, 10000, 'LOW', '2024-01-01', '2024-05-31', NULL, NULL),
                                                                                                                    (1918, 10000, 'HIGH', '2024-06-01', '2024-12-31', NULL, NULL);

INSERT INTO hotel_price_rates (id, hotel_id, meal_plan_id) VALUES (522, 564, 1), (523, 564, 1);
INSERT INTO hotel_price_rates_translation (translatable_id, language, name, description) VALUES (522, 'ru', 'PROMO', ''), (523, 'ru', 'WEB', '');

INSERT INTO hotel_price_rate_rooms (rate_id, room_id) VALUES
    (522, 2603),
    (523, 2603),
    (522, 2604);

INSERT INTO `hotel_price_groups` (`id`, `rate_id`, `guests_count`, `is_resident`) VALUES
    (15, 522, 1, 0),
    (14, 522, 1, 1),
    (20, 522, 2, 0),
    (19, 522, 2, 1),
    (17, 523, 1, 0),
    (16, 523, 1, 1);

INSERT INTO `hotel_season_prices` (`id`, `season_id`, `group_id`, `room_id`, `price`) VALUES
    (40, 1917, 14, 2603, 500000.00),
    (41, 1917, 15, 2603, 1000000.00),
    (42, 1918, 14, 2603, 700000.00),
    (43, 1918, 15, 2603, 1500000.00),
    (44, 1917, 16, 2603, 350000.00),
    (45, 1917, 17, 2603, 750000.00),
    (46, 1918, 16, 2603, 900000.00),
    (47, 1918, 17, 2603, 1200000.00),
    (49, 1917, 14, 2604, 350000.00),
    (50, 1917, 19, 2604, 400000.00),
    (51, 1917, 15, 2604, 500000.00),
    (52, 1917, 20, 2604, 550000.00),
    (53, 1918, 14, 2604, 700000.00),
    (54, 1918, 19, 2604, 800000.00),
    (55, 1918, 15, 2604, 900000.00),
    (56, 1918, 20, 2604, 1000000.00);

INSERT INTO traveline_hotels (hotel_id, created_at) VALUES (564, '2024-01-23 07:15:25');
