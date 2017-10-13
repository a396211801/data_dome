<?php
class Model_Order extends Smodel {
    protected $table = "order";

    /**
     * 后台订单列表
     * @param $type
     * @return array
     */
    public function htGetList($condition, $type = 1)
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $r = isset($condition['limit']) && $condition['limit'] > 0 ? $condition['limit'] : 10;
        $condition['type'] = $type;
        $where = $this->htListCondition($condition);
        $count = $this->count($where);

        $where['ORDER'] = ' create_time desc,id desc';
        $where['LIMIT'] = array(($p - 1) * $r, $r);
        $list = $this->select("*", $where);
        $return = $this->htExtConn($list);

        return [
            'list' => $return['list'],
            'all_pv' => $return['all_pv'],
            'all_click' => $return['all_click'],
            'all_click_rate' => $return['all_click_rate'],
            'count' => ceil($count / $r)
        ];

    }

    /**
     * 条件判断
     */
    public function htListCondition($condition)
    {

        //状态
        if(isset($condition['type']) && $condition['type']) {
            $where['AND']['type'] = $condition['type'];
        } else {
            $where['AND']['type'] = 1;
        }

        //计划名称
        if(isset($condition['name']) && $condition['name']) {

            $model_plan = new Model_Plan();

            $where_plan = [
                'AND' => [
                    'name[~]' => $condition['name']
                ]
            ];
            $plan_ids = $model_plan->select('id',$where_plan);
            if(!$plan_ids) {
                $plan_ids = -1;
            }

            $where['AND']['or']['plan_id'] = $plan_ids;
            $where['AND']['or']['name[~]'] = $condition['name'];
        }
        return $where;
    }

    /**
     * 连接外表
     */

    public function htExtConn($list)
    {
        //展现量
        $all_pv = 0;
        //点击量
        $all_click = 0;
        //总点击率
        $all_click_rate = '0%';

        if(!$list) {
            return [
                'list' => [],
                'all_pv' => $all_pv,
                'all_click' => $all_click,
                'all_click_rate' => $all_click_rate,
            ];
        }

        $model_order_data = new Model_OrderData();
        $model_plan = new Model_Plan();
        $model_customer = new Model_Customer();

        $return = [];
        foreach($list as $k=>$v) {
            $return[$k]['id'] = $v['id'];  //订单id
            $return[$k]['realname'] = $model_customer->get('realname',['AND'=>['id'=>$v['customer_id']]]);  //客户名称
            $return[$k]['plan_name'] = $model_plan->get('name',['AND'=>['id'=>$v['plan_id']]]);  //计划名称
            $return[$k]['order_name'] = $v['name'];  //订单名称
            $return[$k]['target_cpm'] = $v['target_cpm']*1000;  //目标cpm(需要显示成目标展现量)
            $sum_cpm = $model_order_data->sum('pv',['AND'=>['order_id'=>$v['id']]]);
            $sum_click = $model_order_data->sum('click',['AND'=>['order_id'=>$v['id']]]); //累计点击量
            $return[$k]['sum_cpm'] = $sum_cpm; //累计展现量
            $return[$k]['sum_click'] = $sum_click; //累计展现量
            if($sum_cpm > 0) {
                $click_rate = round($sum_click/$sum_cpm*100,2)."%";
            } else {
                $click_rate = "0%";
            }

            if($v['target_cpm'] > 0) {
                $completion_rate = round($sum_cpm/($v['target_cpm']*1000)*100,2)."%";
            } else {
                $completion_rate = "0%";
            }

            $return[$k]['click_rate'] = $click_rate; //总点击率
            $return[$k]['completion_rate'] = $completion_rate; //完成率

            $return[$k]['is_del'] = $v['is_del'];
            $return[$k]['create_date'] = date("Y-m-d",$v['create_time']);
            $all_pv += $sum_cpm;
            $all_click += $sum_click;
        }


        if($all_pv > 0) {
            $all_click_rate = round($all_click/$all_pv*100,2)."%";
        }

        return [
            'list' => $return,
            'all_pv' => $all_pv,
            'all_click' => $all_click,
            'all_click_rate' => $all_click_rate,
        ];
    }

    /**
     * @param array $data
     * @return bool|int
     * 修改订单状态
     */
    public function editStatus($data = []){
        if(!isset($data['order_id']) || !$data['order_id'] || !isset($data['is_del'])){
            fn_ajax_return(0,'参数错误','');
        }
        $update_data['is_del'] = $data['is_del'];
        $update_data['update_time'] = time();
        if($data['is_del'] == 1){
            $update_data['del_time'] = time();
        }else{
            $update_data['del_time'] = 0;
        }
        return $this->update($update_data,['id'=>$data['order_id']]);
    }

    /**
     * @param array $data
     * @return bool|int
     * 获取订单状态
     */
    public function getStatus($data = [])
    {
        if(empty($data)) fn_ajax_return(0,'参数错误','');
        if(!isset($data['order_id'])) fn_ajax_return(0,'参数错误','');
        return $this->get('*',['AND'=>['id'=>$data['order_id']]]);
    }

}