<?php
class Model_Operation extends Smodel {
    protected $table = "operation";

    public function getTask($position=0){
        return $this->select('task_id',['position_id'=>$position]);
    }

    /**
     * 权限修改
     * */
    public function editJurisdiction($condition=[]){

        if(!isset($condition['position_id']) || !$condition['position_id']) return [];

        $position_id = $condition['position_id'];

        $task_id = isset($condition['task_id'])?$condition['task_id']:'';

        if(!$position_id) return [];
        $this->delete(['position_id'=>$position_id]);
        if($task_id){
            $task_id = explode(',',$task_id);
            foreach ($task_id as $itme){
                if($itme){
                    $this->insert(['position_id'=>$position_id,'task_id'=>$itme]);
                }
            }
        }else{
            //
            $position  = new Model_Position();
            $position->del(['id'=>$position_id]);
        }
        return 1;
    }

}