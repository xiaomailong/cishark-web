CREATE TABLE `log` (
  `session_id` INT(11) UNSIGNED NOT NULL,
  `log_tv_sec` INT(11) UNSIGNED NOT NULL,
  `log_tv_usec` INT(6) UNSIGNED NOT NULL,
  `log_type` TINYINT(3) UNSIGNED NOT NULL,
  `log_content` TEXT
) ENGINE=MYISAM DEFAULT CHARSET=utf8
