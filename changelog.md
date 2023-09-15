```sql
-- Make  variation_id default null --
ALTER TABLE `purchase_lines` CHANGE `variation_id` `variation_id` INT(10) UNSIGNED NULL DEFAULT NULL;

-- Set Default Status for Vendor --
ALTER TABLE `transactions` CHANGE `res_order_status` `res_order_status` ENUM('new','received','cooked','served') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'new';

--- Add Po NO ---
ALTER TABLE `transactions` ADD `po_no` VARCHAR(191) NULL DEFAULT NULL AFTER `ref_no`;


```
# 07-06-2023

```sql
ALTER TABLE `purchase_lines` ADD `product_unit_price_id` INT NULL DEFAULT NULL AFTER `sub_unit_id`, ADD INDEX (`product_unit_price_id`);
```

# 11-07-2023

```sql
ALTER TABLE `transactions` ADD `bill_no` VARCHAR(255) NULL DEFAULT NULL AFTER `ref_no`;

ALTER TABLE `transactions` ADD `to_location_id` INT UNSIGNED NOT NULL AFTER `location_id`, ADD INDEX (`to_location_id`);

ALTER TABLE `purchase_lines` ADD `purchase_price_total` DECIMAL(22,4) NOT NULL AFTER `purchase_price`;

ALTER TABLE `product_stocks` ADD `location_id` BIGINT NOT NULL AFTER `reference_id`, ADD INDEX (`location_id`);

```

```sql
CREATE TABLE `price_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `price_level_id` varchar(191) NOT NULL,
  `customer_type` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 => Deactive, 1 => Active',
  `created_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_levels_created_by_foreign` (`created_by`),
  CONSTRAINT `price_levels_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
)

CREATE TABLE `routes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `sales_person_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 => Deactive, 1 => Active',
  `created_by` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `routes_sales_person_id_foreign` (`sales_person_id`),
  KEY `routes_created_by_foreign` (`created_by`),
  CONSTRAINT `routes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `routes_sales_person_id_foreign` FOREIGN KEY (`sales_person_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
)

CREATE TABLE `route_customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(10) unsigned NOT NULL,
  `route_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `route_customers_contact_id_foreign` (`contact_id`),
  KEY `route_customers_route_id_foreign` (`route_id`),
  CONSTRAINT `route_customers_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `route_customers_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE
)
```

ALTER TABLE `transactions` CHANGE `sub_type` `sub_type` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
