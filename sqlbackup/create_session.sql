CREATE TABLE `session` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `run_counter` int(10) unsigned DEFAULT NULL,
  `start_tv_sec` int(10) unsigned NOT NULL,
  `start_tv_usec` mediumint(6) unsigned NOT NULL,
  `end_tv_sec` int(10) unsigned DEFAULT NULL,
  `end_tv_usec` mediumint(6) unsigned DEFAULT NULL,
  `ip` char(16) NOT NULL,
  `series_state` tinyint(3) unsigned DEFAULT NULL,
  `cpu_state` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk
