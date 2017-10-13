<?php

class Controller_Order extends Admin
{
    protected $layout = 'base';
    protected $model_plan;
    protected $model_order;
    protected $model_order_data;

    public function init()
    {
        parent::init();
        $this->model_plan = new Model_Plan();
        $this->model_order = new Model_Order();
        $this->model_order_data = new Model_OrderData();
    }

    /**
     * pc广告
     */
    public function indexAction()
    {
        $list = $this->model_order->htGetList($this->_request->getQuery());
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->assign('all_pv', $list['all_pv']);
        $this->assign('all_click', $list['all_click']);
        $this->assign('all_click_rate', $list['all_click_rate']);
        $this->display();
    }
    /**
     * 移动广告
     */
    public function mobileadvertAction()
    {
        $list = $this->model_order->htGetList($this->_request->getQuery(),2);
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->assign('all_pv', $list['all_pv']);
        $this->assign('all_click', $list['all_click']);
        $this->assign('all_click_rate', $list['all_click_rate']);
        $this->display();
    }
    /**
     * 外呼
     */
    public function outboundAction()
    {
        $list = $this->model_order->htGetList($this->_request->getQuery(),3);
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->assign('all_pv', $list['all_pv']);
        $this->assign('all_click', $list['all_click']);
        $this->assign('all_click_rate', $list['all_click_rate']);
        $this->display();
    }
    /**
     * 数据
     */
    public function dataAction()
    {
        $list = $this->model_order->htGetList($this->_request->getQuery(),4);
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->assign('all_pv', $list['all_pv']);
        $this->assign('all_click', $list['all_click']);
        $this->assign('all_click_rate', $list['all_click_rate']);
        $this->display();
    }
    /**
     * 其他
     */
    public function otherAction()
    {
        $list = $this->model_order->htGetList($this->_request->getQuery(),5);
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->assign('all_pv', $list['all_pv']);
        $this->assign('all_click', $list['all_click']);
        $this->assign('all_click_rate', $list['all_click_rate']);
        $this->display();
    }

    /**
     * 录入数据
     */
    public function inputDataAction()
    {
        if ($this->_request->isPost()) {
            $return  = $this->model_order_data->inputData($this->_request->getPost());
            if($return) {
                fn_ajax_return(1,'操作成功',$return);
            } else {
                fn_ajax_return(0,'系统繁忙,请联系管理员!');
            }
        }
    }

    /**
     * 修改状态
     */
    public function editStatusAction(){
        if ($this->_request->isPost()) {
            $res = $this->model_order->editStatus($this->_request->getPost());
            if($res) fn_ajax_return(1, '修改成功','');
            fn_ajax_return(0, '修改失败','');
        }else{
            $res = $this->model_order->getStatus($this->_request->getQuery());
            if($res) fn_ajax_return(1, '获取成功',$res);
            fn_ajax_return(0, '获取失败','');
        }
    }


}