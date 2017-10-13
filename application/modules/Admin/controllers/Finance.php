<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhanghuang
 * Date: 17-8-14
 * Time: 下午5:17
 * 财务管理
 */
class Controller_Finance extends Admin
{
    protected $layout = 'base';
    private $model_finance;
    protected $collection_status = [
        '1' => '待收款',
        '2' => '部分收款',
        '3' => '完全收款',
    ];

    public function init()
    {
        parent::init();
        $this->model_finance = new Model_Finance();
    }

    /**
     * 财务管理
     */
    public function indexAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_finance->htGetList($data);
        $this->assign('collection_status',$this->collection_status);
        $this->assign('list',$list);
        $this->assign('data','finance');
        $this->display();
    }

    /**
     *收款明细
     */
    public function detailAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_finance->htDetailList($data);
        $this->assign('list',$list);
        $this->assign('data','finance');
        $this->display();
    }

    /**
     * 添加收款
     */
    public function addReceivablesAction(){
        if ($this->_request->isPost()) {
//        $data = [
//            'plan_id'=>2,
//            'date'=>'2017-08-16',
//            'money'=>500,
//        ];
            $res = $this->model_finance->addReceivables($this->_request->getPost());
            if($res) fn_ajax_return(1, '添加成功','');
            fn_ajax_return(0, '添加失败','');
        }
    }

    /**
     * 修改状态
     */
    public function editStatusAction(){
        if ($this->_request->isPost()) {
//        $data = [
//            'plan_id' =>1,
//            'collection_status'=>1,
//        ];
            $res = $this->model_finance->editStatus($this->_request->getPost());
            if($res) fn_ajax_return(1, '修改成功','');
            fn_ajax_return(0, '修改失败','');
        }else{
            $res = $this->model_finance->getStatus($this->_request->getQuery());
            if($res) fn_ajax_return(1, '获取成功',$res);
            fn_ajax_return(0, '获取失败','');
        }
    }

    /**
     * 修改收款明细
     */
    public function editDetailAction(){
        if ($this->_request->isPost()) {
//        $data = [
//            'id'=>3,
//            'money'=>666.66
//        ];
            $res = $this->model_finance->editDetail($this->_request->getPost());
            if($res) fn_ajax_return(1, '修改成功','');
            fn_ajax_return(0, '修改失败','');
        }else{
            $res = $this->model_finance->getFinanceInfo($this->_request->getQuery());
            if($res) fn_ajax_return(1, '获取成功',$res);
            fn_ajax_return(0, '获取失败','');
        }
    }

    /**
     * 删除收款记录
     */
    public function delRecordAction(){
        if ($this->_request->isPost()) {
//        $data =[
//            'id'=>2
//        ];
            $res= $this->model_finance->delRecord($this->_request->getPost());
            if($res) fn_ajax_return(1, '删除成功','');
            fn_ajax_return(0, '删除失败','');
        }
    }
}