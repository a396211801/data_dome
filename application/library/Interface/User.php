<?php

/**
 * 用户接口模型
 *
 * @author jyh 2016-05-20
 */
class Interface_User extends CallInterface
{

    /**
     * 获取用户信息
     */
    public function getUser($email)
    {
        $where = array();
        $where['OR']['email'] = strtolower($email);
        $where['OR']['phone'] = $email;
        $where['OR']['username'] = $email;

        $field = array('id', 'username', 'phone', 'email', 'money', 'score', 'seller_id', 'come_from', 'time');
        $send = array(
            'type' => 'user', //相当于表，但并不是所有表都可以
            '_action' => 'qg_get', //多条查询
            '_field' => json_encode($field), //自定义
            '_where' => json_encode($where),
        );
        $this->setUrl('user_url');
        $data = $this->run($send);
        if (isset($data['data'])) {
            return $data['data'];
        } else {
            return false;
        }
    }

    public function updateUserMoney($uid, $money)
    {
        $money_data = array(
            'uid' => $uid,
            'm' => $money,
            'type' => 'recharge',
        );
        $this->setUrl('user_url', 'jhs');
        $res = $this->run($money_data);
        if (empty($res)) {
            return false;
        } else {
            return $res;
        }
    }

}
