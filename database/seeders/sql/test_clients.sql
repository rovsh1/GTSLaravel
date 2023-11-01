INSERT INTO `client_legals` (`id`, `client_id`, `city_id`, `industry_id`, `name`, `address`, `requisites`, `created_at`, `updated_at`)
VALUES (112, 6, NULL, NULL, 'OTA', 'OTA', '{\"bik\":\"\",\"cityName\":\"\",\"inn\":\"\",\"okpo\":\"\",\"correspondentAccount\":\"\",\"kpp\":\"\",\"bankName\":\"\",\"currentAccount\":\"\"}', '2023-10-26 07:40:29', '2023-10-26 07:40:29'),
       (113, 6, NULL, NULL, 'TA', 'TA', '{\"bik\":\"\",\"cityName\":\"\",\"inn\":\"\",\"okpo\":\"\",\"correspondentAccount\":\"\",\"kpp\":\"\",\"bankName\":\"\",\"currentAccount\":\"\"}', '2023-10-26 07:40:38', '2023-10-26 07:40:38'),
       (114, 6, NULL, NULL, 'TO', 'TO', '{\"bik\":\"\",\"cityName\":\"\",\"inn\":\"\",\"okpo\":\"\",\"correspondentAccount\":\"\",\"kpp\":\"\",\"bankName\":\"\",\"currentAccount\":\"\"}', '2023-10-26 07:40:46', '2023-10-26 07:40:46');

INSERT INTO `client_markup_groups` (`id`, `name`, `type`, `value`, `created_at`, `updated_at`)
VALUES #(1, 'Базовая наценка', 1, 3, '2023-10-23 14:05:14', '2023-10-23 14:05:14'),
       (3, 'OTA', 1, 10, '2023-10-26 10:43:10', '2023-10-26 10:43:10'),
       (4, 'TO', 1, 15, '2023-10-26 10:43:18', '2023-10-26 10:43:18'),
       (5, 'TA', 1, 20, '2023-10-26 10:43:27', '2023-10-26 10:43:27'),
       (6, 'ФИЗЛИЦО', 1, 12, '2023-10-26 10:43:38', '2023-10-26 10:43:38');
