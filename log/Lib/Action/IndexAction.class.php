<?php
/**
 * This is class IndexAction
 *
 */
class IndexAction extends CommonAction {
    protected $db_session;
    /**
     * (non-PHPdoc)
     * @see NavAction::_initialize()
     */
    public function _initialize(){
        parent::_initialize();
        $this->db_session = D('session');
    }
    public function index(){
        $sessions = $this->db_session->field("
            session_id,
            parent_id,
            FROM_UNIXTIME(start_tv_sec) as start_tv_sec,
            start_tv_usec,
            FROM_UNIXTIME(end_tv_sec) as end_tv_sec,
            end_tv_usec,
            ip,
            get_series_state_name(series_state) AS series_state,
            get_cpu_state_name(cpu_state) as cpu_state
            ")
            ->order('session_id desc')
            ->select();

        #dump($sessions);
        $this->assign("sessions",$sessions);

        $this->display();
    }
}
