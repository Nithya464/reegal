-- Change datatype bigInteger to Integer in  'price_levels' table--


ALTER TABLE `price_levels` CHANGE `id` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;



-- Change customer_discount column set as Null in Contacts Table

ALTER TABLE `contacts` CHANGE `customer_discount` `customer_discount` DOUBLE(8,2) NULL DEFAULT '0.00' COMMENT 'In Per(%)';