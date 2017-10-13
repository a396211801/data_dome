<?php

/**
 * 推送
 * @author zhengzhe
 * @date 2015-08-05
 */
class Putin
{

    private $user_url = 'http://login.juhuisuan.com/interface/ht'; //接口
    private $type; //用于存放类型 strategy或者advert
    private $error = array(); //用于存放错误信息
    private $hf_under_strategy_id; //托底id
    private $mobile_under_strategy_id; //无线托底id
    private $open; //开关（true开，false关）
    private $status; //开关具体标识（1，0）
    private $strategy_id; //预留存放计划ID
    private $advert_id; //预留创意存放创意ID
    private $strategy_info; //预留存放具体计划信息
    private $advert_info; //预留存放具体创意信息
    private $advert_arr; //预留存放所有可用的创意集合
    private $put_status = 0; //预留存放状态
    private $redis;
    //正确和错误信息
    private $info = array(
        '1000' => array('msg' => '没有推广计划'), // > 1xxx
        '1001' => array('msg' => '没有投放创意'), // > 数据不存在
        '1002' => array('msg' => '审核未通过'),
        '1003' => array('msg' => '无可投放创意', 'put_status' => 6),
        '1004' => array('msg' => '上层计划未打开', 'put_status' => 7),
        '1005' => array('msg' => '未通过审核', 'put_status' => 8),
        '2000' => array('msg' => '余额不足', 'put_status' => 2), // > 2xxx
        '2001' => array('msg' => '日限额不足', 'put_status' => 3), // > 跟资金有关
        '3000' => array('msg' => '不在投放周期', 'put_status' => 4), // > 3xxx
        '3001' => array('msg' => '不在投放时段', 'put_status' => 5), // > 跟时间有关
        '4444' => array('msg' => '数据更新失败'),
        '9999' => array('msg' => '正在投放', 'put_status' => 1), //打开投放
        '1111' => array('msg' => '未投放', 'put_statys' => 0),
    );
    //模型
    private $model_strategy;
    private $model_advert;
    private $model_area;
    private $model_report_ad_day_tmp;
    private $model_group_pkg;
    //字段
    private $field_strategy = array('*');
    private $field_advert = array('*');
    //键
    private $prefix_key_province = 'PROVINCE_'; //省
    private $prefix_key_province_delicacy = 'PROVINCE_WAP_'; //省
    private $prefix_key_city = 'CITY_'; //市
    private $prefix_key_index = 'INDEX_'; //首页，非首页
    private $prefix_key_position = 'POSITION_'; //展现位置
    private $prefix_key_site = 'SITE_'; //站点类型
    private $prefix_key_size = 'SIZE_'; //尺寸
    private $prefix_key_os = 'OS_'; //系统
    private $prefix_key_oe = 'OE_'; //设备
    private $prefix_key_tags = 'TAGS_'; //标签
    private $prefix_key_normaltags = 'NORMALTAGS_'; //普通计划标签
    private $prefix_key_strategy = 'STRSTEGY_'; //计划前缀
    private $prefix_key_shop = 'SHOP_'; //店铺ID前缀
    private $prefix_key_alltags = 'ALL_TAGS'; //店铺ID前缀
    private $prefix_key_id_province = 'ID_PROVINCE'; //省ID数组
    private $prefix_key_id_city = 'ID_CITY'; //市ID数组
    private $prefix_key_p_to_c = 'ID_P_TO_C'; //省市对应关系ID数组
    private $prefix_key_web_type = 'ID_WEB_TYPE'; //网站类型ID数组
    private $prefix_key_person_package = "PERSON_PACKAGE"; //访客找回人群包
    //各数组
    private $province; //省
    private $city; //市
    private $p_to_c; //省市对应关系数组
    private $web_type; //网站类型
    //分隔符
    private $delimiter_area = ','; //strategy表字段allow_area的分隔符
    private $delimiter_site = ','; //strategy表字段site_type的分隔符

    //各种初始化

    public function __construct($id, $type)
    {
        $this->redis = new Component_Redis();
        $this->model_strategy = new Model_Strategy();
        $this->model_advert = new Model_Advert();
        $this->model_report_ad_day_tmp = new Model_ReportAdDayTmp();
        $this->model_group_pkg = new Model_GroupPkg;
        $this->hf_under_strategy_id = fn_getConfig()->under->hf->strategy; //CPC托底
        $this->mobile_under_strategy_id = fn_getConfig()->under->mobile->strategy; //无线托底
        $this->error = array('err_code' => '9999', 'msg' => $this->info['9999']['msg']); //添加默认消息
        $this->init(); //初始化省、市、省市对应、网站类型四个数组
        if ($type == 'advert') {
            $this->advert_id = $id;
            $this->strategy_id = $this->get_sid_by_aid($id);
            $this->type = 'advert';
        } else {
            $this->strategy_id = $id;
            $this->type = 'strategy';
        }
    }

    //初始化
    private function init()
    {
        //初始化省、市、省市对应三数组
        $this->province = json_decode($this->redis->get($this->prefix_key_id_province), true);
        $this->city = json_decode($this->redis->get($this->prefix_key_id_city), true);
        $this->p_to_c = json_decode($this->redis->get($this->prefix_key_p_to_c), true);
        if (!$this->province || !$this->city || !$this->p_to_c) {
            $where = array(
                'AND' => array(
                    'type' => 2, //市
                ),
            );
            $this->model_area = new Model_Area();
            $list = $this->model_area->select(array('id', 'pid'), $where);
            foreach ($list as $v) {
                $this->p_to_c[$v['pid']][] = $v['id']; //省[市]
                $this->city[] = $v['id']; //市 数组
            }
            $this->province = array_keys($this->p_to_c); //省数组
            $this->redis->set($this->prefix_key_id_province, json_encode($this->province));
            $this->redis->set($this->prefix_key_id_city, json_encode($this->city));
            $this->redis->set($this->prefix_key_p_to_c, json_encode($this->p_to_c));
        }

        //初始化网站类型
        $this->web_type = json_decode($this->redis->get($this->prefix_key_web_type), true);
        if (!$this->web_type) {
            $where = array(
                'AND' => array(
                    'type' => 'WEBTYPE',
                ),
            );
            $this->model_dic = new Model_Dic();
            $this->web_type = $this->model_dic->select('code', $where);
            $this->redis->set($this->prefix_key_web_type, json_encode($this->web_type));
        }
    }

    //推送开启
    public function start()
    {
        $this->open = true;
        $this->status = 1;
        $this->putin_switch();
    }

    //推送关闭
    public function stop()
    {
        $this->open = false;
        $this->status = 0;
        $this->putin_switch();
    }

    /**
     * @describe 开关具体操作，包括检查状态，更改状态
     * @return [true or false] [成功开启 true 不成功 false]
     * 获取具体错误信息 调用 $this->getError()来查看
     */
    private function putin_switch()
    {
        //托底
        if ($this->strategy_id == $this->hf_under_strategy_id || $this->strategy_id == $this->mobile_under_strategy_id) {
            return $this->under($this->type) ? true : false;
        }
        if ($this->open) {
            //开关为开的时候
            if ($this->type == 'strategy') {
                //开计划的时候
                $this->get_strategy(); //获取计划信息
                $this->check(); //是否符合所有条件,为各种状态赋值
                if ($this->put_status == 1) { //如果开启计划 检查计划下开启的创意 推送到redis
                    $this->get_advert(); //获取创意数组
                    $this->doAdvertAndRedis(); //处理hash数组和做redis操作
                }
                //更改计划状态
                $_data = array(
                    'status' => $this->status,
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_strategy->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                //更改创意状态
                $_data = array(
                    'put_status' => $this->put_status, //创意列表页状态继承计划，除了上层未开启
                );
                $_where = array(
                    'AND' => array(
                        'status' => 1,
                        'review_status' => 1,
                        'strategy_id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                return true;
            } else {
                $this->get_strategy();
                $this->check();
                if ($this->put_status == 1) {
                    //check后通过
                    $this->get_advert();
                    $this->doAdvertAndRedis();
                }
                //更改创意状态
                $_data = array(
                    'status' => $this->status,
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'isdel' => 0,
                        'id' => $this->advert_id,
                        'strategy_id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                //判断上层计划状态，改变状态字段
                if ($this->strategy_info['status'] == 1 && $this->advert_arr[$this->advert_id]['review_status'] == 1) {
                    $_data = array(
                        'put_status' => 1, //开启
                    );
                    $_where = array(
                        'AND' => array(
                            'id' => $this->strategy_id,
                        ),
                    );
                    $ret = $this->model_strategy->update($_data, $_where);
                    if ($ret === false) {
                        $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                        return false;
                    }
                }
                return true;
            }
        } else {
            if ($this->type == 'strategy') {
                //关闭计划
                $this->get_strategy();
                $this->get_advert(); //获取开关打开的创意
                $this->doAdvertAndRedis();
                $_data = array(
                    'status' => $this->status,
                    'put_status' => 0,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_strategy->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                $this->error = array('err_code' => '1111', 'msg' => $this->info['1111']['msg']);
                //更改创意状态
                $_data = array(
                    'put_status' => 7, //上层计划未开启
                );
                $_where = array(
                    'AND' => array(
                        'status' => 1,
                        'review_status' => 1,
                        'strategy_id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                return true;
            } else {
                $this->get_strategy();
                $this->get_advert(); //获取开关打开的创意
                $this->doAdvertAndRedis();
                //更改创意状态
                $_data = array(
                    'status' => $this->status, //关闭标识 0
                    'put_status' => 0,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->advert_id,
                        'strategy_id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                $this->error = array('err_code' => '1111', 'msg' => $this->info['1111']['msg']); //修改ajax反馈信息
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                //检查是否无符合投放状态的创意,没有话更改计划状态
                if ($this->strategy_info['status'] == 1) {
                    $_where = array(
                        'AND' => array(
                            'strategy_id' => $this->strategy_id,
                            'review_status' => 1, //已审核
                            'isdel' => 0,
                            'status' => 1, //开关打开
                        ),
                    );
                    $has = $this->model_advert->has($_where);
                    if (!$has) {
                        $ret = $this->model_strategy->update(array('put_status' => 6), array('id' => $this->strategy_id));
                        if ($ret === false) {
                            $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                            return false;
                        }
                    }
                }
                return true;
            }
        }
    }

    //读取strategy数据
    private function get_strategy()
    {
        $where = array(
            'AND' => array(
                'isdel' => 0,
                'id' => $this->strategy_id,
            ),
        );
        $strategy = $this->model_strategy->get($this->field_strategy, $where);
        if (!$strategy) {
            $this->ajax_return(0, array('err_code' => '1000', 'msg' => $this->info['1000']['msg']));
        }
        $this->strategy_info = $strategy;
    }

    //读取advert数据
    private function get_advert()
    {
        $where = array(
            'AND' => array(
                'strategy_id' => $this->strategy_id,
                'isdel' => 0,
                'review_status' => 1,
            ),
        );
        if ($this->type == 'advert') {
            //托底不需要审核
            if ($this->strategy_id == $this->hf_under_strategy_id) {
                unset($where['AND']['review_status']);
            }
            $where['AND']['id'] = $this->advert_id;
        } else {
            $where['AND']['status'] = 1; //计划的关闭和打开操作只影响已经打开投放开关的创意
        }
        $list = $this->model_advert->select($this->field_advert, $where);
        if (!$list) {
            $this->put_status = $this->info[1003]['put_status'];
            $this->error = array('err_code' => '1003', 'msg' => $this->info['1003']['msg']);
        }
        foreach ($list as $v) {
            $v['put_status'] = $this->put_status;
            $this->advert_arr[$v['id']] = $v;
        }
        return true;
    }

    //根据advert_id读取strategy_id
    private function get_sid_by_aid($advert_id)
    {
        $where = array(
            'AND' => array(
                'id' => $advert_id,
            ),
        );
        $strategy_id = $this->model_advert->get('strategy_id', $where);
        return $strategy_id;
    }

    //检查数据合法性
    private function check()
    {
        //开启创意时，是否为未审核
        if ($this->type == 'advert') {
            $review_status = $this->model_advert->get('review_status', array('id' => $this->advert_id));
            if ($review_status != 1) {
                $this->put_status = $this->info['1005']['put_status'];
                $this->error = array('err_code' => '1005', 'msg' => $this->info['1005']['msg']);
                return false;
            }
        }
        //检查用户余额
        $money = 0;
        $select = array(
            'money',
        );
        $where = array(
            'id' => $this->strategy_info['user_id'],
        );
        $send = array(
            'type' => 'user',
            "_action" => "qg_get",
            "_field" => json_encode($select),
            '_where' => json_encode($where),
        );
        $user = json_decode(fn_get_contents($this->user_url, $send, 'post'), true);
        if (isset($user['data']['data']) && isset($user['data']['data']['money'])) {
            $money = $user['data']['data']['money'];
        }
        //检查用户余额
        if ($money <= 0) {
            $this->put_status = $this->info['2000']['put_status']; //余额不足
            $this->error = array('err_code' => '2000', 'msg' => $this->info['2000']['msg']);
            return false;
        }

        //检查日限额
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $where = array(
            'AND' => array(
                'date' => $today,
                'strategy_id' => $this->strategy_info['id'],
            ),
        );
        $day_limit = $this->model_report_ad_day_tmp->get('SUM(money) as money', $where);

        if (floatval($day_limit) >= floatval($this->strategy_info['day_limit'])) {
            $this->put_status = $this->info['2001']['put_status']; //3 日限额不足
            $this->error = array('err_code' => '2001', 'msg' => $this->info['2001']['msg']);
            return false;
        }

        //检查投放周期
        if ($today < $this->strategy_info['begin_time'] || $today > $this->strategy_info['end_time']) {
            $this->put_status = $this->info['3000']['put_status']; //4 不在投放周期
            $this->error = array('err_code' => '3000', 'msg' => $this->info['3000']['msg']);
            return false;
        }

        //检查时间段
        $time = time();
        $week = date('N', $time); //星期
        $hour = date('H', $time); //时
        $interval = explode('|', $this->strategy_info['time_interval']);
        $str = substr($interval[$week - 1], $hour, 1);
        if (!$str) {
            $this->put_status = $this->info['3001']['put_status'];
            $this->error = array('err_code' => '3001', 'msg' => $this->info['3001']['msg']);
            return false;
        }

//        //开启创意时，是否为未审核
//        if ($this->type == 'advert') {
//            $review_status = $this->model_advert->get('review_status', array('id' => $this->advert_id));
//            if ($review_status != 1) {
//                $this->put_status = $this->info['1005']['put_status'];
//                $this->error = array('err_code' => '1005', 'msg' => $this->info['1005']['msg']);
//                return false;
//            }
//        }

        //检查是否审核通过
        $where = array(
            'AND' => array(
                'review_status' => 1,
                'isdel' => 0,
            ),
        );
        //检查子级是否都已审核通过
        if ($this->type == 'strategy') {
            $where['AND']['strategy_id'] = $this->strategy_info['id'];
        } else {
            //如果开启的是创意，则检查当前创意有无审核通过
            $where['AND']['id'] = $this->advert_id;
        }
        $has = $this->model_advert->has($where);
        if (!$has) {
            $this->put_status = $this->info['1003']['put_status'];
            $this->error = array('err_code' => '1003', 'msg' => $this->info['1003']['msg']);
            return false;
        }

        //如果传入类型为advert，检查上级是否开启
        if ($this->type == 'advert' && $this->strategy_info['status'] == 0) {
            $this->put_status = $this->info['1004']['put_status'];
            $this->error = array('err_code' => '1004', 'msg' => $this->info['1004']['msg']);
            return false;
        }

        $this->put_status = 1; //开启投放
        return true;
    }

    //更新数据
    private function update()
    {
        $status = $this->open ? 1 : 0;
        if ($this->open) {
            //如果是开启
            if ($this->type == 'advert') {
                //如果传入的是advert_id
                $_data = array(
                    'status' => $status,
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->advert_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']); //数据更新失败
                    return false;
                }
                return true;
            } else {
                if ($this->put_status == 1) { //如果开启计划 检查计划下开启的创意 推送到redis
                    $this->get_advert(); //获取创意数组
                    $this->doAdvertAndRedis(); //处理hash数组和做redis操作
                }
                //更改计划状态
                $_data = array(
                    'status' => $status,
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_strategy->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
                //更改创意状态
                $_data = array(
                    'put_status' => $this->put_status,
                );
                $_where = array(
                    'AND' => array(
                        'strategy_id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_advert->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
            }
        } else {
            if ($this->type == 'advert') {
                //关闭创意
            } else {
                $this->get_advert(); //获取开关打开的创意
                $this->doAdvertAndRedis();
                $_data = array(
                    'status' => $status,
                    'put_status' => 0,
                );
                $_where = array(
                    'AND' => array(
                        'id' => $this->strategy_id,
                    ),
                );
                $ret = $this->model_strategy->update($_data, $_where);
                if ($ret === false) {
                    $this->error = array('err_code' => '4444', 'msg' => $this->info['4444']['msg']);
                    return false;
                }
            }
        }

        return true;
    }

    //处理计划下的创意
    private function doAdvertAndRedis()
    {
        if ($this->advert_arr) {
            foreach ($this->advert_arr as $advert) {
                //处理省市的投放关系
                if ($this->strategy_info['allow_area'] === '1') {
                    foreach ($this->city as $c) {
                        $city[$c][] = $advert['id']; //在所有城市下投放
                    }
                } else {
                    $allow_area = explode($this->delimiter_area, $this->strategy_info['allow_area']);
                    foreach ($allow_area as $c) {
                        //如果是省，读出所有的市ID
                        if (strpos($c, 'p') !== false && in_array(ltrim($c, 'p'), $this->province)) {
                            $province[ltrim($c, 'p')][] = $advert['id'];
//                            foreach ($this->p_to_c[$c] as $_c) {
                            //                                $city[$_c][] = $advert['id'];
                            //                                $province[]='';
                            //                            }
                        } else if (in_array($c, $this->city)) {
                            $city[$c][] = $advert['id'];
                        }
                    }
                }
                //分类展现位置，如果用户选择不限
                if (intval($this->strategy_info['index_type']) == 0) {
                    $index['1'][] = $advert['id']; //首屏
                    $index['2'][] = $advert['id']; //非首屏
                } else {
                    $index[$this->strategy_info['index_type']][] = $advert['id'];
                }
                //广告形式，如果用户选择不限
                if (intval($this->strategy_info['position_type']) == 0) {
                    $position['1'][] = $advert['id']; //固定位置
                    $position['2'][] = $advert['id']; //富媒体
                    $position['4'][] = $advert['id']; //无线固定
                    $position['5'][] = $advert['id']; //无线悬浮
                } else {
                    $position[$this->strategy_info['position_type']][] = $advert['id'];
                }
                //站点类型
                $site_type = explode($this->delimiter_site, $this->strategy_info['site_type']);
                if ($site_type[0] == '' || $site_type[0] == '0') {
                    foreach ($this->web_type as $v) {
                        //如果用户选择不限，就分散存到各类型中
                        $site[$v][] = $advert['id'];
                    }
                } else {
                    $site_type = explode($this->delimiter_site, $this->strategy_info['site_type']);
                    foreach ($site_type as $v) {
                        $site[$v][] = $advert['id'];
                    }
                }
                //精准投放标准进redis
                if ($this->strategy_info['is_precise'] == 1) {
                    $tag_ids = $this->strategy_info['tag_ids'];
                    if ($tag_ids) {
                        $tag_tmp = explode(',', $tag_ids);
                        foreach ($tag_tmp as $key => $value) {
                            $tag_arr[$value] = $advert['id'];
                        }
                        if ($advert['tag_id'] != 0 && !array_search($advert['tag_id'], $tag_arr)) {
                            $tag_arr[$advert['tag_id']] = $advert['id']; //系统打的创意标签也放入
                        }
                        if ($this->strategy_info['key_type'] == 5) {
                            $this->do_set($tag_arr, $this->prefix_key_tags . '5_'); //TAGS_5 防止横幅域名id和电商id冲突混淆
                        } else {
                            $this->do_set($tag_arr, $this->prefix_key_tags . $this->strategy_info['put_type'] . '_');
                        }
                    }

                    // 域名投放
                    if(isset($this->strategy_info['ht_domain_ids']) && $this->strategy_info['ht_domain_ids'] != '0' && $this->strategy_info['ht_domain_ids'] != '') {
                        $domainIds = $this->strategy_info['ht_domain_ids'];
                    } else {
                        $domainIds = $this->strategy_info['domain_ids'];
                    }
                    if ($domainIds) {
                        $domainIdAry = explode(',', $domainIds);
                        $domainAdverts = array();
                        foreach ($domainIdAry as $key => $value) {
                            $domainAdverts[$value] = $advert['id'];
                        }
                        if (!empty($domainAdverts)) {
                            $this->do_set($domainAdverts, $this->prefix_key_tags . '5_');
                        }
                    }

                    // 店铺投放
                    $shopinfo = json_decode($this->strategy_info['shopinfo'], true);
                    if ($shopinfo && isset($shopinfo['shopinfo']) && isset($shopinfo['dayLimit'])) {
                        foreach ($shopinfo['shopinfo'] as $shop) {
                            $shop_ids[$shop['sid']][] = $advert['id'] . '_' . $shopinfo['dayLimit'];
                        }
                        $this->do_set_value($shop_ids, $this->prefix_key_shop . $this->strategy_info['put_type'] . '_');
                    }

                    //访客人群投放
                    $this->set_people_pkg($advert['id']);

                    //把所有是精准的放入一个集合
                    $set = $this->open ? 'sAdd' : 'sRem'; //增加所有的精准创意
                    $this->redis->$set($this->prefix_key_alltags, $advert['id']);
                } else {
                    if (isset($advert['tag_id']) && !empty($advert['tag_id'])) {
                        $tags[$advert['tag_id']][] = $advert['id'];
                        $this->do_set($tags, $this->prefix_key_normaltags . $this->strategy_info['put_type'] . '_');
                    }
                }
                //如果是无线的投放，要增加系统和设备的条件
                if ($this->strategy_info['put_type'] == DBConst::PUT_TYPE_WAP) {
                    //操作系统
                    if ($this->strategy_info['os'] == 0) { //全选
                        $os[1][] = $advert['id']; //安卓
                        $os[2][] = $advert['id']; //ios
                    } else {
                        $os[$this->strategy_info['os']][] = $advert['id'];
                    }
                    //操作设备
                    if ($this->strategy_info['oe'] == 0) { //全选
                        $oe[1][] = $advert['id']; //手机
                        $oe[2][] = $advert['id']; //平板
                    } else {
                        $oe[$this->strategy_info['oe']][] = $advert['id'];
                    }
                    //为无线添加redis集合
                    $this->do_set($os, $this->prefix_key_os . $this->strategy_info['put_type'] . '_');
                    $this->do_set($oe, $this->prefix_key_oe . $this->strategy_info['put_type'] . '_');
                }

                //banner需要添加大小
                if ($this->strategy_info['put_type'] == DBConst::PUT_TYPE_BANNER || $this->strategy_info['put_type'] == DBConst::PUT_TYPE_WAP) {
                    $this->do_set_size();
                }

                //添加计划和创意的对应关系
                $adverts[$this->strategy_id][] = $advert['id'];

                //存入或删除advert详细信息数组
                $this->do_hash();
                //存入或删除ID集合
                //key的形式：
                //CPC例如：CITY_0_383，0代表CPC，383是城市ID，在nxu_area表中
                //CPM例如：CITY_1_383，1代表CPM，383是城市ID，在nxu_area表中
                if ($this->strategy_info['is_precise'] == 1) {
                    $proTag = $this->prefix_key_province;
                    if ($this->strategy_info['put_type'] == 50) {
                        $proTag = $this->prefix_key_province_delicacy;
                    }
                    !empty($province) && $this->do_set($province, $proTag . $this->strategy_info['put_type'] . '_');
                }
                $this->do_set($city, $this->prefix_key_city . $this->strategy_info['put_type'] . '_');
                $this->do_set($index, $this->prefix_key_index . $this->strategy_info['put_type'] . '_');
                $this->do_set($position, $this->prefix_key_position . $this->strategy_info['put_type'] . '_');
                $this->do_set($site, $this->prefix_key_site . $this->strategy_info['put_type'] . '_');
                $this->do_set($adverts, $this->prefix_key_strategy . $this->strategy_info['put_type'] . '_');
            }
        }
        return true;
    }

    //处理人群包
    private function set_people_pkg($advertId)
    {
        $pkgs = explode(",", $this->strategy_info['pkg_ids']);
        $pkglist = $this->model_group_pkg->getMulPkgInfo($pkgs);
        $putPkgs = array();
        foreach ($pkglist as $pkg) {
            $putPkgs[] = $pkg["id"] . "_" . $pkg['expire'] . "_" . $advertId;
        }
        $set = $this->open ? 'sAdd' : 'sRem';
        foreach ($putPkgs as $v) {
            $this->redis->$set($this->prefix_key_person_package, $v);
        }
    }

    //处理集合的数据
    private function do_set_value($data, $prefix_key)
    {
        $set = $this->open ? 'sAdd' : 'sRem';
        foreach ($data as $k => $v) {
            $this->redis->$set($prefix_key . $k, $v);
        }
    }

    //处理集合的数据
    private function do_set($data, $prefix_key)
    {
        $set = $this->open ? 'sAdd' : 'sRem';
        foreach ($data as $k => $v) {
            $this->redis->$set($prefix_key . $k, array_keys($this->advert_arr));
        }
    }

    //处理size尺寸集合的数据
    private function do_set_size()
    {
        $set = $this->open ? 'sAdd' : 'sRem';
        foreach ($this->advert_arr as $v) {
            $this->redis->$set($this->prefix_key_size . $v['size'], $v['id']);
        }
    }

    //处理哈希表的数据
    private function do_hash()
    {
        foreach ($this->advert_arr as $v) {
            if ($this->open) {
                $this->redis->set('advert_info:' . $v['id'], json_encode($v));
            } else {
                $this->redis->del('advert_info:' . $v['id']);
            }
        }
    }

    //推送托底
    public function under($type)
    {
        $key = 'UNDER_ADVERT';
        $status = 0;
        $SetOrDel = 'del';
        $sAddOrSrem = 'srem';
        $this->error = $this->info['1111'];
        if ($this->open) {
            $status = 1;
            $SetOrDel = 'set';
            $sAddOrSrem = 'sAdd';
            $this->error = $this->info['9999'];
        }
        if ($this->strategy_id == $this->mobile_under_strategy_id) {
            $key = 'MOBILE_UNDER_ADVERT';
        }
        if ($type == 'strategy') {
            $where = array(
                'AND' => array(
                    'strategy_id' => $this->strategy_id,
                    'isdel' => 0,
                ),
            );
            //根据推广计划ID取托底的创意
            $list = $this->model_advert->select($this->field_advert, $where);
            if (!$list) {
                return false;
            }
            //id添加进set，数据添加进hash
            $advert = array();
            foreach ($list as $v) {
                $advert[$v['id']] = $v;
                $this->redis->$SetOrDel('advert_info:' . $v['id'], json_encode($v));
            }
            $this->redis->$sAddOrSrem($key, array_keys($advert));
            $this->redis->$sAddOrSrem('UNDER_SIZE_' . $v['size'], array_keys($advert));
            $this->model_strategy->update(array('put_status' => $status, 'status' => $status), array('AND' => array('id' => $this->strategy_id)));
            $this->model_advert->update(array('put_status' => $status, 'status' => $status), array('AND' => array('strategy_id' => $this->strategy_id)));
            return true;
        } else {
            if ($this->get_advert()) {
                $advert_info = $this->advert_arr;
            } else {
                return false;
            }
            $this->redis->$SetOrDel('advert_info:' . $this->advert_id, json_encode($advert_info[$this->advert_id]));
            $this->redis->$sAddOrSrem($key, $this->advert_id);
            $this->redis->$sAddOrSrem('UNDER_SIZE_' . $advert_info[$this->advert_id]['size'], $this->advert_id);
            $this->model_advert->update(array('put_status' => $status, 'status' => $status), array('AND' => array('id' => $this->advert_id)));
        }
    }

    //ajax返回
    private function ajax_return($ret = 0, $data = '')
    {
        $info = array(
            'ret' => $ret,
        );
        if ($data) {
            $info['data'] = $data;
        }
        return json_encode($info);
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}
