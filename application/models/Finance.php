<?php
class Model_Finance extends Smodel {

    protected $table = "finance";


    /*********************************前台部分*********************************/

    /**
     * @param array $condition
     * @return array
     * 前台收款总表
     */
    public function getList($condition = [])
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $model_plan = new Model_Plan();
        $where = $this->listCondition($condition);
        $count = $model_plan->count($where);
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='create_time desc, id desc ';
        $list = $model_plan->select("*", $where);
        $list = $this->externalConnection($list);
        return array('list'=>$list,'count'=> ceil($count / $size));
    }

    /**
     * @param array $condition
     * @return array
     * 前台收款明细表
     */
    public function detailList($condition = [])
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $where = $this->detailCondition($condition);
        $count = $this->count($where);
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='date desc, id desc ';
        $list = $this->select('*', $where);
        $list = $this->detailExt($list);
        return array('list'=>$list,'count'=> ceil($count / $size));
    }

    /**
     * @param $condition
     * @return array
     * 收款明细查询条件
     */
    public function detailCondition($condition = [])
    {
        $model_plan = new Model_Plan();
        $where =array();

        if(isset($_SESSION['customer_info'])){
            //客户
            $where['AND']['customer_id'] = $_SESSION['customer_info']['id'];
        }

        if(isset($condition['username'])&& $condition['username']){
            $plan_ids = $model_plan->select('id',['AND'=>['name[~]'=>$condition['username']]]);
            if(isset($plan_ids) && $plan_ids) {
                $where['AND']['OR']['plan_id'] = $plan_ids;
            }

            if(empty($plan_ids)){
                $where['AND']['id'] = -1;
            }
        }
        $get_time = $this->getTime($condition);
        $where['AND']['date[>=]'] = $get_time['start_date'];
        $where['AND']['date[<=]'] = $get_time['end_date'];
        return $where;
    }

    /**
     * @param $condition
     * @return array
     * 前台总览查询条件
     */
    public function listCondition($condition = [])
    {
        $user_info = $_SESSION['customer_info'];
        $where =array();
        $where['AND']['customer_id'] = $user_info['id'];
        if(isset($condition['username'])&& $condition['username']){
            $where['AND']['OR']['name[~]'] = $condition['username'];
        }
        if(isset($condition['collection_status']) && $condition['collection_status']){
            $where['AND']['collection_status'] = $condition['collection_status'];
        }
        $get_time = $this->getTime($condition);
        $where['AND']['create_time[>=]'] = $get_time['start_date'];
        $where['AND']['create_time[<=]'] = $get_time['end_date'];
        return $where;
    }

    /*********************************后台部分*********************************/


    /**
     * @param array $condition
     * @return array
     * 后台收款总表
     */
    public function htGetList($condition = [])
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $model_plan = new Model_Plan();
        $where = $this->htListCondition($condition);
        $count = $model_plan->count($where);
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='create_time desc, id desc ';
        $list = $model_plan->select("*", $where);
        $list = $this->externalConnection($list);
        return array('list'=>$list,'count'=> ceil($count / $size));
    }

    /**
     * @param array $condition
     * @return array
     * 后台收款明细表
     */
    public function htDetailList($condition = [])
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $where = $this->htDetailCondition($condition);
        $count = $this->count($where);
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='date desc, id desc ';
        $list = $this->select('*', $where);
        $list = $this->detailExt($list);
        return array('list'=>$list,'count'=> ceil($count / $size));
    }

    /**
     * @param $condition
     * @return array
     * 后台收款明细查询条件
     */
    public function htDetailCondition($condition = [])
    {
        $model_customer = new Model_Customer();
        $model_plan = new Model_Plan();
        $where =array();
        if(isset($condition['username'])&& $condition['username']){
            $plan_ids = $model_plan->select('id',['AND'=>['name[~]'=>$condition['username']]]);
            if(isset($plan_ids) && $plan_ids) {
                $where['AND']['OR']['plan_id'] = $plan_ids;
            }

            $customer_ids = $model_customer->select('id',['AND'=>['username[~]'=>$condition['username']]]);
            if(isset($customer_ids) && $customer_ids) {
                $where['AND']['OR']['customer_id'] = $customer_ids;
            }
            if(empty($plan_ids) && empty($customer_ids)){
                $where['AND']['id'] = -1;
            }
        }
        $get_time = $this->getTime($condition);
        $where['AND']['date[>=]'] = $get_time['start_date'];
        $where['AND']['date[<=]'] = $get_time['end_date'];
        return $where;
    }

    /**
     * @param $condition
     * @return array
     * 后台总览查询条件
     */
    public function htListCondition($condition = [])
    {
        $model_customer = new Model_Customer();
        $where =array();
        if(isset($condition['username'])&& $condition['username']){
            $where['AND']['OR']['name[~]'] = $condition['username'];

            $customer_ids = $model_customer->select('id',['AND'=>['username[~]'=>$condition['username']]]);
            if(isset($customer_ids) && $customer_ids) {
                $where['AND']['OR']['customer_id'] = $customer_ids;
            }
        }
        if(isset($condition['collection_status']) && $condition['collection_status']){
            $where['AND']['collection_status'] = $condition['collection_status'];
        }
        $get_time = $this->getTime($condition);
        $where['AND']['create_time[>=]'] = $get_time['start_date'];
        $where['AND']['create_time[<=]'] = $get_time['end_date'];
        return $where;
    }


    /**
     * @param array $data
     * @return bool|int
     * 修改收款明细
     */
    public function editDetail($data = [])
    {
        if(empty($data)) fn_ajax_return(0,'参数错误','');
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        $update_data['money'] = $data['money'];
        $update_data['update_time'] = time();
        return $this->update($update_data,['AND'=>['id'=>$data['id']]]);
    }

    /**
     * @param array $data
     * @return bool|int
     * 获取计划收款状态
     */
    public function getStatus($data = [])
    {
        if(empty($data)) fn_ajax_return(0,'参数错误','');
        if(!isset($data['plan_id'])) fn_ajax_return(0,'参数错误','');
        $model_plan = new Model_Plan();
        return $model_plan->get('*',['AND'=>['id'=>$data['plan_id']]]);
    }

    /**
     * @param array $data
     * @return array|mixed
     * 添加收款
     */
    public function addReceivables($data = [])
    {
        if(empty($data)) fn_ajax_return(0,'参数错误','');
        //先查询是否存在
        $where = [
            'AND'=>[
                'plan_id'=>$data['pid'],
                'date'=>strtotime($data['date']),
            ]
        ];
        $info = $this->get('id',$where);
        if($info){
            fn_ajax_return(0,'该日期数据已存在,请直接修改','');
        }else{
            $model_plan = new Model_Plan();
            $customer_id = $model_plan->get('customer_id',['id'=>$data['pid']]);
            $data['create_time'] = time();
            $data['plan_id'] = $data['pid'];
            $data['customer_id'] = $customer_id;
            $data['date'] = strtotime($data['date']);
            unset($data['pid']);
            return $this->insert($data);
        }
    }

    /**
     * @param array $data
     * @return bool|mixed
     * 根据id获取数据信息
     */
    public function getFinanceInfo($data = [])
    {
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        $res = $this->get(['date','money'],['id'=>$data['id']]);
        if($res){
            $res['date'] = date('Y-m-d',$res['date']);
        }
        return $res;
    }

    /**
     * @param array $data
     * @return bool|int
     * 修改计划状态
     */
    public function editStatus($data = []){
       if(!isset($data['plan_id']) || !$data['plan_id'] || !isset($data['collection_status']) || !$data['collection_status']){
           fn_ajax_return(0,'参数错误','');
       }
       $model_plan = new Model_Plan();
       $update_data['collection_status'] = $data['collection_status'];
       $update_data['update_time'] = time();
       return $model_plan->update($update_data,['id'=>$data['plan_id']]);
    }

    /**
     * @param array $data
     * @return bool|int
     * 删除收款记录
     */
    public function delRecord($data = [])
    {
        if(!isset($data['id']) || !$data['id']) fn_ajax_return(0,'参数错误','');
        return $this->delete(['AND'=>['id'=>$data['id']]]);
    }

    /**
     * @param array $condition
     * @return mixed
     * 获取时间
     */
    private function getTime($condition = [])
    {
        if (isset($condition['start_date']) && $condition['start_date'] && isset($condition['end_date']) && $condition['end_date']) {
            $gettime['start_date'] = strtotime($condition['start_date']);
            $gettime['end_date'] = strtotime($condition['end_date'] . ' 23:59:59');
        } else {
            $gettime['start_date'] = strtotime(date('Y-m-01', time()));
            $gettime['end_date'] = strtotime(date('Y-m-t', time()) . ' 23:59:59');
        }
        return $gettime;
    }


    /**
     * @param $list
     * @return array
     * 收款总表链接外表
     */
    private function externalConnection($list) {
        if(empty($list)) {
            return array();
        }
        $model_customer = new Model_Customer();
        foreach($list as $k=>$v) {
            $customer_where['AND']['id'] = $v['customer_id'];
            $list[$k]['username'] = $model_customer->get('username',$customer_where) ? $model_customer->get('username',$customer_where) : '-';
            $list[$k]['date'] = isset($v['create_time']) ? date('Y-m-d H:i:s',$v['create_time']) : '-';
            $list[$k]['price'] = isset($v['price']) ? $v['price'] : '-';
            $data_where['plan_id'] =  $v['id'];
            $money = $this->sum('money',$data_where);
            $list[$k]['money'] = $money;
        }
        return $list;
    }

    /**
     * @param $list
     * @return array
     * 收款明细链接外表
     */
    private function detailExt($list) {
        if(empty($list)) {
            return array();
        }
        $model_customer = new Model_Customer();
        $model_plan = new Model_Plan();
        foreach($list as $k=>$v) {
            $customer_where['AND']['id'] = $v['customer_id'];
            $list[$k]['username'] = $model_customer->get('username',$customer_where) ? $model_customer->get('username',$customer_where) : '-';
            $plan_where['AND']['id'] = $v['plan_id'];
            $plan_info = $model_plan->get('*',$plan_where);
            $list[$k]['plan_name'] = isset($plan_info['name']) ? $plan_info['name'] : '-';
            $list[$k]['date'] = date('Y-m-d',$v['date']);
            $list[$k]['price'] = isset($plan_info['price']) ? $plan_info['price'] : '-';
        }
        return $list;
    }
}