CREATE TABLE `data_dict` (
  `tb_name` varchar(32) NOT NULL,
  `fd_name` varchar(32) NOT NULL,
  `fd_value` tinyint(3) unsigned NOT NULL,
  `fd_mean` varchar(32) DEFAULT NULL,
  `enum_name` varchar(100) DEFAULT NULL,
  `remark` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`tb_name`,`fd_name`,`fd_value`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk
