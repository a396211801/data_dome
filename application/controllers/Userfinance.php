<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhanghuang
 * Date: 17-8-14
 * Time: 下午5:39
 * 用户财务管理
 */
class Controller_Userfinance extends Front
{
    private $model_finance;

    public function init()
    {
        parent::init();
        $this->model_finance = new Model_Finance();
    }

    /**
     * 数据列表
     */
    public function indexAction()
    {
        if ($this->_request->isPost()) {
            $list = $this->model_finance->getList($this->_request->getPost());
            fn_ajax_return(1, '获取成功', $list);
        }
    }
}