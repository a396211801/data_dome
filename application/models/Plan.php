<?php
class Model_Plan extends Smodel {
    protected $table = "plan";
    protected $plan_status_num = [1,2,3,4,99];
    /**
     * 前台首页
     * 计划，订单等数据
     */
    public function planOrderList($condition)
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $r = isset($condition['limit']) && $condition['limit'] > 0 ? $condition['limit'] : 5;

        $where = $this->listCondition($condition);
        $count = $this->count($where);

        $where['ORDER'] = ' create_time desc,id desc';
        $where['LIMIT'] = array(($p - 1) * $r, $r);
        $list = $this->select("*", $where);
        $list = $this->extConn($list);
        return array('list' => $list, 'count'=> ceil($count / $r));
    }

    /**
     * 条件判断
     */
    public function listCondition($condition)
    {
        $customer_info = $_SESSION['customer_info'];
        $customer_id = $customer_info['id'];

        $where['AND']['is_del'] = 0;
        $where['AND']['is_complete'] = 1;
        $where['AND']['customer_id'] = $customer_id;

        //计划名称
        if(isset($condition['name']) && $condition['name']) {
            $where['AND']['name[~]'] = $condition['name'];
        }

        //状态
        if(isset($condition['status']) && $condition['status']) {
            $where['AND']['status'] = $condition['status'];
        }
        return $where;
    }

    /**
     * 连接外表
     */
    public function extConn($list)
    {
        if(!$list) {
            return [];
        }
        $model_order = new Model_Order();
        $model_order_data = new Model_OrderData();

        foreach ($list as $k=>$v) {

            $order = $model_order->select("*",['AND'=>['plan_id'=>$v['id'],'is_del'=>0]]);
            if(isset($order) && $order) {
                foreach($order as $n=>$m) {
                    $completed = $model_order_data->sum('pv',['AND'=>['order_id'=>$m['id']]]);
                    $order[$n]['completed'] = $completed;
                    $order[$n]['completed_probability'] = round($completed/($m['target_cpm']*1000)*100,2)."%";
                }
            }
            $list[$k]['order_list'] = $order;
        }
        return $list;
    }

    /*********************************后台*********************************/
    /**
     * 后台订单管理列表
     * 计划，订单等数据
     */
    public function htPlanOrderList($condition)
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $r = isset($condition['limit']) && $condition['limit'] > 0 ? $condition['limit'] : 10;

        $where = $this->htListCondition($condition);
        $count = $this->count($where);

        $where['ORDER'] = ' create_time desc,id desc';
        $where['LIMIT'] = array(($p - 1) * $r, $r);
        $list = $this->select("*", $where);
        $list = $this->htExtConn($list);
        return array('list' => $list, 'count'=> ceil($count / $r));
    }

    /**
     * 条件判断
     */
    public function htListCondition($condition)
    {
        $where['AND']['is_complete'] = 1;

        //计划名称
        if(isset($condition['name']) && $condition['name']) {
            $where['AND']['name[~]'] = $condition['name'];
        }

        //状态
        if(isset($condition['status']) && $condition['status']) {
            if($condition['status'] == 99) {
                $where['AND']['is_del'] = 1;
            } else {
                $where['AND']['is_del'] = 0;
                $where['AND']['status'] = $condition['status'];
            }
        }
        return $where;
    }

    /**
     * 连接外表
     */
    public function htExtConn($list)
    {
        if(!$list) {
            return [];
        }
        $model_order = new Model_Order();
        $model_order_data = new Model_OrderData();
        $model_customer = new Model_Customer();

        foreach ($list as $k=>$v) {

            $user_realname = $model_customer->get('realname',['AND'=>['id'=>$v['customer_id']]]);
            $list[$k]['user_realname'] = isset($user_realname) && $user_realname ? $user_realname : '';

            $order = $model_order->select("*",['AND'=>['plan_id'=>$v['id']]]);
            if(isset($order) && $order) {
                foreach($order as $n=>$m) {
                    $completed = $model_order_data->sum('pv',['AND'=>['order_id'=>$m['id']]]);
                    $order[$n]['completed'] = $completed;
                    $order[$n]['completed_probability'] = round($completed/$m['target_cpm']*100,2)."%";
                }
            }
            $list[$k]['order_list'] = $order;
        }
        return $list;
    }

    /**
     * 创建计划
     */
    public function createPlan($data)
    {
        $data = $data['params'];

        $model_order = new Model_Order();
        $this->checkCreatePlan($data);
        $datas = $this->getCreateProcessData($data);

        $plan_data = $datas['plan_data'];
        $order_data = $datas['order_data'];

        $createPlan = $this->insert($plan_data);
        if($createPlan) {
            foreach($order_data as $k=>$v) {
                $order_data[$k]['plan_id'] = $createPlan;
            }

            $createOrder = $model_order->insert($order_data);
            if($createOrder) {
                return $createPlan;
            } else{
                $this->delete(['AND'=>['id'=>$createPlan]]);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 验证新增数据
     */
    private function checkCreatePlan($data)
    {
        //验证计划数据
        if(!isset($data['name']) || !$data['name']) {
            fn_ajax_return(0,'计划名称必填!');
        }
        if(!isset($data['business']) || !$data['business']) {
            fn_ajax_return(0,'商务/市场必填!');
        }
        $is_exist = $this->has(['AND'=>['name'=>$data['name'],'is_complete'=>1]]);
        if($is_exist) {
            fn_ajax_return(0,'计划名称已存在!');
        }

        //验证订单
        $model_order = new Model_Order();
        $order_name = [];
        $order_list = $data['order_list'];
        if(count($order_list) < 1) {
            fn_ajax_return(0,'订单必填!');
        }

        foreach ($order_list as $k=>$v) {
            if(!isset($v['type']) || !$v['type']) {
                fn_ajax_return(0,'订单类型必填!');
            }
            if(!isset($v['name']) || !$v['name']) {
                fn_ajax_return(0,'订单名称必填!');
            }
            if(!isset($v['target_cpm']) || !$v['target_cpm']) {
                fn_ajax_return(0,'目标cpm必填!');
            }
            if(in_array($v['name'],$order_name)) {
                fn_ajax_return(0,'订单名称重复!');
            } else {
                $order_name[] = $v['name'];
            }

            $is_exist = $model_order->has(['AND'=>['name'=>$v['name']]]);
            if($is_exist) {
                fn_ajax_return(0,'订单名称已存在!');
            }
        }
    }

    /**
     * 新增处理数据
     */
    public function getCreateProcessData($data)
    {
        $now = time();

        //处理计划数据
        $plan_data = [
            'name' => $data['name'],
            'business' => $data['business'],
            'create_time' => $now,
        ];

        if(isset($data['price']) && $data['price']) {
            $plan_data['price'] = $data['price'];
        }
        if(
            isset($data['start_date'])
            && $data['start_date']
            &&isset($data['end_date'])
            && $data['end_date']
        ) {
            $plan_data['start_date'] = strtotime($data['start_date']);
            $plan_data['end_date'] = strtotime($data['end_date']);
        }
        if(isset($data['explain']) && $data['explain']) {
            $plan_data['explain'] = $data['explain'];
        }

        //处理订单数据
        $order_data = [];
        $order_list = $data['order_list'];
        foreach($order_list as $k=>$v) {
            $order_data[$k]['type'] = $v['type'];
            $order_data[$k]['name'] = $v['name'];
            $order_data[$k]['target_cpm'] = $v['target_cpm'];
            if(isset($v['explain']) && $v['explain']) {
                $order_data[$k]['explain'] = $v['explain'];
            }
            $order_data[$k]['create_time'] = $now;
        }

        return ['plan_data'=>$plan_data,'order_data'=>$order_data];
    }

    /**
     * 获取单个营销计划和计划
     */
    public function getPlanAndOrder($id)
    {
        $where = [
            'AND'=>[
                'id' => $id,
            ]
        ];
        $data = $this->get("*", $where);
        if(isset($data) && $data) {
            $model_order = new Model_Order();
            $order_list = $model_order->select("*",['AND'=>['plan_id'=>$data['id'],'is_del'=>0]]);
            $data['order_list'] = $order_list;
            return $data;
        } else {
            return [];
        }
    }

    /**
     * 编辑营销计划和订单
     */
    public function editPlan($data)
    {
        $now = time();
        $data = $data['params'];

        $model_order = new Model_Order();
        $this->checkEditPlan($data);
        $datas = $this->getEditProcessData($data);

        $customer_id = $this->get('customer_id',['AND'=>['id'=>$data['id']]]);

        $plan_data = $datas['plan_data'];
        $plan_id = $plan_data['id'];

        unset($plan_data['id']);

        $order_data = $datas['order_data'];

        $updatePlan = $this->update($plan_data,['AND'=>['id'=>$plan_id]]);
        if($updatePlan) {
            $order_ids = [];
            foreach($order_data as $k=>$v) {
                $order_data[$k]['plan_id'] = $plan_id;
                if(isset($v['id']) && $v['id']) {
                    $order_data[$k]['update_time'] = $now;
                    $order_ids[] = $v['id'];
                } else {
                    $order_data[$k]['customer_id'] = $customer_id;
                    $order_data[$k]['plan_id'] = $plan_id;
                    $order_data[$k]['create_time'] = $now;
                }
            }

            $order_ids_before = $model_order->select('id',['AND'=>['plan_id'=>$plan_id]]);

            foreach ($order_ids_before as $k=>$v) {
                if(!in_array($v,$order_ids)) {
                    $model_order->update(['is_del'=>1,'update_time'=>$now,'del_time'=>$now],['AND'=>['id'=>$v]]);
                }
            }

            foreach($order_data as $k=>$v) {
                if(isset($v['id']) && $v['id']) {
                    $model_order->update($v,['AND'=>['id'=>$v['id']]]);
                } else {
                    $model_order->insert($v);
                }
            }
            return true;

        } else {
            return false;
        }
    }

    /**
     * 验证编辑数据
     */
    private function checkEditPlan($data)
    {
        //验证计划数据
        if(!isset($data['id']) || !$data['id']) {
            fn_ajax_return(0,'参数错误!');
        }
        if(!isset($data['name']) || !$data['name']) {
            fn_ajax_return(0,'计划名称必填!');
        }
        if(!isset($data['business']) || !$data['business']) {
            fn_ajax_return(0,'商务/市场必填!');
        }
        $is_exist = $this->has(['AND'=>['name'=>$data['name'],'id[!]'=>$data['id']]]);
        if($is_exist) {
            fn_ajax_return(0,'计划名称已存在!');
        }

        //验证订单
        $model_order = new Model_Order();
        $order_name = [];
        $order_list = $data['order_list'];
        if(count($order_list) < 1) {
            fn_ajax_return(0,'订单必填!');
        }

        foreach ($order_list as $k=>$v) {

            if(!isset($v['type']) || !$v['type']) {
                fn_ajax_return(0,'订单类型必填!');
            }
            if(!isset($v['name']) || !$v['name']) {
                fn_ajax_return(0,'订单名称必填!');
            }
            if(!isset($v['target_cpm']) || !$v['target_cpm']) {
                fn_ajax_return(0,'目标cpm必填!');
            }
            if(in_array($v['name'],$order_name)) {
                fn_ajax_return(0,'订单名称重复!');
            } else {
                $order_name[] = $v['name'];
            }

            if(isset($v['id']) && $v['id']) {
                $is_exist = $model_order->has(['AND'=>['name'=>$v['name'],'id[!]'=>$v['id']]]);
                if($is_exist) {
                    fn_ajax_return(0,'订单名称已存在!');
                }
            } else {
                $is_exist = $model_order->has(['AND'=>['name'=>$v['name']]]);
                if($is_exist) {
                    fn_ajax_return(0,'订单名称已存在!');
                }
            }
        }
    }

    /**
     * 编辑处理数据
     */
    public function getEditProcessData($data)
    {
        $now = time();

        //处理计划数据
        $plan_data = [
            'id' => $data['id'],
            'name' => $data['name'],
            'business' => $data['business'],
            'update_time' => $now,
        ];

        if(isset($data['price']) && $data['price']) {
            $plan_data['price'] = $data['price'];
        }
        if(
            isset($data['start_date'])
            && $data['start_date']
            &&isset($data['end_date'])
            && $data['end_date']
        ) {
            $plan_data['start_date'] = strtotime($data['start_date']);
            $plan_data['end_date'] = strtotime($data['end_date']);
        }
        if(isset($data['explain']) && $data['explain']) {
            $plan_data['explain'] = $data['explain'];
        }

        //处理订单数据
        $order_data = [];
        $order_list = $data['order_list'];
        foreach($order_list as $k=>$v) {
            $order_data[$k]['type'] = $v['type'];
            $order_data[$k]['name'] = $v['name'];
            $order_data[$k]['target_cpm'] = $v['target_cpm'];
            if(isset($v['explain']) && $v['explain']) {
                $order_data[$k]['explain'] = $v['explain'];
            }
            if(isset($v['id']) && $v['id']) {
                $order_data[$k]['id'] = $v['id'];
            }
        }

        return ['plan_data'=>$plan_data,'order_data'=>$order_data];
    }

    /**
     * 删除计划及订单
     */
    public function delPlan($plan_id)
    {
        $return = false;

        if(isset($plan_id) || intval($plan_id) > 0 ) {
            $now = time();
            $update_data = [
                'is_del' => 1,
                'del_time' => $now,
                'update_time' => $now,
            ];
            $has_plan = $this->has(['AND'=>['id'=>$plan_id]]);
            if($has_plan) {
                $this->begin();
                $del_plan = $this->update($update_data,['AND'=>['id'=>$plan_id]]);
                if($del_plan) {
                    $model_order = new Model_Order();
                    $del_order = $model_order->update($update_data,['AND'=>['plan_id'=>$plan_id]]);
                    if($del_order){
                        $this->commit();
                        $return = true;
                    }else{
                        $this->rollback();
                        $return = false;
                    }
                } else {
                    $return = false;
                }
            }
        }
        return $return;
    }

    /**
     * 修改营销计划状态
     */
    public function changeStatus($plan_id,$status)
    {
        $now = time();
        //判断参数是否正确
        if(intval($plan_id) <= 0 || !in_array($status,$this->plan_status_num)) {
            fn_ajax_return(0,'非法操作');
        }

        if($status == 99 ){
            $update_data = [
                'update_time' => $now,
                'del_time' => $now,
                'is_del' => 1,
            ];
        } else {
            $update_data = [
                'status' => $status,
                'update_time' => $now,
                'del_time' => 0,
                'is_del' => 0,
            ];
        }
        $update = $this->update($update_data,['AND'=>['id'=>$plan_id]]);

        if($update) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 分配客户
     */
    public function assignCustomer($data)
    {
        if(!$data['name'] || !$data['plan_id']) {
            fn_ajax_return(0,'参数错误');
        } else {
            $name = $data['name'];
            $plan_id = $data['plan_id'];
        }
        $now = time();
        $model_customer = new Model_Customer();
        $model_order = new Model_Order();


        $customer  = $model_customer->get('*',['AND'=>['username'=>$name]]);

        if(!$customer) {
            fn_ajax_return(2,'不存在该客户');
        }

        $update_plan_data = [
            'customer_id' => $customer['id'],
            'update_time' => $now,
            'is_complete' => 1,
        ];

        $update_order_data = [
            'customer_id' => $customer['id'],
            'update_time' => $now
        ];

        $this->begin();
        $update_plan = $this->update($update_plan_data,['AND'=>['id'=>$plan_id]]);
        $update_order = $model_order->update($update_order_data,['AND'=>['plan_id'=>$plan_id]]);

        if($update_plan && $update_order) {
            $this->commit();
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }

    /**
     * 根据营销计划id,获取客户名称
     */
    public function getRealname($plan_id)
    {
        $customer_id = $this->get('customer_id',['AND'=>['id'=>$plan_id]]);

        if($customer_id) {
            $model_customer = new Model_Customer();
            $realname = $model_customer->get('username',['AND'=>['id'=>$customer_id]]);
            return $realname;
        } else {
            return '';
        }
    }
}