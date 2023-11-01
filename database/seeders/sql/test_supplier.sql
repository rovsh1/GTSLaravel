INSERT INTO `suppliers` (`id`, `name`, `currency`, `created_at`, `updated_at`)
VALUES (555, 'Sixt', 'UZS', '2023-10-23 14:05:22', '2023-10-23 14:05:22');

INSERT INTO `supplier_seasons` (`id`, `supplier_id`, `number`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES (1, 555, '2023', '2023-01-01', '2023-12-31', '2023-10-23 14:05:22', '2023-10-23 14:05:22'),
       (2, 555, 'NEW YEAR', '2024-01-01', '2024-03-31', '2023-10-26 07:45:53', '2023-10-26 07:48:58');

INSERT INTO `supplier_services` (`id`, `supplier_id`, `title`, `type`, `data`, `created_at`, `updated_at`)
VALUES (1, 555, 'Трансфер из аэропорта Ташкента', 7, '{\"airportId\":1}', NULL, NULL),
       (2, 555, 'Трансфер в аэропорт Ташкента', 6, '{\"airportId\":1}', NULL, NULL),
       (3, 555, 'Аренда авто на пол дня в Ташкенте', 3, '{\"cityId\":1}', NULL, NULL),
       (4, 555, 'Трансфер в ЖД вокзала Ташкент', 4, '{\"railwayStationId\":1,\"cityId\":1}', NULL, NULL),
       (5, 555, 'Трансфер из ЖД вокзала Ташкент', 5, '{\"railwayStationId\":1,\"cityId\":1}', NULL, NULL),
       (6, 555, 'Трасфер Ташкент-Самарканд', 9, '{\"fromCityId\":1,\"toCityId\":4,\"returnTripIncluded\":true}', NULL, NULL),
       (7, 555, 'Однодневная поездка в горы', 10, '{\"cityId\":1}', NULL, NULL),
       (8, 555, 'CIP Встреча Аэропорт Ташкент', 2, '{\"airportId\":1}', NULL, NULL),
       (9, 555, 'CIP Проводы Аэропорт Ташкент', 2, '{\"airportId\":1}', NULL, NULL);

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
