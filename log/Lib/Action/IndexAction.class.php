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
        import('ORG.Util.Page');

        $count = $this->db_session->field("
            session_id,
            parent_id,
            FROM_UNIXTIME(start_tv_sec) as start_tv_sec,
            start_tv_usec,
            FROM_UNIXTIME(end_tv_sec) as end_tv_sec,
            end_tv_usec,
            ip,
            series_state,
            cpu_state
            ")
            ->count();

        $page=new Page($count,C("PAGESIZE"));
        $show=$page->show();
        $this->assign("page",$show);

        $sessions = $this->db_session->field("
            session_id,
            parent_id,
            FROM_UNIXTIME(start_tv_sec) as start_tv_sec,
            start_tv_usec,
            FROM_UNIXTIME(end_tv_sec) as end_tv_sec,
            end_tv_usec,
            ip,
            series_state,
            cpu_state
            ")
            ->order('session_id desc')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();

        #dump($sessions);
        $this->assign("sessions",$sessions);

        $this->display();
    }
}
