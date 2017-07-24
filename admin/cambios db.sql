ALTER TABLE `faq` ADD `in_faq` TINYINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `faq` ADD `in_categories` TEXT NULL DEFAULT NULL;
ALTER TABLE `faq` ADD `in_products` TEXT NULL DEFAULT NULL;

ALTER TABLE `cart_item` ADD `cost_base` FLOAT(10,2) NOT NULL ;
ALTER TABLE `product` ADD `no_discount` TINYINT NOT NULL DEFAULT '0' ;

add product > related_gim text