<?php

class Controller_Plan extends Front
{

    protected $model_plan;
    protected $model_order;
    protected $model_order_data;
    protected $layout = 'base';
    protected $plan_status = [
        '1' => '待开始',
        '2' => '进行中',
        '3' => '已完成',
        '4' => '已结束',
    ];
    protected $order_type = [
        '1' => 'pc广告',
        '2' => '移动广告',
        '3' => '外呼',
        '4' => '数据',
        '5' => '其他',
    ];
    protected $data_action = [
        '1' => 'index',
        '2' => 'mobileadvert',
        '3' => 'outbound',
        '4' => 'data',
        '5' => 'other',
    ];

    public function init()
    {
        parent::init();
        $this->model_plan = new Model_Plan();
        $this->model_order = new Model_Order();
        $this->model_order_data = new Model_OrderData();
    }

    /**
     * 计划管理列表
     */
    public function indexAction()
    {
        $status = $this->_request->getQuery('status');
        $name = $this->_request->getQuery('name');

        $list = $this->model_plan->planOrderList($this->_request->getQuery());
        $this->assign('data','plan');
        $this->assign('data_action',$this->data_action);
        $this->assign('status',$status);
        $this->assign('name',$name);

        $this->assign('plan_status',$this->plan_status);
        $this->assign('order_type',$this->order_type);
        $this->assign('list',$list['list']);
        $this->assign('count',$list['count']);
        $this->display();
    }

}
