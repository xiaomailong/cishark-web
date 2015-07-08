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
        # code...
    }
    /*
     * 根据双系状态信息、CPU状态信息、时间信息得到session的id
     */
    private function get_session_id($series_sate,$cpu_state,$time)
    {

    }
    /*
     * 向模板当中赋值站场名称信息
     */
    private function assign_station_list()
    {
        $station_list = $this->db_station->field("id,name")->select();
        $this->assign('station_list',$station_list);
    }
    /**
     * 列表
     */
    public function index()
    {
        $stationname = $_GET['station'];

        /*如果没有传递双系状态参数则以主系为主*/
        if (empty($_GET['series_state'])) {
            $data = $this->db_data_dict->field('fd_value')->where("enum_name='series_primary'")->select();
            $_GET['series_state'] = $data[0]['fd_value'];
        }
        $series_state = $_GET['series_state'];

        /*若cpu状态没有传递则使用主CPU*/
        if (empty($_GET['cpu_state'])) {
            $data = $this->db_data_dict->field('fd_value')->where("enum_name='cpu_primary'")->select();
            $_GET['cpu_state'] = $data[0]['fd_value'];
            # echo $_GET['cpu_state'];
        }
        $cpu_state = $_GET['cpu_state'];

        /*若时间未传递使用当前时间*/
        if(empty($_GET['time']))
        {
            $_GET['time'] = date_format(new DateTime(), 'Y-m-d H:i:s');
        }
        $time = $_GET['time'];

        $fsm = $_GET['fsm'];
        $eeu_recv = $_GET['eeu_recv'];
        $eeu_send = $_GET['eeu_send'];
        $eeu_request = $_GET['eeu_request'];

        # $tb_name = $this->get_log_tb_name($time);
        $tb_name = "log20150708";
        $model = M();
        $sql = 'SELECT FROM_UNIXTIME(log_tv_sec) as time,
                       log_tv_usec as usec,
                       get_log_type_name(log_type) as type,
                       log_content as content
                FROM `log20150707`
                WHERE session_id = 3066 
                    AND log_type IN (165,167,168,169,170,171,0) 
                    AND log_tv_sec >= UNIX_TIMESTAMP("2015-03-23 09:25:00") 
                ORDER BY log_tv_sec,log_tv_usec';

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
