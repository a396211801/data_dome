<?php

class Controller_Plan extends Admin
{

    protected $model_plan;
    protected $model_order;
    protected $model_order_data;
    protected $model_customer;
    protected $layout = 'base';
    protected $plan_status = [
        '1' => '待开始',
        '2' => '进行中',
        '3' => '已完成',
        '4' => '已结束',
        '99' => '已删除'
    ];
    protected $order_type = [
        '1' => 'pc广告',
        '2' => '移动广告',
        '3' => '外呼',
        '4' => '数据',
        '5' => '其他',
    ];
    protected $collection_status = [
        '1' => '待收款',
        '2' => '部分收款',
        '3' => '完全收款',
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
        $this->model_customer = new Model_Customer();

        $this->assign('plan_status', $this->plan_status);
        $this->assign('order_type', $this->order_type);
        $this->assign('collection_status', $this->collection_status);

    }

    /**
     * 后台计划管理列表
     */
    public function indexAction()
    {
        $list = $this->model_plan->htPlanOrderList($this->_request->getQuery());
        $this->assign('data_action',$this->data_action);
        $this->assign('list', $list['list']);
        $this->assign('count', $list['count']);
        $this->display();
    }

    /**
     * 创建营销计划
     */
    public function createPlanAction()
    {
        if ($this->_request->isPost()) {
            $return  = $this->model_plan->createPlan($this->_request->getPost());
            if($return) {
                fn_ajax_return(1,'创建成功',$return);
            } else {
                fn_ajax_return(0,'系统繁忙,请联系管理员!');
            }
        }

        $plan_id = $this->_request->getQuery('plan_id');
        if(isset($plan_id) && $plan_id) {
            $plan = $this->model_plan->getPlanAndOrder($plan_id);
            $this->assign('plan', $plan);
        }
        $this->display();
    }

    /**
     * 验证客户
     */
    public function checkCustomerAction()
    {
        $customer_name = $this->_request->getPost('name');
        $return  = $this->model_customer->has(['AND'=>['username'=>$customer_name]]);
        if($return) {
            fn_ajax_return(1,'该客户存在');
        } else {
            fn_ajax_return(0,'未存在该客户');
        }
    }

    /**
     * 分配客户
     */
    public function assignCustomerAction()
    {
        if ($this->_request->isPost()) {
            $return  = $this->model_plan->assignCustomer($this->_request->getPost());
            if($return) {
                fn_ajax_return(1,'操作成功');
            } else {
                fn_ajax_return(0,'系统繁忙,请联系管理员!');
            }
        }

        $plan_id = $this->_request->getQuery('plan_id');
        if(!$plan_id || intval($plan_id) <= 0) {
            $this->display('index');
            Header("Location: /admin/plan/index");
        }

        //根据营销计划id,获取客户名称
        $username = $this->model_plan->getRealname($plan_id);
        $this->assign('realname', $username);

        $this->assign('plan_id', $plan_id);
        $this->display();
    }



    /**
     * 编辑营销计划
     */
    public function editPlanAction()
    {
        if ($this->_request->isPost()) {
            $return  = $this->model_plan->editPlan($this->_request->getPost());
            if($return) {
                fn_ajax_return(1,'编辑成功');
            } else {
                fn_ajax_return(0,'系统繁忙,请联系管理员!');
            }
        }
        $plan_id = $this->_request->getQuery('plan_id');
        if(!isset($plan_id) || intval($plan_id) <= 0) {
            header("Location:/admin/plan/index");
            exit();
        }
        $plan = $this->model_plan->getPlanAndOrder($plan_id);
        $this->assign('plan', $plan);
        $this->assign('plan_id', $plan_id);
        $this->display();
    }

    /**
     * 删除计划及订单
     */
    public function delPlanAction()
    {
        $plan_id = $this->_request->getPost('plan_id');
        $return  = $this->model_plan->delPlan($plan_id);

        if($return) {
            fn_ajax_return(1,'删除成功');
        } else {
            fn_ajax_return(0,'系统繁忙,请联系管理员!');
        }
    }

    /**
     * 修改状态
     */
    public function changeStatusAction()
    {
        $plan_id = $this->_request->getPost('plan_id');
        $status = $this->_request->getPost('status');
        $return  = $this->model_plan->changeStatus($plan_id,$status);
        if($return) {
            fn_ajax_return(1,'修改状态成功');
        } else {
            fn_ajax_return(0,'系统繁忙,请联系管理员!');
        }
    }
}
