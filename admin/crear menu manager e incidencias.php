<?php 
die(); 
/*

DROP TABLE IF EXISTS `nz_company_secure`;
CREATE TABLE IF NOT EXISTS `nz_company_secure` (
  `id_company` mediumint(8) UNSIGNED NOT NULL COMMENT 'Empresa',
  `id_submenu` mediumint(8) UNSIGNED NOT NULL COMMENT 'Item',
  `view` tinyint(1) UNSIGNED NOT NULL COMMENT 'Ver',
  `edit` tinyint(1) UNSIGNED NOT NULL COMMENT 'Editar',
  `delete` tinyint(1) UNSIGNED NOT NULL COMMENT 'Borrar',
  `special` tinyint(1) UNSIGNED NOT NULL COMMENT 'Especial'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nz_company_secure`
--

INSERT INTO `nz_company_secure` (`id_company`, `id_submenu`, `view`, `edit`, `delete`, `special`) VALUES
(1, 9, 1, 1, 1, 1),
(1, 8, 1, 1, 1, 1),
(1, 7, 1, 1, 1, 1),
(1, 6, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 1, 1, 1, 1, 1),
(1, 12, 1, 1, 1, 1),
(1, 11, 1, 1, 1, 1),
(1, 13, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_menu`
--

DROP TABLE IF EXISTS `nz_menu`;
CREATE TABLE IF NOT EXISTS `nz_menu` (
  `id_menu` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `controller` varchar(50) DEFAULT NULL COMMENT 'Controlador',
  `name` varchar(255) DEFAULT NULL COMMENT 'Nombre',
  `id_ico` mediumint(8) UNSIGNED DEFAULT NULL COMMENT 'Ícono',
  `num` tinyint(4) UNSIGNED DEFAULT NULL COMMENT 'Orden',
  PRIMARY KEY (`id_menu`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nz_menu`
--

INSERT INTO `nz_menu` (`id_menu`, `controller`, `name`, `id_ico`, `num`) VALUES
(1, 'manager', 'Gestión', 281, 100),
(2, 'tickets', 'Incidencias', 228, 99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_submenu`
--

DROP TABLE IF EXISTS `nz_submenu`;
CREATE TABLE IF NOT EXISTS `nz_submenu` (
  `id_submenu` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_menu` tinyint(3) UNSIGNED NOT NULL COMMENT 'Menú',
  `function` varchar(50) DEFAULT NULL COMMENT 'Función',
  `name` varchar(255) DEFAULT NULL COMMENT 'Nombre',
  `num` tinyint(3) UNSIGNED NOT NULL DEFAULT '99' COMMENT 'Orden',
  `root` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Root',
  PRIMARY KEY (`id_submenu`)
) ENGINE=MyISAM AUTO_INCREMENT=421 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nz_submenu`
--

INSERT INTO `nz_submenu` (`id_submenu`, `id_menu`, `function`, `name`, `num`, `root`) VALUES
(2, 1, 'index', 'Automatización', 2, 1),
(3, 1, 'sql', 'SQL', 3, 1),
(4, 1, 'menu', 'Menú', 4, 1),
(5, 1, 'submenu', 'Submenú', 5, 1),
(6, 1, 'icons', 'Íconos', 6, 1),
(9, 1, 'companies', 'Empresas', 10, 0),
(1, 1, 'actions', 'Acciones', 1, 1),
(7, 1, 'clients', 'Clientes', 7, 0),
(8, 1, 'projects', 'Proyectos', 8, 0),
(10, 1, 'users', 'Usuarios', 11, 0),
(11, 2, 'index', 'Ver Incidencias', 4, 0),
(12, 2, 'categories', 'Categorías', 5, 0),
(13, 2, 'report', 'Reportar', 1, 0);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_user_secure`
--

DROP TABLE IF EXISTS `nz_user_secure`;
CREATE TABLE IF NOT EXISTS `nz_user_secure` (
  `id_user` mediumint(8) UNSIGNED NOT NULL COMMENT 'Usuario',
  `id_submenu` mediumint(8) UNSIGNED NOT NULL COMMENT 'Item',
  `view` tinyint(1) UNSIGNED NOT NULL COMMENT 'Ver',
  `edit` tinyint(1) UNSIGNED NOT NULL COMMENT 'Editar',
  `delete` tinyint(1) UNSIGNED NOT NULL COMMENT 'Borrar',
  `special` tinyint(1) UNSIGNED NOT NULL COMMENT 'Especial',
  KEY `id_user` (`id_user`,`id_submenu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nz_user_secure`
--

INSERT INTO `nz_user_secure` (`id_user`, `id_submenu`, `view`, `edit`, `delete`, `special`) VALUES
(1, 10, 1, 0, 0, 0),
(1, 8, 1, 1, 1, 0),
(1, 6, 1, 1, 1, 0),
(1, 7, 1, 1, 1, 0);










DROP TABLE IF EXISTS `nz_file`;
CREATE TABLE IF NOT EXISTS `nz_file` (
  `id_file` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_folder` mediumint(8) NOT NULL DEFAULT '0',
  `id_type` tinyint(3) UNSIGNED NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'Nombre',
  `alt` varchar(255) DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_file`),
  KEY `id_tabla` (`id_folder`),
  KEY `id_tipo` (`id_type`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_folder`
--

DROP TABLE IF EXISTS `nz_folder`;
CREATE TABLE IF NOT EXISTS `nz_folder` (
  `id_folder` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `id_parent` mediumint(8) NOT NULL DEFAULT '0',
  `id_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '2',
  `id_user` mediumint(8) UNSIGNED DEFAULT NULL,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_folder`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_gallery`
--

DROP TABLE IF EXISTS `nz_gallery`;
CREATE TABLE IF NOT EXISTS `nz_gallery` (
  `id_gallery` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creación',
  PRIMARY KEY (`id_gallery`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_gallery_file`
--

DROP TABLE IF EXISTS `nz_gallery_file`;
CREATE TABLE IF NOT EXISTS `nz_gallery_file` (
  `id_item` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_gallery` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Galería',
  `id_file` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Archivo',
  `num` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Orden',
  PRIMARY KEY (`id_item`),
  KEY `id_gallery` (`id_gallery`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nz_user_data`
--

DROP TABLE IF EXISTS `nz_user_data`;
CREATE TABLE IF NOT EXISTS `nz_user_data` (
  `id_data` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` int(10) UNSIGNED NOT NULL COMMENT 'Usuario',
  `item` varchar(255) NOT NULL COMMENT 'Item',
  `data` text NOT NULL COMMENT 'Data',
  PRIMARY KEY (`id_data`),
  KEY `id_user` (`id_user`,`item`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `nz_session`;
CREATE TABLE IF NOT EXISTS `nz_session` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text,
  `url` varchar(255) DEFAULT NULL,
  `robot` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
