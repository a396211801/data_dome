<?php

class Controller_Member extends Web
{
    protected $model_customer;

    public function init()
    {
        parent::init();
        $this->model_customer= new Model_Customer();
    }

    /**
     * 登录
     */
    public function loginAction()
    {
        if ($this->_request->isPost()) {
            $info = $this->_request->getPost();
            //判断数据是否正确
            $customer = $this->model_customer->loginCheck($info);
            if($customer){
                $this->model_customer->update(['last_login_time' => time(), 'last_login_ip' => fn_get_ip()],['id' => $customer['id']]);
                $customer_info=[
                    'id'=>$customer['id'],
                    'username'=>$customer['username'],
                    'realname'=>$customer['realname'],
                ];
                $this->_session->set("customer_info", $customer_info);
                fn_ajax_return(1, "登陆成功");
            }
        } else {
            $customer_info = $this->_session->get("customer_info");
            if($customer_info) {
                Header("Location: /");
            }
            $this->display("member/login");
        }
    }

    /**
     * 退出登录
     */
    public function logoutAction(){
        $this->_session->del('customer_info');
        $this->display("member/login");
    }

}
