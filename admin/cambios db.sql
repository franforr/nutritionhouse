ALTER TABLE `cart` ADD `id_coupon` INT NULL COMMENT 'Cupon' ;


ALTER TABLE `cart` ADD `iva` DECIMAL(10,2) NULL ;
ALTER TABLE `cart` ADD `gim_discount` DECIMAL(10,2) NULL

ALTER TABLE `cart` ADD `gim_comission` DECIMAL(10,2) NULL ;


CREATE TABLE IF NOT EXISTS `provinces` (
  `id_province` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(255) DEFAULT NULL COMMENT 'Provincia',
  `shipping` decimal(10,2) DEFAULT NULL COMMENT 'Costo de envío',
  `active` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Estado',
  PRIMARY KEY (`id_province`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `provinces`
--

INSERT INTO `provinces` (`id_province`, `province`, `shipping`, `active`) VALUES
(1, 'Artigas', '10.00', 1),
(2, 'Canelones', '10.00', 1),
(3, 'Cerro Largo', '10.00', 1),
(4, 'Colonia', '10.00', 1),
(5, 'Durazno', '10.00', 1),
(6, 'Flores', '10.00', 1),
(7, 'Florida', '10.00', 1),
(8, 'Lavalleja', '10.00', 1),
(9, 'Maldonado', '10.00', 1),
(10, 'Montevideo', '10.00', 1),
(11, 'Paysandú', '10.00', 1),
(12, 'Río Negro', '10.00', 1),
(13, 'Rivera', '10.00', 1),
(14, 'Rocha', '10.00', 1),
(15, 'Salto', '10.00', 1),
(16, 'San José', '10.00', 1),
(17, 'Soriano', '10.00', 1),
(18, 'Tacuarembó', '10.00', 1),
(19, 'Treinta y Tres', '10.00', 1);


ALTER TABLE `cart` ADD `shipping_cost` DECIMAL(10,2) NULL ;
ALTER TABLE `cart` CHANGE `province` `id_province` INT NULL COMMENT 'Provincia';
