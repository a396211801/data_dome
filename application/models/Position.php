<?php
class Model_Position extends Smodel {
    protected $table = "position";

    /**
     * @param array $data
     * @return array|mixed
     * 权限组添加
     */
    public function getlist($data = []){
        return $this->select(['id','name'],[]);
    }


    /**
     * @param array $data
     * @return array|mixed
     * 权限组添加
     */
    public function add($data = []){
        if(!isset($data['name']) && !$data['name']) fn_ajax_return(0,'权限名必填','');
        $data['create_time'] = time();
        return $this->insert($data);
    }

    /**
     * @param array $data
     * @return array|mixed
     * 权限组修改
     */
    public function edit($data = []){
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        $data['update_time'] = time();
        return $this->update($data);
    }

    /**
     * @param array $data
     * @return array|mixed
     * 权限组删除
     */
    public function del($data = []){
        if(!isset($data['id'])) fn_ajax_return(0,'参数错误','');
        //先查询是否占用
        $model_admin = new Model_Admin();
        $res = $model_admin->has(['position_id'=>$data['id']]);
        if($res){
            fn_ajax_return(0,'该权限正在使用,请先解除关系再删除','');
        }
        return $this->delete($data);
    }
}