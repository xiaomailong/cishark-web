DELIMITER $$

ALTER ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_session` AS (
SELECT
  `session`.`session_id`    AS `session_id`,
  `session`.`parent_id`   AS `parent_id`,
  `session`.`run_counter`   AS `run_counter`,
  FROM_UNIXTIME(`session`.`start_tv_sec`) AS `start_tv_sec`,
  `session`.`start_tv_usec` AS `start_tv_usec`,
  FROM_UNIXTIME(`session`.`end_tv_sec`) AS `end_tv_sec`,
  `session`.`end_tv_usec`   AS `end_tv_usec`,
  `session`.`ip`            AS `ip`,
  `get_series_state_name`(
`session`.`series_state`)  AS `series_state`,
  `get_cpu_state_name`(
`session`.`cpu_state`)  AS `cpu_state`
FROM `session`)$$

DELIMITER ;