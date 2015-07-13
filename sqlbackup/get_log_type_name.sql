DELIMITER $$

USE `ci_log`$$

DROP FUNCTION IF EXISTS `get_log_type_name`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_log_type_name`(
    in_field_id INT(6)
) RETURNS VARCHAR(100) CHARSET gbk
BEGIN
    DECLARE v_field_name VARCHAR(100);
    SELECT enum_name INTO v_field_name 
     FROM data_dict
    WHERE tb_name = 'log' AND fd_value = in_field_id;
    IF(v_field_name IS NULL) THEN
        SET v_field_name = 'UNKNOWN';
    END IF;
    RETURN v_field_name;
END$$


DELIMITER ;