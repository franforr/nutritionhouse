ALTER TABLE `faq` ADD `in_faq` TINYINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `faq` ADD `in_categories` TEXT NULL DEFAULT NULL;
ALTER TABLE `faq` ADD `in_products` TEXT NULL DEFAULT NULL;
