<?php
class LogAction extends CommonAction
{
    protected $dao,$roleid;
    protected $db_session,$db_datadict,$db_station,$db_log,$db_data_dict;

    function _initialize()
    {
        $this->db_session = D('session');
        $this->db_station = D('station');
        $this->db_log = D('log');
        $this->db_data_dict = new DataDictModel();
    }
    /*
     * 根据传入的时间得到日志表的名称
     */
    private function get_log_tb_name($time)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $time);
        return 'log'.$d->format("Ymd");
    }
    /*
     * 根据双系状态信息、CPU状态信息、时间信息得到session的id
     */
    private function get_session_id($series_state,$cpu_state,$time)
    {
        $tb_name = $this->get_log_tb_name($time);
        $timestamp = strtotime($time);

        $model = M();
        $sql = "SELECT DISTINCT $tb_name.session_id AS session_id
                FROM `$tb_name`,`session`
                WHERE $tb_name.log_tv_sec = $timestamp
                AND $tb_name.`session_id` = session.`session_id`
                AND session.cpu_state = $cpu_state
                AND session.series_state = $series_state";
        $sessions = $model->query($sql);
        # echo $model->GetLastSql();
        if (empty($sessions)) {
            return NULL;
        }else {
            return $sessions[0]['session_id'];
        }
    }
    private function get_id_str_list($where)
    {
        $fsm_data_dict = $this->db_data_dict->field("fd_value")
            ->where($where)
            ->select();
        # echo $this->db_data_dict->GetLastSql();
        $str_list = "";
        foreach ($fsm_data_dict as $key => $value) {
            $str_list = $str_list.",".$value['fd_value'];
        }
        # echo "$str_list";
        return $str_list;
    }
    /*
     * 从数据字典当中得到状态机相关id，并以字符串形式返回
     */
    private function get_fsm_id_str_list()
    {
        return $this->get_id_str_list("enum_name like 'fsm%'");
    }
    /*
     * 从数据字典当中得到电子单元接收类型的id
     */
    private function get_eeu_recv_id_str_list()
    {
        return $this->get_id_str_list("enum_name like 'eeu_recv%'");
    }
    /*
     * 从数据字典当中得到电子单元发送类型的id
     */
    private function get_eeu_send_id_str_list()
    {
        return $this->get_id_str_list("enum_name like 'eeu_send%'");
    }
    /*
     * 从数据字典当中得到电子单元请求类型的id
     */
    private function get_eeu_request_id_str_list()
    {
        return $this->get_id_str_list("enum_name = 'eeu_request_status'");
    }
    /*
     * 向模板当中赋值站场名称信息
     */
    private function assign_station_list()
    {
        $station_list = $this->db_station->field("id,name")->select();
        $this->assign('station_list',$station_list);
    }
    private function validate_date($date,$format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    /*
     * 返回给定时间两个小时后的时间
     */
    private function next_two_hour_str($time)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $time);
        /*向后递增两个小时，格式说明http://sixrevisions.com/web-development/php-dateinterval-class/*/
        $interval = new DateInterval("PT2H");

        $new_date = date_add($d,$interval);
        return date_format($new_date, 'Y-m-d H:i:s');
    }
    /*
     * 验证get的数据
     */
    private function check_get()
    {
        /*如果没有传递双系状态参数则以主系为主*/
        if (empty($_GET['series_state'])) {
            $data = $this->db_data_dict->field('fd_value')->where("enum_name='series_primary'")->select();
            $_GET['series_state'] = $data[0]['fd_value'];
        }

        /*若cpu状态没有传递则使用主CPU*/
        if (empty($_GET['cpu_state'])) {
            $data = $this->db_data_dict->field('fd_value')->where("enum_name='cpu_primary'")->select();
            $_GET['cpu_state'] = $data[0]['fd_value'];
            # echo $_GET['cpu_state'];
        }

        /*若时间未传递使用当前时间*/
        if(empty($_GET['time']) or $this->validate_date($_GET['time']) == false)
        {
            echo $_GET['time'];
            $_GET['time'] = date_format(new DateTime(), 'Y-m-d H:i:s');
        }
    }
    /**
     * 列表
     */
    public function index()
    {
        $this->check_get();

        $stationname = $_GET['station'];
        $series_state = $_GET['series_state'];
        $cpu_state = $_GET['cpu_state'];
        $time = $_GET['time'];
        $fsm = $_GET['fsm'];
        $eeu_recv = $_GET['eeu_recv'];
        $eeu_send = $_GET['eeu_send'];
        $eeu_request = $_GET['eeu_request'];

        $end_time = $this->next_two_hour_str($time);

        /*得到条件*/
        $tb_name = $this->get_log_tb_name($time);
        $session_id = $this->get_session_id($series_state,$cpu_state,$time);

        $type_list = "0";
        if (empty($fsm) == false) {
            $type_list = $type_list.$this->get_fsm_id_str_list();
        }
        if (empty($eeu_recv) == false) {
            $type_list = $type_list.$this->get_eeu_recv_id_str_list();
        }
        if (empty($eeu_send) == false) {
            $type_list = $type_list.$this->get_eeu_send_id_str_list();
        }
        if (empty($eeu_request) == false) {
            $type_list = $type_list.$this->get_eeu_request_id_str_list();
        }

        import('ORG.Util.Page');

        $model = M();

        $select = 'SELECT FROM_UNIXTIME(log_tv_sec) as time,
                           log_tv_usec as usec,
                           get_log_type_name(log_type) as type,
                           log_content as content ';
        $from =   'FROM `'.$tb_name.'` ';
        /*这里只限制了开始时间，没有限制结束时间，因为数据以天表存储，所以不会有太多的数据*/
        $where =  "WHERE session_id = $session_id 
                      AND log_type IN ($type_list) 
                      AND log_tv_sec >= UNIX_TIMESTAMP('$time') 
                      AND log_tv_sec <= UNIX_TIMESTAMP('$end_time') 
                      ";
        $order =  "ORDER BY log_tv_sec,log_tv_usec ";

        $count = 'SELECT 1 ';

        $sql = $count.$from.$where.$order;
        # echo $sql;

        /*计算出总数，并分页查找*/
        $res = $model->query($sql); 
        $count = sizeof($res);

        $page = new Page($count,C("PAGESIZE"));
        $show = $page->show();
        $this->assign("page",$show);

        $limit =  'LIMIT '.$page->firstRow.','.$page->listRows;

        $sql = $select.$from.$where.$order.$limit;
        $res = $model->query($sql);

        $this->assign("data_list",$res);

        $this->assign($_GET);

        /*assign station list*/
        $this->assign_station_list();

        /*assign series state*/
        $this->get_field_dict('session','series_state',"series_states");

        /*assign cpu state*/
        $this->get_field_dict('session','cpu_state',"cpu_states");

        $this->display();
    }
}
?>
