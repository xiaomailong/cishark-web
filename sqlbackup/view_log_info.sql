DELIMITER $$

ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_info` AS (
SELECT
  `log`.`session_id`  AS `session_id`,
  `log`.`log_tv_sec`  AS `log_tv_sec`,
  `log`.`log_tv_usec` AS `log_tv_usec`,
  `get_log_type_name`(
`log`.`log_type`)  AS `log_type`,
  `log`.`log_content` AS `log_content`
FROM `log`
ORDER BY `log`.`log_tv_sec`,`log`.`log_tv_usec`)$$

DELIMITER ;