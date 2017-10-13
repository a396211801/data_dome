<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhanghuang
 * Date: 17-8-14
 * Time: 下午5:06
 * 数据管理
 */
class Controller_Data extends Admin
{
    protected $layout = 'base';
    private $model_orderData;

    public function init()
    {
        parent::init();
        $this->model_orderData = new Model_OrderData();
    }

    /**
     * pc广告
     */
    public function indexAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->htGetList($data);
        $this->assign('data','data');
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 移动广告
     */
    public function mobileadvertAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->htGetList($data,2);
        $this->assign('data','data');
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 外呼
     */
    public function outboundAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->htGetList($data,3);
        $this->assign('data','data');
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 数据
     */
    public function dataAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->htGetList($data,4);
        $this->assign('data','data');
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 其他
     */
    public function otherAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->htGetList($data,5);
        $this->assign('data', 'data');
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 添加数据
     */
    public function addDataAction(){
        if ($this->_request->isPost()) {
            $res = $this->model_orderData->addData($this->_request->getPost());
            if($res) fn_ajax_return(1,'添加成功','');
            fn_ajax_return(0,'添加失败','');
        }
    }

    /**
     * 修改数据
     */
    public function editDataAction(){
        if ($this->_request->isPost()) {
            $res = $this->model_orderData->editData($this->_request->getPost());
            if($res) fn_ajax_return(1, '修改成功','');
            fn_ajax_return(0, '修改失败','');
         }else{
            $res = $this->model_orderData->getDataInfo($this->_request->getQuery());
            if($res) fn_ajax_return(1, '获取成功',$res);
            fn_ajax_return(0, '获取失败','');
        }
    }

    /**
     * 删除数据
     */
    public function delDataAction(){
        if ($this->_request->isPost()) {
            $res= $this->model_orderData->delData($this->_request->getPost());
            if($res) fn_ajax_return(1, '删除成功','');
            fn_ajax_return(0, '删除失败','');
        }
    }
}