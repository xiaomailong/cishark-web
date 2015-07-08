<?php

/* ------------------------------------------------------------
 * 日期		：2012-9-7
 * -----------------------------------------------------------
 * 创建人	：xiaoqing 1043977511@qq.com
 * -----------------------------------------------------------
 * 文件说明	; CommonAction.class.php UTF-8
 * -----------------------------------------------------------
 */

abstract class CommonAction extends Action {
    function _initialize()
    {
    }
    /*
     * $table_name:表名
     * $field_name:字段名
     * $tpl_controlid:界面显示控件ID号
     */
    public function get_field_dict($table_name, $field_name, $tpl_controlid) {
        $model = new DataDictModel();
        $data = $model->field('fd_value as id,fd_mean as name')->where("tb_name='{$table_name}' and fd_name='{$field_name}'")->order('seq')->select();
        $this->assign($tpl_controlid, $data);
    }
    public function get_field_dict_data($table_name, $field_name) {
        $model = new DataDictModel();
        $data = $model->field('fd_value as id,fd_mean as name')->where("tb_name='{$table_name}' and fd_name='{$field_name}'")->order('seq')->select();
        return $data;
    }
    protected function get_tb_comment( $tb_name )
    {
        $db = new Model();
        $data = $db->table('information_schema.TABLES')
            ->where("TABLE_SCHEMA='tsms' and TABLE_NAME='{$tb_name}'")
            ->field('TABLE_COMMENT as comment')
            ->find();
        return $data["comment"];
    }
    protected function get_fd_comment( $tb_name , $fd_name )
    {
        $db = M();
        $data = $db->table('information_schema.COLUMNS')
            ->where("TABLE_SCHEMA='{$this->get_db_name()}' and TABLE_NAME='{$tb_name}' and COLUMN_NAME='{$fd_name}'")
            ->field('COLUMN_COMMENT as comment')
            ->find();
        return $data["comment"];
    }
    protected function sys_config( $varname )
    {
        $db = M('config') ;
        $data = $db->where("varname='{$varname}'")->find();
        return $data ;
    }
    protected function sys_config_value( $varname )
    {
        $db = M('config') ;
        $data = $db->where("varname='{$varname}'")->find();
        return $data['value'] ;
    }
    protected function get_sys_config( $varname , $varid )
    {
        $db = M('config') ;
        $data = $db->where("varname='{$varname}'")->find();
        $this->assign($varid, $data['value']) ;
    }
    protected function get_db_name()
    {
        return C("DB_NAME") ;
    }
    /**
     * 默认操作
     */
    public function index() {
        $this->display();
    }
    /**
     *
     * Enter description here ...
     */
    function insert() {
        $name = MODULE_NAME;
        $model = D ($name);

        if (false === $model->create ()) {
            $this->error ( $model->getError () );
        }
        $id = $model->add();

        $_REQUEST["courseid"] = $id;

        if ($id !==false) {
            if(in_array($name,$this->cache_model)){
                savecache($name);
            }
            $jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
            $this->assign ( 'jumpUrl',$jumpUrl );
            $this->success (L('add_ok'));
        } else {
            $this->error (L('add_error').': '.$model->getDbError());
        }
    }
    /**
     * 添加
     *
     */
    function add() {
        $name = MODULE_NAME;
        $this->display ('edit');
    }

    function edit() {
        $name = MODULE_NAME;
        $model = M ( $name );
        $pk=ucfirst($model->getPk ());
        $id = $_REQUEST [$model->getPk ()];
        if(empty($id))   $this->error(L('do_empty'));
        $do='getBy'.$pk;
        $vo = $model->$do ( $id );
        if($vo['setup']) $vo['setup']=string2array($vo['setup']);
        $this->assign ( 'vo', $vo );
        $this->display ();
    }
    /**
     * 更新操作
     */
    function update() {
        $name = MODULE_NAME;
        $model = D ( $name );
        if (false === $model->create ()) {
            $this->error ( $model->getError () );
        }
        if (false !== $model->save ()) {
            if(in_array($name,$this->cache_model)){
                savecache($name);
            }

            $jumpUrl = $_POST['forward'] ? $_POST['forward'] : U(MODULE_NAME.'/index');
            $this->assign ( 'jumpUrl',$jumpUrl );
            $this->success (L('update_ok'));
        } else {
            $this->success (L('edit_error').': '.$model->getError());
        }
    }
    /**
     * 删除
     */
    function delete(){
        $name = MODULE_NAME;
        $model = M ( $name );
        $pk = $model->getPk ();
        $id = $_REQUEST [$pk];
        if (isset ( $id )) {
            if(false!==$model->delete($id)){
                if(in_array($name,$this->cache_model)){
                    savecache($name);
                }
                $this->success(L('delete_ok'));
            }else{
                $this->error(L('delete_error').': '.$model->getDbError());
            }
        }else{
            $this->error (L('do_empty'));
        }
    }
    /**
     * 批量操作
     *
     */
    public function listorder()
    {
        $name = MODULE_NAME;
        $model = M ( $name );
        $pk = $model->getPk ();
        $ids = $_POST['listorders'];
        foreach($ids as $key=>$r) {
            $data['seq']=$r;
            $model->where($pk .'='.$key)->save($data);
        }
        if(in_array($name,$this->cache_model)) savecache($name);
        $this->success (L('do_ok'));
    }
    /*
     * 根据类型返回数据
     * @param string $message
     */
    public function returnMessage($message,$data = array()){
        if($this->isAjax() == true){
            $this->ajaxReturn($data,$message,1);
        }else{
            $this->show($message,'utf-8','text/xml');
        }
    }

    /**
     * 通过对角色表的查询，得到$child是否是$father的子用户组
     *
     * @param int $father 父用户组id
     * @param int $child 子用户组id
     * @return boolean 如果是则返回true，否则返回false
     */
    protected function isRoleBelongTo($father,$child) {
        if ($child == 0 || $father == 0) {
            return false;
        }
        while ($child > 0) {
            if ($child == $father) {
                return true;
            }
            $child = $this->role_map[$child]['pid'];
        }
        return false;
    }
    /**
     * 得到当前的年份,在实际当中可以使用date函数直接获取时间，但为了代码可读性建议
     * 使用该方法
     * @author zhangys
     * @date 2013-04-25
     */
    protected function getCurYear() {
        return date('Y');
    }
    /**
     * 得到当下的月份
     */
    protected function getCurMonth(){
        return intval(date("n"));
    }

    /**
     * 得到数据字典的值
     * @param string $dd_enum_name 数据表data_dict中的enum_name的值
     */
    protected function getDDValue($dd_enum_name){
        return $this->dd_list[$dd_enum_name];
    }

}

?>
