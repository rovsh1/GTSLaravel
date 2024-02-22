INSERT INTO `suppliers` (`id`, `name`, `type_id`, `currency`, `created_at`, `updated_at`)
VALUES (555, 'Sixt', 335, 'UZS', '2023-10-23 14:05:22', '2023-10-23 14:05:22');

INSERT INTO `supplier_cities` (`city_id`, `supplier_id`)
VALUES (1, 555), (4, 555);

INSERT INTO `supplier_airports` (`airport_id`, `supplier_id`)
VALUES (1, 555), (4, 555);

INSERT INTO `supplier_seasons` (`id`, `supplier_id`, `number`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES (1, 555, 'LOW', '2024-01-01', '2024-06-30', '2023-10-23 14:05:22', '2023-10-23 14:05:22'),
       (2, 555, 'HIGH', '2024-07-01', '2024-12-31', '2023-10-26 07:45:53', '2023-10-26 07:48:58');

INSERT INTO `supplier_services` (`id`, `supplier_id`, `type`, `data`, `created_at`, `updated_at`)
VALUES (1, 555,  7, '{\"airportId\":1}', NULL, NULL),
       (2, 555,  6, '{\"airportId\":1}', NULL, NULL),
       (3, 555,  3, '{\"cityId\":1}', NULL, NULL),
       (4, 555, 4, '{\"railwayStationId\":1,\"cityId\":1}', NULL, NULL),
       (5, 555, 5, '{\"railwayStationId\":1,\"cityId\":1}', NULL, NULL),
       (6, 555, 9, '{\"fromCityId\":1,\"toCityId\":4,\"returnTripIncluded\":true}', NULL, NULL),
       (7, 555, 10, '{\"cityId\":1}', NULL, NULL),
       (8, 555, 2, '{\"airportId\":1}', NULL, NULL),
       (9, 555, 11, '{\"airportId\":1}', NULL, NULL),
       (10, 555, 8, NULL, NULL, NULL);

INSERT INTO `r_railway_stations` (`id`, `city_id`) VALUES (1, 1);
INSERT INTO `r_railway_stations_translation` (`translatable_id`, `language`, `name`) VALUES (1, 'ru', 'ЖД Вокзал Ташкент');

INSERT INTO `supplier_services_translation` (`translatable_id`, `language`, `title`)
VALUES (1, 'ru', 'Трансфер из аэропорта Ташкента'),
       (2, 'ru', 'Трансфер в аэропорт Ташкента'),
       (3, 'ru', 'Аренда авто на пол дня в Ташкенте'),
       (4, 'ru', 'Трансфер в ЖД вокзала Ташкент'),
       (5, 'ru', 'Трансфер из ЖД вокзала Ташкент'),
       (6, 'ru', 'Трасфер Ташкент-Самарканд'),
       (7, 'ru', 'Однодневная поездка в горы'),
       (8, 'ru', 'CIP Встреча Аэропорт Ташкент'),
       (9, 'ru', 'CIP Проводы Аэропорт Ташкент'),
       (10, 'ru', 'Прочая'),
       (1, 'en', '[EN] Трансфер из аэропорта Ташкента'),
       (2, 'en', '[EN] Трансфер в аэропорт Ташкента'),
       (3, 'en', '[EN] Аренда авто на пол дня в Ташкенте'),
       (4, 'en', '[EN] Трансфер в ЖД вокзала Ташкент'),
       (5, 'en', '[EN] Трансфер из ЖД вокзала Ташкент'),
       (6, 'en', '[EN] Трасфер Ташкент-Самарканд'),
       (7, 'en', '[EN] Однодневная поездка в горы'),
       (8, 'en', '[EN] CIP Встреча Аэропорт Ташкент'),
       (9, 'en', '[EN] CIP Проводы Аэропорт Ташкент'),
       (10, 'en', '[EN] Прочая');

INSERT INTO `supplier_contracts` (`id`, `supplier_id`, `service_type`, `status`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES (1, 555, 7, 1,'2024-01-01', '2024-12-31', null, null),
       (2, 555, 6, 1, '2024-01-01', '2024-12-31',null, null),
       (3, 555, 3, 1,'2024-01-01', '2024-12-31', null, null),
       (4, 555, 4, 1, '2024-01-01', '2024-12-31',null, null),
       (5, 555, 5, 1,'2024-01-01', '2024-12-31', null, null),
       (6, 555, 9, 1,'2024-01-01', '2024-12-31', null, null),
       (7, 555, 10, 1,'2024-01-01', '2024-12-31', null, null),
       (8, 555, 2, 1,'2024-01-01', '2024-12-31', null, null),
       (9, 555, 11, 1,'2024-01-01', '2024-12-31', null, null),
       (10, 555, 8, 1,'2024-01-01', '2024-12-31', null, null);

INSERT INTO `supplier_service_contracts` (`service_id`, `contract_id`)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5),
       (6, 6),
       (7, 7),
       (8, 8),
       (9, 9),
       (10, 10);

INSERT INTO `supplier_cars` (`id`, `supplier_id`, `car_id`)
VALUES (1, 555, 1),
       (2, 555, 2);

INSERT INTO `supplier_car_prices` (`id`, `season_id`, `service_id`, `car_id`, `currency`, `price_net`, `prices_gross`)
VALUES (1, 1, 1, 1, 'UZS', '20000.00', '[{\"amount\":30000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (2, 1, 2, 1, 'UZS', '20000.00', '[ { \"amount\": 30000, \"currency\": \"UZS\" }, { \"amount\": 25, \"currency\": \"USD\" } ]'),
       (3, 1, 3, 1, 'UZS', '50000.00', '[ { \"amount\": 100000, \"currency\": \"UZS\" }, { \"amount\": 50, \"currency\": \"USD\" } ]'),
       (4, 1, 1, 2, 'UZS', '30000.00', '[{\"amount\":50000,\"currency\":\"UZS\"},{\"amount\":50,\"currency\":\"USD\"}]'),
       (5, 2, 1, 1, 'UZS', '100000.00', '[{\"amount\":200000,\"currency\":\"UZS\"},{\"amount\":20,\"currency\":\"USD\"}]'),
       (6, 2, 1, 2, 'UZS', '200000.00', '[{\"amount\":300000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (7, 1, 2, 2, 'UZS', '30000.00', '[{\"amount\":40000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (8, 2, 2, 1, 'UZS', '100000.00', '[{\"amount\":200000,\"currency\":\"UZS\"},{\"amount\":20,\"currency\":\"USD\"}]'),
       (9, 2, 2, 2, 'UZS', '200000.00', '[{\"amount\":300000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (10, 1, 3, 2, 'UZS', '70000.00', '[{\"amount\":90000,\"currency\":\"UZS\"},{\"amount\":90,\"currency\":\"USD\"}]'),
       (11, 2, 3, 1, 'UZS', '30000.00', '[{\"amount\":40000,\"currency\":\"UZS\"},{\"amount\":40,\"currency\":\"USD\"}]'),
       (12, 2, 3, 2, 'UZS', '40000.00', '[{\"amount\":50000,\"currency\":\"UZS\"},{\"amount\":50,\"currency\":\"USD\"}]'),
       (13, 1, 4, 1, 'UZS', '100000.00', '[{\"amount\":150000,\"currency\":\"UZS\"},{\"amount\":15,\"currency\":\"USD\"}]'),
       (14, 1, 4, 2, 'UZS', '200000.00', '[{\"amount\":250000,\"currency\":\"UZS\"},{\"amount\":25,\"currency\":\"USD\"}]'),
       (15, 2, 4, 1, 'UZS', '50000.00', '[{\"amount\":150000,\"currency\":\"UZS\"},{\"amount\":15,\"currency\":\"USD\"}]'),
       (16, 2, 4, 2, 'UZS', '70000.00', '[{\"amount\":140000,\"currency\":\"UZS\"},{\"amount\":14,\"currency\":\"USD\"}]'),
       (17, 1, 5, 1, 'UZS', '50000.00', '[{\"amount\":100000,\"currency\":\"UZS\"},{\"amount\":10,\"currency\":\"USD\"}]'),
       (18, 1, 5, 2, 'UZS', '120000.00', '[{\"amount\":140000,\"currency\":\"UZS\"},{\"amount\":14,\"currency\":\"USD\"}]'),
       (19, 2, 5, 1, 'UZS', '60000.00', '[{\"amount\":90000,\"currency\":\"UZS\"},{\"amount\":9,\"currency\":\"USD\"}]'),
       (20, 2, 5, 2, 'UZS', '70000.00', '[{\"amount\":100000,\"currency\":\"UZS\"},{\"amount\":10,\"currency\":\"USD\"}]'),
       (21, 1, 6, 1, 'UZS', '200000.00', '[{\"amount\":250000,\"currency\":\"UZS\"},{\"amount\":25,\"currency\":\"USD\"}]'),
       (22, 1, 6, 2, 'UZS', '300000.00', '[{\"amount\":350000,\"currency\":\"UZS\"},{\"amount\":35,\"currency\":\"USD\"}]'),
       (23, 2, 6, 1, 'UZS', '150000.00', '[{\"amount\":200000,\"currency\":\"UZS\"},{\"amount\":20,\"currency\":\"USD\"}]'),
       (24, 2, 6, 2, 'UZS', '200000.00', '[{\"amount\":240000,\"currency\":\"UZS\"},{\"amount\":24,\"currency\":\"USD\"}]');

INSERT INTO `supplier_car_cancel_conditions` (`id`, `season_id`, `service_id`, `car_id`, `data`)
VALUES (1, 1, 1, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (2, 1, 1, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (3, 2, 1, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (4, 2, 1, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (5, 1, 2, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (6, 1, 2, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (7, 2, 2, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (8, 2, 2, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (9, 1, 3, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (10, 1, 3, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (11, 2, 3, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (12, 2, 3, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (13, 1, 4, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (14, 1, 4, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (15, 2, 4, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (16, 2, 4, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (17, 1, 5, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (18, 1, 5, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (19, 2, 5, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (20, 2, 5, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (21, 1, 6, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (22, 1, 6, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (23, 2, 6, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (24, 2, 6, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),

       (25, 1, 7, 1 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (26, 1, 7, 2 ,'{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (27, 2, 7, 1 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (28, 2, 7, 2 ,'{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}');

INSERT INTO `supplier_airport_prices` (`id`, `season_id`, `service_id`, `currency`, `price_net`, `prices_gross`)
VALUES (1, 1, 8, 'UZS', '20000.00', '[{\"amount\":30000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (2, 2, 8, 'UZS', '30000.00', '[{\"amount\":40000,\"currency\":\"UZS\"},{\"amount\":40,\"currency\":\"USD\"}]'),
       (3, 1, 9, 'UZS', '20000.00', '[{\"amount\":30000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (4, 2, 9, 'UZS', '30000.00', '[{\"amount\":40000,\"currency\":\"UZS\"},{\"amount\":40,\"currency\":\"USD\"}]');

INSERT INTO `supplier_other_prices` (`id`, `season_id`, `service_id`, `currency`, `price_net`, `prices_gross`)
VALUES (1, 1, 10, 'UZS', '20000.00', '[{\"amount\":30000,\"currency\":\"UZS\"},{\"amount\":30,\"currency\":\"USD\"}]'),
       (2, 2, 10, 'UZS', '30000.00', '[{\"amount\":40000,\"currency\":\"UZS\"},{\"amount\":40,\"currency\":\"USD\"}]');

INSERT INTO `supplier_service_cancel_conditions` (`id`, `season_id`, `service_id`, `data`)
VALUES (1, 1, 8, '{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (2, 2, 8, '{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (3, 1, 9, '{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (4, 2, 9, '{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}'),
       (5, 1, 10, '{"noCheckInMarkup":{"percent":5},"dailyMarkups":[{"percent":15,"daysCount":3}]}'),
       (6, 2, 10, '{"noCheckInMarkup":{"percent":2},"dailyMarkups":[{"percent":10,"daysCount":1}]}');
