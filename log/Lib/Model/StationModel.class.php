<?php

class StationModel extends CommonModel{
    protected $_validate = array(
        array('id', 'require', '参数错误！'),
        array('station', 'require', '站场名不能为空！'),
    );
}

?>
