<?php
class Model_OrderData extends Smodel {

    protected $table = "order_data";


    /*********************************前台部分*********************************/

    /**
     * @param array $condition
     * @param int $type
     * @return array
     * 数据列表
     */
    public function getList($condition = [], $type = 1)
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $condition['type'] = $type;
        $where = $this->listCondition($condition);
        $count = $this->count($where);
        $sum_pv = $this->sum('pv',$where);
        $sum_click = $this->sum('click',$where);
        $click_rate = $sum_pv > 0 ? sprintf("%0.2f", ($sum_click / ($sum_pv)) * 100) . '%' : '0%';
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='date desc, id desc ';
        $list = $this->select('*',$where);
        $list = $this->externalConnection($list);
        return array('list'=>$list,'sum_pv'=>$sum_pv,'sum_click'=>$sum_click,'click_rate'=>$click_rate,'count'=> ceil($count / $size));
    }

    /**
     * @param $condition
     * @return array
     * 查询条件
     */
    public function listCondition($condition = [])
    {
        $model_plan = new Model_Plan();
        $model_order = new Model_Order();
        $where =array();
        $user_info = $_SESSION['customer_info'];
        $where['AND']['customer_id'] = $user_info['id'];

        if(isset($condition['username'])&& $condition['username']){
            $plan_ids = $model_plan->select('id',['AND'=>['name[~]'=>$condition['username']]]);
            if(isset($plan_ids) && $plan_ids) {
                $where['AND']['OR']['plan_id'] = $plan_ids;
            }
            
            $order_ids = $model_order->select('id',['AND'=>['name[~]'=>$condition['username']]]);
            if(isset($order_ids) && $order_ids) {
                $where['AND']['OR']['order_id'] = $order_ids;
            }
            if(empty($plan_ids) && empty($order_ids)){
                $where['AND']['id'] = -1;
            }
        }
        if(isset($condition['type']) && $condition['type'] && is_numeric($condition['type'])){
            $order_w['AND']['type'] = $condition['type'];
            $orders = $model_order->select('id',$order_w);
            if(isset($orders) && $orders) {
                $where['AND']['order_id'] = $orders;
            }else{
                $where['AND']['order_id'] = -1;
            }
        }else{
            $orders = $model_order->select('id',['type'=>1]);
            if(isset($orders) && $orders) {
                $where['AND']['order_id'] = $orders;
            }else{
                $where['AND']['order_id'] = -1;
            }
        }
        $get_time = $this->getTime($condition);
        $where['AND']['date[>=]'] = $get_time['start_date'];
        $where['AND']['date[<=]'] = $get_time['end_date'];
        return $where;
    }

    /*********************************后台部分*********************************/


    /**
     * @param array $condition
     * @param int $type
     * @return array
     * 后台数据列表
     */
    public function htGetList($condition = [], $type = 1)
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $size = isset($condition['limit']) ? $condition['limit']>0 ? $condition['limit'] : 10 : 10;
        $condition['type'] = $type;
        $where = $this->htListCondition($condition);
        $count = $this->count($where);
        $sum_pv = $this->sum('pv',$where);
        $sum_click = $this->sum('click',$where);
        $click_rate = $sum_pv > 0 ? sprintf("%0.2f", ($sum_click / ($sum_pv)) * 100) . '%' : '0%';
        $where['LIMIT'] = array(($p - 1) * $size, $size);
        $where['ORDER'] ='date desc, id desc ';
        $list = $this->select('*',$where);
        $list = $this->externalConnection($list);
        return array('list'=>$list,'sum_pv'=>$sum_pv,'sum_click'=>$sum_click,'click_rate'=>$click_rate,'count'=> ceil($count / $size));
    }

    /**
     * @param $condition
     * @return array
     * 查询条件
     */
    public function htListCondition($condition = [])
    {
        $model_customer = new Model_Customer();
        $model_plan = new Model_Plan();
        $model_order = new Model_Order();
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

            $order_ids = $model_order->select('id',['AND'=>['name[~]'=>$condition['username']]]);
            if(isset($order_ids) && $order_ids) {
                $where['AND']['OR']['order_id'] = $order_ids;
            }

            if(empty($plan_ids) && empty($order_ids) && empty($customer_ids)){
                $where['AND']['id'] = -1;
            }
        }
        if(isset($condition['type']) && $condition['type'] && is_numeric($condition['type'])){
            $order_w['AND']['type'] = $condition['type'];
            $orders = $model_order->select('id',$order_w);
            if(isset($orders) && $orders) {
                $where['AND']['order_id'] = $orders;
            }else{
                $where['AND']['order_id'] = -1;
            }
        }else{
            $orders = $model_order->select('id',['type'=>1]);
            if(isset($orders) && $orders) {
                $where['AND']['order_id'] = $orders;
            }else{
                $where['AND']['order_id'] = -1;
            }
        }
        $get_time = $this->getTime($condition);
        $where['AND']['date[>=]'] = $get_time['start_date'];
        $where['AND']['date[<=]'] = $get_time['end_date'];
        if(isset($condition['order_id']) && $condition['order_id'] && is_numeric($condition['order_id'])){
            unset($where['AND']['date[>=]']);
            unset($where['AND']['date[<=]']);
            $where['AND']['order_id'] = $condition['order_id'];
        }
        return $where;
    }
    /**
     * @param array $data
     * @return array|mixed
     * 添加数据
     */
    public function addData($data = [])
    {
        $check = $this->checkData($data);
        if($check){
            $data['date'] = strtotime($data['date']);
            $data['create_time'] = time();
            return $this->insert($data);
        }else{
            fn_ajax_return(0,'参数错误','');
        }
    }

    /**
     * @param array $data
     * @return bool|mixed
     * 根据id获取数据信息
     */
    public function getDataInfo($data = [])
    {
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        $res = $this->get(['id','date','pv','click','order_id'],['id'=>$data['id']]);
        if($res){
            $model_order = new Model_Order();
            $res['type'] = $model_order->get('type',['id'=>$res['order_id']]);
            $res['date'] = date('Y-m-d',$res['date']);
        }
        return $res;
    }

    /**
     * @param array $data
     * @return bool|int
     * 修改数据
     */
    public function editData($data = [])
    {
        if(empty($data)) fn_ajax_return(0,'参数错误','');
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        if(isset($data['pv'])){
            $update_data['pv'] = $data['pv'];
        }
        if(isset($data['click'])){
            $update_data['click'] = $data['click'];
        }
        $update_data['update_time'] = time();
        return $this->update($update_data,['AND'=>['id'=>$data['id']]]);
    }

    /**
     * @param array $data
     * @return bool|int
     * 删除数据
     */
    public function delData($data = [])
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
            $gettime['end_date'] = strtotime($condition['end_date'] .' 23:59:59');
        } else {
            $gettime['start_date'] = strtotime(date('Y-m-d', time()));
            $gettime['end_date'] = strtotime(date('Y-m-d', time()) . ' 23:59:59');
        }
        return $gettime;
    }

    /**
     * @param array $data
     * @return bool
     * 数据校验
     */
    public function checkData($data = [])
    {
        $flag = true;
        if(empty($data)) $flag = false;
        if(!isset($data['plan_id']) || !$data['plan_id']) $flag = false;
        if(!isset($data['order_id']) || !$data['order_id']) $flag = false;
        if(!isset($data['customer_id']) || !$data['customer_id']) $flag = false;
        if(!isset($data['date']) || !$data['date']) $flag = false;
        if(!isset($data['pv'])) $flag = false;
        if(!isset($data['click'])) $flag = false;
        return $flag;
    }


    /**
     * @param $list
     * @return array
     * 链接外表
     */
    private function externalConnection($list) {
        if(empty($list)) {
            return array();
        }
        $model_customer = new Model_Customer();
        $model_order = new Model_Order();
        $model_plan = new Model_Plan();
        foreach($list as $k=>$v) {
            $list[$k]['click_rate'] = $v['pv'] > 0 ? sprintf("%0.2f", ($v['click'] / ($v['pv'])) * 100) . '%' : '0%';
            $customer_where['AND']['id'] = $v['customer_id'];
            $list[$k]['username'] = $model_customer->get('username',$customer_where) ? $model_customer->get('username',$customer_where) : '-';
            $order_where['AND']['id'] = $v['order_id'];
            $order_info = $model_order->get(['name','type'],$order_where);
            $list[$k]['order_name'] = $order_info['name'];
            $plan_where['AND']['id'] = $v['plan_id'];
            $list[$k]['plan_name'] = $model_plan->get('name',$plan_where) ? $model_plan->get('name',$plan_where) : '-';
            $list[$k]['date'] = date('Y-m-d',$v['date']);
            $list[$k]['type'] = $order_info['type'];
        }
        return $list;
    }


    /**
     * 录入数据
     * @param $data
     * @return bool
     */
    public function inputData($data)
    {
        if(!$data['order_id'] || !$data['data_day'] || !$data['show_num'] || !$data['click_num']) {
            fn_ajax_return(0,'参数错误');
        } else {
            $order_id = $data['order_id'];
            $date_time = strtotime($data['data_day']);
            $show_num = $data['show_num'];
            $click_num = $data['click_num'];
        }
        $now = time();

        //判断数据是否存在
        $where_has = [
            'AND' => [
                'order_id' => $order_id,
                'date' => $date_time
            ]
        ];
        $order_data = $this->get("*",$where_has);

        if($order_data) {
            $update_date = [
                'update_time' => $now,
                'pv' => $show_num,
                'click' => $click_num,
            ];
            $return = $this->update($update_date,['AND'=>['id'=>$order_data['id']]]);
        } else {
            $model_order = new Model_Order();
            $order = $model_order->get('*',['AND'=>['id'=>$order_id]]);
            $insert_data = [
                'customer_id' => $order['customer_id'],
                'plan_id' => $order['plan_id'],
                'order_id' => $order_id,
                'date' => $date_time,
                'pv' => $show_num,
                'click' => $click_num,
                'create_time' => $now,
            ];
            $return = $this->insert($insert_data);
        }
        return $return;
    }



}