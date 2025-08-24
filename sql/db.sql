CREATE TABLE IF NOT EXISTS `mc_textmulti` (
  `id_textmulti` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_textmulti` varchar(25) NOT NULL DEFAULT 'home',
  `id_module` int(11) DEFAULT NULL,
  `order_textmulti` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_textmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_textmulti_content` (
  `id_textmulti_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_textmulti` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `title_textmulti` varchar(125) NOT NULL,
  `desc_textmulti` text,
  `published_textmulti` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_textmulti_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_textmulti` (`id_textmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_textmulti_content`
  ADD CONSTRAINT `mc_textmulti_content_ibfk_1` FOREIGN KEY (`id_textmulti`) REFERENCES `mc_textmulti` (`id_textmulti`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_textmulti_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;