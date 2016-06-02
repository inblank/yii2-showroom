SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Structure
--

-- --------------------------------------------------------

--
-- Table structure for table `showroom_categories`
--

DROP TABLE IF EXISTS `showroom_categories`;
CREATE TABLE `showroom_categories` (
    `id` int(11) NOT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_categories_products`
--

DROP TABLE IF EXISTS `showroom_categories_products`;
CREATE TABLE `showroom_categories_products` (
    `category_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_categories_tree`
--

DROP TABLE IF EXISTS `showroom_categories_tree`;
CREATE TABLE `showroom_categories_tree` (
    `id` int(11) NOT NULL,
    `lft` int(11) NOT NULL,
    `rgt` int(11) NOT NULL,
    `depth` int(11) NOT NULL,
    `tree` int(11) NOT NULL,
    `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_prices`
--

DROP TABLE IF EXISTS `showroom_prices`;
CREATE TABLE `showroom_prices` (
    `seller_id` int(11) NOT NULL DEFAULT '0',
    `product_id` int(11) NOT NULL DEFAULT '0',
    `vendor_id` int(11) NOT NULL DEFAULT '0',
    `price` float NOT NULL DEFAULT '0',
    `available_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
    PARTITION BY KEY (seller_id)
    PARTITIONS 10;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_products`
--

DROP TABLE IF EXISTS `showroom_products`;
CREATE TABLE `showroom_products` (
    `id` int(11) NOT NULL,
    `seller_id` int(11) DEFAULT NULL,
    `type_id` int(11) DEFAULT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_types`
--

DROP TABLE IF EXISTS `showroom_types`;
CREATE TABLE `showroom_types` (
    `id` int(11) NOT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_vendors`
--

DROP TABLE IF EXISTS `showroom_vendors`;
CREATE TABLE `showroom_vendors` (
    `id` int(11) NOT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `showroom_categories`
--
ALTER TABLE `showroom_categories`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_slug` (`slug`);

--
-- Indexes for table `showroom_categories_products`
--
ALTER TABLE `showroom_categories_products`
    ADD PRIMARY KEY (`category_id`,`product_id`),
    ADD KEY `product` (`product_id`),
    ADD KEY `sort` (`category_id`,`sort`);

--
-- Indexes for table `showroom_categories_tree`
--
ALTER TABLE `showroom_categories_tree`
    ADD PRIMARY KEY (`id`),
    ADD KEY `category` (`category_id`);

--
-- Indexes for table `showroom_prices`
--
ALTER TABLE `showroom_prices`
    ADD PRIMARY KEY (`seller_id`,`product_id`,`vendor_id`),
    ADD KEY `seller` (`seller_id`),
    ADD KEY `vendor` (`vendor_id`),
    ADD KEY `products` (`product_id`);

--
-- Indexes for table `showroom_products`
--
ALTER TABLE `showroom_products`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_slug` (`slug`),
    ADD KEY `seller` (`seller_id`),
    ADD KEY `type` (`type_id`);

--
-- Indexes for table `showroom_types`
--
ALTER TABLE `showroom_types`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_slug` (`slug`);

--
-- Indexes for table `showroom_vendors`
--
ALTER TABLE `showroom_vendors`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `showroom_categories`
--
ALTER TABLE `showroom_categories`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `showroom_categories_tree`
--
ALTER TABLE `showroom_categories_tree`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `showroom_products`
--
ALTER TABLE `showroom_products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `showroom_types`
--
ALTER TABLE `showroom_types`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `showroom_vendors`
--
ALTER TABLE `showroom_vendors`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `showroom_categories_products`
--
ALTER TABLE `showroom_categories_products`
    ADD CONSTRAINT `fk__showroom_categories_products__categories` FOREIGN KEY (`category_id`) REFERENCES `showroom_categories` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk__showroom_categories_products__products` FOREIGN KEY (`product_id`) REFERENCES `showroom_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `showroom_categories_tree`
--
ALTER TABLE `showroom_categories_tree`
    ADD CONSTRAINT `fk__showroom_categories__categories_tree` FOREIGN KEY (`category_id`) REFERENCES `showroom_categories` (`id`);

--
-- Constraints for table `showroom_products`
--
ALTER TABLE `showroom_products`
    ADD CONSTRAINT `fk__showroom_products__types` FOREIGN KEY (`type_id`) REFERENCES `showroom_types` (`id`);


--
-- Data
--
INSERT INTO `showroom_types` (`id`, `slug`, `name`) VALUES
    (1, 'test-1', 'Type 1'),
    (2, 'test-2', 'Type 2'),
    (3, 'test-3', 'Type 3');

INSERT INTO `showroom_products` (`id`, `seller_id`, `type_id`, `slug`, `name`, `created_at`, `deleted_at`) VALUES
    (1, NULL, 2, 'product-1', 'Product 1', '2016-06-02 04:34:20', NULL),
    (2, NULL, NULL, 'product-2', 'Product 2', '2016-06-02 04:34:21', NULL),
    (3, 1, NULL, 'product-3', 'Product 3', '2016-06-02 04:34:22', NULL),
    (4, 1, 1, 'product-4', 'Product 4', '2016-06-02 04:34:23', NULL);

INSERT INTO `showroom_vendors` (`id`, `slug`, `name`) VALUES
    (1, 'vendor-1', 'Vendor 1'),
    (2, 'vendor-2', 'Vendor 2');

INSERT INTO `showroom_prices` (`seller_id`, `product_id`, `vendor_id`, `price`, `available_count`) VALUES
    (1, 1, 0, 123, 10),
    (1, 1, 1, 130, 10),
    (1, 3, 0, 200, 10),
    (1, 4, 0, 250, 10);

INSERT INTO `showroom_categories` (`id`, `slug`, `name`, `created_at`, `deleted_at`) VALUES
    (1, 'category-1', 'Category 1', '2016-06-02 07:01:39', NULL),
    (2, 'category-2', 'Category 2', '2016-06-02 07:01:39', NULL),
    (3, 'category-3', 'Category 3', '2016-06-02 07:01:39', NULL);

# 558769
INSERT INTO `showroom_categories_products` (`category_id`, `product_id`, `sort`) VALUES
    (1, 2, 0),
    (1, 3, 1),
    (2, 4, 0);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
