SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `showroom_categories`
--

DROP TABLE IF EXISTS `showroom_categories`;
CREATE TABLE `showroom_categories` (
    `id` int(11) NOT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL
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
    `kind` int(11) DEFAULT NULL,
    `seller_id` int(11) DEFAULT NULL,
    `type_id` int(11) DEFAULT NULL,
    `slug` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_sellers`
--

DROP TABLE IF EXISTS `showroom_sellers`;
CREATE TABLE `showroom_sellers` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `logo` varchar(255) DEFAULT NULL,
    `name` varchar(255) NOT NULL DEFAULT '',
    `slug` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showroom_sellers_profiles`
--

DROP TABLE IF EXISTS `showroom_sellers_profiles`;
CREATE TABLE `showroom_sellers_profiles` (
    `seller_id` int(11) NOT NULL,
    `web` varchar(255) NOT NULL DEFAULT '',
    `description` text NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `id` int(11) NOT NULL,
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
-- Indexes for table `showroom_sellers`
--
ALTER TABLE `showroom_sellers`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `unique_slug` (`slug`);

--
-- Indexes for table `showroom_sellers_profiles`
--
ALTER TABLE `showroom_sellers_profiles`
    ADD PRIMARY KEY (`seller_id`);

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
-- AUTO_INCREMENT for table `showroom_sellers`
--
ALTER TABLE `showroom_sellers`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `showroom_sellers_profiles`
--
ALTER TABLE `showroom_sellers_profiles`
    MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT;
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
    ADD CONSTRAINT `fk__showroom_categories_products__showroom_categories` FOREIGN KEY (`category_id`) REFERENCES `showroom_categories` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk__showroom_categories_products__showroom_products` FOREIGN KEY (`product_id`) REFERENCES `showroom_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `showroom_categories_tree`
--
ALTER TABLE `showroom_categories_tree`
    ADD CONSTRAINT `fk__showroom_categories__showroom_categories_tree` FOREIGN KEY (`category_id`) REFERENCES `showroom_categories` (`id`);

--
-- Constraints for table `showroom_products`
--
ALTER TABLE `showroom_products`
    ADD CONSTRAINT `fk__showroom_products__showroom_sellers` FOREIGN KEY (`seller_id`) REFERENCES `showroom_sellers` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk__showroom_products__showroom_types` FOREIGN KEY (`type_id`) REFERENCES `showroom_types` (`id`);

--
-- Constraints for table `showroom_sellers_profiles`
--
ALTER TABLE `showroom_sellers_profiles`
    ADD CONSTRAINT `fk__showroom_sellers_profiles__showroom_sellers` FOREIGN KEY (`seller_id`) REFERENCES `showroom_sellers` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
