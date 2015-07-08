DELIMITER $$

USE `ci_log`$$

DROP FUNCTION IF EXISTS `get_log_type_id`$$

CREATE DEFINER=`root`@`%` FUNCTION `get_log_type_id`(
	in_field_name VARCHAR(100)
) RETURNS INT(6)
BEGIN
	DECLARE v_field_id INT(6);
	SELECT fd_value INTO v_field_id 
	 FROM data_dict
	WHERE tb_name = 'log' AND fd_name = 'log_type' AND enum_name = in_field_name;
	IF(v_field_id IS NULL) THEN
		SET v_field_id = 0;
	END IF;
	RETURN v_field_id;
END$$

DELIMITER ;