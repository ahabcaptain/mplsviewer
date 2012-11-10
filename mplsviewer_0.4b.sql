DROP TABLE IF EXISTS `ipvrf`;
CREATE TABLE `ipvrf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(80) NOT NULL,
  `name` varchar(40) NOT NULL,
  `desc` varchar(120) NOT NULL,
  `rd` varchar(40) NOT NULL,
  `export` varchar(40) NOT NULL,
  `import` varchar(40) NOT NULL,
  `exportmap` varchar(40) NOT NULL,
  `importmap` varchar(40) NOT NULL,
  `type` varchar(40) NOT NULL,
  `vrftype` varchar(10) NOT NULL,
  `address-family` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3079 DEFAULT CHARSET=latin1;
