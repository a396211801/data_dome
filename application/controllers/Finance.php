<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhanghuang
 * Date: 17-8-14
 * Time: 下午5:39
 * 用户财务管理
 */
class Controller_Finance extends Front
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
     * 数据列表
     */
    public function indexAction()
    {
        $data = $this->_request->getQuery();
        $list = $this->model_finance->getList($data);
        $this->assign('list',$list);
        $this->assign('collection_status',$this->collection_status);
        $this->display();
    }

    /**
     *收款明细
     */
    public function detailAction(){
        $data = $this->_request->getQuery();
        $list = $this->model_finance->detailList($data);
        $this->assign('list',$list);
        $this->display('detail');
    }
}