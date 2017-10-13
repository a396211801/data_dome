<?php
class Model_Customer extends Smodel {

    protected $table = "customer";

    /**
     * 前台登录
     * @param $info
     * @return mixed
     */
    public function loginCheck($info)
    {
        if(!isset($info['username'])) {
            fn_ajax_return(0,'请输入用户名!');
        }
        if(!isset($info['password'])) {
            fn_ajax_return(0,'请输入密码!');
        }

        $username = $info['username'];
        $password = $info['password'];

        $customer = $this->get("*",['AND'=>['username'=>$username]]);

        if($customer) {
            if($customer['status'] == 2){
                fn_ajax_return(0,'您的账号已冻结,请联系客服');
            }
            $salt = $customer['salt'];
            $old_password = $customer['password'];
            if($old_password == md5($password."_".$salt)){
                return $customer;
            } else {
                fn_ajax_return(0,'密码输入错误!');
            }

        } else {
            fn_ajax_return(0,'用户名输入错误!');
        }
    }

    /**
     * 用户列表
     * */
    public function getList($condition=[])
    {
        $p = isset($condition['p']) ? $condition['p'] : 1;
        $r = isset($condition['limit']) && $condition['limit'] > 0 ? $condition['limit'] : 10;
        $where = $this->dataCheck($condition);
        $count = $this->count($where);
        $where['ORDER'] = ' create_time desc';
        $where['LIMIT'] = array(($p - 1) * $r, $r);
        $field = ['id','username','realname','contact_name','mobile','qq','wangwang','admin_id','status'];
        $list = $this->select($field, $where);
        if(!empty($list)){
            $list = $this->externalConnection($list);
        }
        return array('list' => $list, 'count' => ceil($count / $r));
    }

    /**
     * 新建用户
     * */
    public function addInfo($data)
    {
        $this->required($data);
        if(!isset($data['password']) && !$data['password'])
        {
            $data['password'] = 123456;
        }
        $salt = rand(1000,9999);
        $data['salt'] = $salt;
        $data['password'] = md5($data['password']."_".$salt);
        $data['create_time'] = time();
        return $this->insert($data);
    }

    /**
     * 信息编辑
     * */
    public function editInfo($condition,$where)
    {
        $data = [];
        if(isset($condition['password']) && isset($condition['psw']))
        {
            if($condition['password'] && $condition['psw'])
            {
                if($condition['password']==$condition['psw'])
                {
                    $salt = rand(1000,9999);
                    $password = md5($condition['password']."_".$salt);
                    $data['password'] = $password;
                    $data['salt'] = $salt;
                }else{
                    fn_ajax_return(0,'两次密码不正确');
                }
            }
        }
        if(isset($condition['realname']) && $condition['realname'])
        {
            $re = $this->dataOnly(['realname'=>$condition['realname'],'id'=>$where['id']],1);
            if($re) fn_ajax_return(0,'用户名已存在');
            $data['realname'] = $condition['realname'];
        }else{
            fn_ajax_return(0,'用户名必填');
        }
        if(isset($condition['contact_name']) && $condition['contact_name'])
        {
            $data['contact_name'] = $condition['contact_name'];
        }else{
            fn_ajax_return(0,'联系人必填');
        }
        if(isset($condition['mobile']) && $condition['mobile'])
        {
            $re = $this->dataOnly(['mobile'=>$condition['mobile'],'id'=>$where['id']],1);
            if($re) fn_ajax_return(0,'手机号码已存在');
            $data['mobile'] = $condition['mobile'];
        }
        if(isset($condition['qq']) && $condition['qq'])
        {
            $data['qq'] = $condition['qq'];
        }
        if(isset($condition['wangwang']) && $condition['wangwang'])
        {
            $data['wangwang'] = $condition['wangwang'];
        }
        $data['update_time'] = time();
        return $this->update($data,$where);
    }

    public function frozen($data = []){
        if(!isset($data['id']) || !$data['id']) fn_ajax_return(0,'参数错误','');
        $update_data = [
            'status'=>$data['status'],
            'update_time'=>time(),
        ];
        return $this->update($update_data,['id'=>$data['id']]);
    }


    /**
     * 获取指定用户数据
     * */
    public function getInfo($data)
    {
        if(!$data) return '';
        $where = [];
        if(isset($data['id']) && $data['id'])
        {
            $where['AND']['id'] = $data['id'];
        }
        if(isset($data['realname']) && $data['realname'])
        {
            $where['AND']['realname'] = $data['realname'];
        }
        if(isset($data['username']) && $data['username'])
        {
            $where['AND']['username'] = $data['username'];
        }
        $res = [];
        $files = ['id','username','realname','contact_name','mobile','qq','wangwang','admin_id','last_login_ip','last_login_time','create_time','update_time'];
        if(!empty($where)){
            $res = $this->get($files,$where);
        }
        return $res;
    }

    /**
     * 必填项
     * */
    protected function required($data)
    {
        if(!isset($data['username']) && !$data['username'])
        {
            fn_ajax_return(0,'用户名必填');
        }
        if (strlen($data['password']) < 8 || strlen($data['password']) > 16 || is_numeric($data['password'])) {
            fn_ajax_return(0, '密码长度：8～16个字符,不能单纯数字，至少包含两种字符！');
        }
        if(!isset($data['realname']) && !$data['realname'])
        {
            fn_ajax_return(0,'客户名必填');
        }
        if(!isset($data['contact_name']) && !$data['contact_name'])
        {
            fn_ajax_return(0,'联系人姓名必填');
        }
        if((!isset($data['mobile']) && !$data['mobile']) ||
            (!isset($data['qq']) && !$data['qq']) ||
            (!isset($data['wangwang']) && !$data['wangwang']))
        {
            fn_ajax_return(0,'手机、qq、旺旺（三者选其一）必填');
        }
        if(isset($data['mobile']) && $data['mobile'])
        {
            $re = $this->dataOnly(['mobile'=>$data['mobile']]);
            if($re) fn_ajax_return(0,'手机号码已存在');
        }
        if(isset($data['username']) && $data['username'])
        {
            $re = $this->dataOnly(['username'=>$data['username']]);
            if($re) fn_ajax_return(0,'用户名已存在');
        }
    }

    /**
     * 数据验证唯一性
     * */
    public function dataOnly($data,$key=false)
    {
        $where=[];
        if(isset($data['username']) && $data['username'])
        {
            $where['AND']['username'] = $data['username'];
        }
        if(isset($data['mobile']) && $data['mobile'])
        {
            $where['AND']['mobile'] = $data['mobile'];
        }
        if(isset($data['realname']) && $data['realname'])
        {
            $where['AND']['realname'] = $data['realname'];
        }
        if($key){
            $where['AND']['id[!]'] = $data['id'];
        }
        return $this->count($where);
    }

    /**
     * 查询条件处理
     * */
    protected function dataCheck($data)
    {
        $where = [];
        if(isset($data['username']) && $data['username'])
        {
            $where['AND']['OR']['username[~]'] = $data['username'];
            $where['AND']['OR']['realname[~]'] = $data['username'];
            $where['AND']['OR']['contact_name[~]'] = $data['username'];
            $where['AND']['OR']['mobile[~]'] = $data['username'];
            $model_admin = new  Model_Admin();
            $admin_ids = $model_admin->select('id',['realname[~]'=>$data['username']]);
            if(isset($admin_ids) && $admin_ids) {
                $where['AND']['OR']['admin_id'] = $admin_ids;
            }
        }
        return $where;
    }

    /**
     * @param array $list
     * @return array
     * 链接外表
     */
    private function externalConnection($list = []){
        if(empty($list)) return $list;
        $model_admin = new Model_Admin();
        foreach($list as $k=>$v) {
            $admin_where['AND']['id'] = $v['admin_id'];
            $list[$k]['admin_name'] = $model_admin->get('realname',$admin_where) ? $model_admin->get('realname',$admin_where) : '-';
        }
        return $list;
    }

    /**
     * 后台信息编辑
     * */
    public function htEditInfo($condition = [])
    {
        if(!isset($condition['id']) || !$condition['id']) fn_ajax_return(0,'参数错误','');
        $data = [];
        if(isset($condition['password']) && $condition['password'])
        {
            if (strlen($condition['password']) < 6 || strlen($condition['password']) > 16 || is_numeric($condition['password'])) {
                fn_ajax_return(0, '密码长度：8～16个字符,不能单纯数字,至少包含两种字符!');
            }

            $salt = rand(1000,9999);
            $password = md5($condition['password']."_".$salt);
            $data['password'] = $password;
            $data['salt'] = $salt;
        }
        if(isset($condition['realname']) && $condition['realname'])
        {
            $re = $this->dataOnly(['realname'=>$condition['realname'],'id'=>$condition['id']],1);
            if($re) fn_ajax_return(0,'用户名已存在');
            $data['realname'] = $condition['realname'];
        }else{
            fn_ajax_return(0,'用户名必填');
        }
        if(isset($condition['contact_name']) && $condition['contact_name'])
        {
            $data['contact_name'] = $condition['contact_name'];
        }else{
            fn_ajax_return(0,'联系人必填');
        }
        if(!isset($condition['mobile']) && !isset($condition['qq']) && !isset($condition['wangwang'])){
            fn_ajax_return(0,'手机号,qq,旺旺必须填一个');
        }
        if(isset($condition['mobile']) && $condition['mobile'])
        {
            $re = $this->dataOnly(['mobile'=>$condition['mobile'],'id'=>$condition['id']],1);
            if($re) fn_ajax_return(0,'手机号码已存在');
            $data['mobile'] = $condition['mobile'];
        }
        if(isset($condition['qq']) && $condition['qq'])
        {
            $data['qq'] = $condition['qq'];
        }
        if(isset($condition['wangwang']) && $condition['wangwang'])
        {
            $data['wangwang'] = $condition['wangwang'];
        }
        if(isset($condition['admin_id']) && $condition['admin_id'])
        {
            $data['admin_id'] = $condition['admin_id'];
        }
        $data['update_time'] = time();
        return $this->update($data,['id'=>$condition['id']]);
    }

}