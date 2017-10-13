<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhanghuang
 * Date: 17-8-14
 * Time: 下午5:29
 * 用户数据管理
 */
class Controller_Data extends Front
{
    protected $layout = 'base';
    private $model_orderData;

    public function init()
    {
        parent::init();
        $this->model_orderData = new Model_OrderData();
    }

    /**
     *pc广告
     */
    public function indexAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->getList($data);
        $this->assign('list',$list);
        $this->display();
    }
    /**
     * 移动广告
     */
    public function mobileadvertAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->getList($data,2);
        $this->assign('list',$list);
        $this->display();
    }
    /**
     * 外呼
     */
    public function outboundAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->getList($data,3);
        $this->assign('list',$list);
        $this->display();
    }
    /**
     * 数据
     */
    public function dataAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->getList($data,4);
        $this->assign('list',$list);
        $this->display();
    }
    /**
     * 其他
     */
    public function otherAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_orderData->getList($data,5);
        $this->assign('list',$list);
        $this->display();
    }
}