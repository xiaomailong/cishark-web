<?php
class LogAction extends CommonAction
{
    protected $dao,$roleid;

    function _initialize()
    {
    }
    /**
     * 列表
     */
    public function index()
    {
        $this->display();
    }
}
?>
