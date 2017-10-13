<?php

class Controller_Member extends Web
{
    protected $model_admin;

    public function init()
    {
        parent::init();
        $this->model_admin= new Model_Admin();
    }

    /**
     * 登录
     */
    public function loginAction()
    {

        if ($this->_request->isPost()) {
            $info = $this->_request->getPost();

            //判断数据是否正确
            $admin = $this->model_admin->loginCheck($info);

            if($admin){
                $this->model_admin->update(['last_login_time' => time(), 'last_login_ip' => fn_get_ip()],['id' => $admin['id']]);
                $admin_info=[
                    'id'=>$admin['id'],
                    'username'=>$admin['username'],
                    'realname'=>$admin['realname'],
                    'position_id'=>$admin['position_id'],
                ];
                $this->_session->set("admin_info", $admin_info);
                fn_ajax_return(1, "登陆成功");
            }

        } else {

            $admin_info = $this->_session->get("admin_info");
            if($admin_info) {
                Header("Location: /admin");
            }
            $this->display();
        }
    }

    /**
     * 退出登录
     */
    public function logoutAction(){
        $this->_session->del('admin_info');
        $this->display("member/login");
    }

}
