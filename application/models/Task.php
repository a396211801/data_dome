<?php
class Model_Task extends Smodel {

    protected $table = "task";

    public function getList($positionid){

        $join = array(
            '[>]operation' => array(
                'id' => 'task_id'
            ),
        );
        $columns = array(
            'task.id',
            'task.name',
            'task.pid',
            'task.controller',
            'task.action',
            'task.sort',
        );
        $where['AND']['operation.position_id'] = $positionid;
        $where['ORDER'] = ' id asc ';
        $list = $this->select($join,$columns,$where);
        $list = $this->getChild($list);
        return $list;
    }

    /**
     * 递归列表
     * */
    public function getChild($array ,$pid=0){
        $arr = array();
        foreach ($array as $v) {
            if ($v['pid'] == $pid) {
                $v['child'] = $this->getChild($array, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }

    /**
     * 获取所有的控制器和方法
     */
    public function getAllList($n = 0){
        $result = $this->select(array('id','name'),['AND'=>['pid'=>$n]]);
        foreach($result as $k=>$v) {
            $result[$k]['child'] = $this->getAllList($v['id']);
        }
        return $result;
    }


}