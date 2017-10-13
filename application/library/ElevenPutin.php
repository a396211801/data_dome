<?php
/**
 * 推送
 * @author zhengzhe
 * @date 2015-08-05
 */
class ElevenPutin
{
    private $user_url = 'http://login.juhuisuan.com/interface/ht';  //接口

    private $error = array();   //用于存放错误信息
    private $open;  //开关（true开，false关）
    private $status;  //开关具体标识（1，0）
    private $advert_id; //预留创意存放创意ID
    private $tra_type; //渠道类型
    private $advert_info;   //预留存放具体创意信息
    private $strategy_info;   //预留存放具体创意信息
    private $put_status = 0;    //预留存放状态
    private $redis;

    //模型
    private $model_strategy;
    private $model_advert;

    //键
    private $prefix_key_tratype      = 'TRA_TYPE_';       //渠道
    private $prefix_key_trasize      = 'TRA_SIZE_';       //双十一横幅的尺寸
    private $prefix_key_tags         = 'TAGS_';       //双十一横幅的尺寸

    //正确和错误信息
    private $info = array(
        '1001' => array('msg' => '没有投放创意'), // > 数据不存在
        '1002' => array('msg' => '审核未通过'),
        '1003' => array('msg' => '无可投放创意', 'put_status' => 6),
        '1005' => array('msg' => '未通过审核', 'put_status' => 8),
        '2000' => array('msg' => '余额不足', 'put_status' => 2), // > 2xxx
        '4444' => array('msg' => '数据更新失败'),
        '9999' => array('msg' => '正在投放', 'put_status' => 1),    //打开投放
        '1111' => array('msg' => '未投放', 'put_statys' => 0),
        '2222' => array('msg' => '无此渠道')
    );

    //渠道类型
    private $tra_type_arr = array(1,2,3,4);

    //各种初始化
    //id为创意id，type为渠道type
    public function __construct($id, $tra_type)
    {
        $this->redis        = new Component_Redis();
        $this->model_advert = new Model_Advert();
        $this->model_strategy = new Model_Strategy();

        if( in_array($tra_type,$this->tra_type_arr) )
        {
            $this->advert_id   = $id;
            $this->tra_type    = $tra_type;
            $this->advert_info = $this->get_advert($tra_type);
            if ( $this->tra_type == 1 )
            {
                $this->strategy_info = $this->get_strategy();
            }
        }else{
            $this->error = array('err_code' => '2222', 'msg' => $this->info['2222']['msg']);
            return false;
        }
    }

    //推送开启
    public function start()
    {
        $this->open   = true;
        $this->status = 1;
        $this->putin_switch();
    }

    //推送关闭
    public function stop()
    {
        $this->open   = false;
        $this->status = 0;
        $this->putin_switch();
    }

    private function putin_switch()
    {
        if ($this->open)  //开关为开的时候
        {
            $check_result = $this->check();
            if ( $check_result )
            {
                //更新数据库数据
                $_data = array(
                    'status'     => $this->status,
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->advert_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);  //数据更新失败
                    return false;
                }
                //之前取出的advert数据需要做相应的更改
                $this->advert_info['status']     = $this->status;
                $this->advert_info['put_status'] = $this->put_status;
                $this->doAdvertAndRedis();
            }
        }else{
            //更新数据库数据
            $_data = array(
                'status'     => $this->status,
                'put_status' => 0,
            );
            $_where = array(
                'AND' => array(
                    'id' => $this->advert_id,
                ),
            );
            $ret = $this->model_advert->update($_data, $_where);
            if ($ret === false) {
                $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);  //数据更新失败
                return false;
            }
            $this->doAdvertAndRedis();
        }
        // if($this->error) print_r($this->error);
    }

    //读取advert数据
    private function get_advert($tra_type)
    {
        $advert_info = array();
        $where = array(
            'AND' => array(
                'id'            => $this->advert_id,
                'isdel'         => 0,
                // 'review_status' => 1,
                'is_eleven'     => 1,
                'tra_type'      =>$tra_type
            )
        );
        $advert_info  = $this->model_advert->get('*', $where);
        // echo $this->model_advert->last_query();die;
        if(!$advert_info)
        {
            return false;
        }
        return $advert_info;
    }

    //读取计划数据
    private function get_strategy()
    {
        $strategy_info = array();
        $where = array(
            'AND' => array(
                'id'        => $this->advert_info['strategy_id'],
                'isdel'     => 0,
                'is_eleven' => 1,
                'tra_type'  => 1
            )
        );
        $strategy_info = $this->model_strategy->get('*', $where);
        if(!$strategy_info)
        {
            return false;
        }
        return $strategy_info;
    }

    //检查数据合法性
    private function check()
    {
        //检查是否存在
        if ( !$this->advert_info )  {
            $this->error = array('err_code' => '1001', 'msg' => $this->info['1001']['msg']);
            return false;
        }
        if ( $this->advert_info['review_status'] == 0 )
        {
            $this->error = array('err_code' => '1005', 'msg' => $this->info['1005']['msg']);
            return false;
        }
        //检查用户余额
        $money = 0;
        $select = array(
            'money',
        );
        $where = array(
            'id' => $this->advert_info['user_id'],
        );
        $send = array(
            'type'    => 'user',
            "_action" => "qg_get",
            "_field"  => json_encode($select),
            '_where'  => json_encode($where),
        );
        $user = json_decode(fn_get_contents($this->user_url, $send, 'post'), TRUE);
        if(isset($user['data']['data']) && isset($user['data']['data']['money']))
        {
            $money = $user['data']['data']['money'];
        }
        //检查用户余额
        if($money <= 0)
        {
            $this->put_status = $this->info['2000']['put_status'];    //余额不足
            $this->error = array('err_code' => '2000', 'msg' => $this->info['2000']['msg']);
            return false;
        }

        //开启创意时，是否为未审核
        if ($this->advert_info['review_status'] != 1) {
            $this->put_status = $this->info['1005']['put_status'];
            $this->error = array('err_code' => '1005', 'msg' => $this->info['1005']['msg']);
            return false;
        }
        $this->put_status = 1;  //开启投放状态
        return true;
    }

    //处理计划下的创意
    private function doAdvertAndRedis()
    {
        if ($this->advert_info)
        {
            //除了定制会场都是横幅
            if ( $this->tra_type != 1 )
            {
                $this->do_set_size();
            }else{
                //只有定制会场有标签
                if ( isset($this->strategy_info['tag_ids']) && $this->strategy_info['tag_ids'] )
                {
                    $tags_arr = explode(',',$this->strategy_info['tag_ids']);
                    $this->do_set_tags($tags_arr);
                }
            }
            //存入或删除advert_info详细信息数组
            $this->do_hash();
            //存入集合
            $this->do_set();
        }
        return true;
    }

    //处理集合的数据
    private function do_set() {
        $set = $this->open ? 'sAdd' : 'sRem';
        $this->redis->$set($this->prefix_key_tratype . $this->tra_type, $this->advert_id);
    }

    //处理标签的集合数据
    private function do_set_tags($tags_arr)
    {
        if ( $tags_arr )
        {
            $set = $this->open ? 'sAdd' : 'sRem';
            foreach ($tags_arr as $value) {
                $this->redis->$set($this->prefix_key_tags . $this->strategy_info['put_type'] . '_' .$value, $this->advert_id);
            }
        }
    }

    //处理size尺寸集合的数据
    private function do_set_size() {
        $set = $this->open ? 'sAdd' : 'sRem';
        $this->redis->$set($this->prefix_key_trasize . $this->advert_info['size'], $this->advert_id);
    }

    //处理哈希表的数据
    private function do_hash() {
        if ($this->open) {
            $this->redis->set('advert_info:' . $this->advert_id, json_encode($this->advert_info));
        } else {
            $this->redis->del('advert_info:' . $this->advert_id, json_encode($this->advert_info));
        }
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}
