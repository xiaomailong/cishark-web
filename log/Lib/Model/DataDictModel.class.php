<?php

class DataDictModel extends Model {

	// 自动验证设置
	protected $_validate = array(
		array('tb_name', 'require', '数据表名不能为空！'),
		array('fd_name', 'require', '字段名不能为空！'),
		array('fd_value', 'require', '字段值不能为空！'),
		array('fd_value', 'number', '字段值必须为数字！'),
		array('fd_mean', 'require', '字段含义不能为空！'),
	);
}

?>
