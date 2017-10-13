<?php
class Admin extends Web
{

    public function init()
    {
        parent::init();
        $this->checkLogin();
        $this->adminInfo();
        $this->checkAuth();
    }

    public function checkLogin()
    {
        if (!$this->_session->get('admin_info')) {
            $this->redirect("/admin/member/login");
        }
    }


    //标签显示
    public function checkAuth()
    {
        $task = New Model_Task();
        $info = $this->_session->get('admin_info');
        $join = array(
            '[>]operation' => array(
                'id' => 'task_id'
            ),
        );
        $columns = array(
            'task.id',
            'task.controller',
            'task.action',
            'task.pid',
            'task.is_menu',
            'task.level',
            'task.upper',
        );
        $where['AND']['operation.position_id'] = $info['position_id'];
        $where['ORDER'] = ' id asc ';
        $task_list = $task->select($join,$columns,$where);
        $nav = [];
        $authority = [];
        if($task_list){
            foreach ($task_list as $item){
                if($item['pid']== 0 && $item['is_menu']==1){
                    $nav[] = strtolower($item['controller']);
                }
                if($item['pid']!=0 && $item['is_menu']==1){
                    $authority[] = $item;
                }
            }
        }
        //头部标签
        $this->assign('nav',$nav);
        //列表、操作标签
        $controller = strtolower($this->_c);
        $action = strtolower($this->_a);
        $auth_num =  false;
        if($authority && $task_list){
            foreach ($task_list as $value) {
                //操作标签权限
                if($value['upper']){
                    foreach ($authority as $t=>$l) {
                        if($l['id']==$value['upper']){
                            $authority[$t]['operation'][] = $value['action'];
                        }
                    }
                }
                //控制器访问权限
                if(strtolower($value['controller'])==$controller
                    && strtolower($value['action'])== $action
                    && $value['level']>0
                ){
                    $auth_num= true;
                }
                //方法访问权限
                if(strtolower($value['controller'])==$controller
                    && strtolower($value['action'])==$action
                    && $value['upper']>0
                ){
                    $auth_num= true;
                }
            }
            if(!$auth_num){
                //首个index没权限 则访问其它权限方法
                foreach ($task_list as $t) {
                    if(strtolower($t['controller'])==$controller
                        && $t['level']>0
                    ){
                        $this->redirect("/Admin/{$controller}/{$t['action']}");
                        break;
                    }
                }
                //首个index没权限 着访问其它权限方法
                if($controller=='plan'&& $action=='index' && !$auth_num && $authority){
                    foreach ($authority as $l) {
                            $this->redirect("/Admin/{$l['controller']}/{$l['action']}");
                            break;
                    }
                }
            }
        }
        if(!$auth_num)
        {
            echo "无权限" . '<a href="#" onClick="javascript :history.go(-1);">返回</a>';
            exit;
        }
        $this->assign('authority',$authority);
    }

    /**
     * 获取nav
     */
    public function getAllNav($n=0){
        $info = $this->_session->get('admin_info');
        $task = New Model_Task();
        $result = $task->select(array('id','name'),['AND'=>['pid'=>$n,'is_menu'=>1]]);
        foreach($result as $k=>$v) {
            $result[$k]['child'] = $this->getAllNav($v['id']);
        }
        return $result;
    }

    public function adminInfo()
    {
        $model_admin = new Model_Admin();
        $info = $this->_session->get('admin_info');
        $adminInfo = $model_admin->get('*',['id'=>$info['id']]);
        $this->assign('data',$this->_c);
        $this->assign('adminInfo', $adminInfo);
    }

}
