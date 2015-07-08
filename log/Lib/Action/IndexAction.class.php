<?php
/**
 * This is class IndexAction
 *
 */
class IndexAction extends CommonAction {
    /**
     * (non-PHPdoc)
     * @see NavAction::_initialize()
     */
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $this->display();
    }
}
