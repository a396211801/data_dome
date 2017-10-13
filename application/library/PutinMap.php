<?php
/**
 * 地图开关
 * @author  luocz
 */
class PutinMap{

    public function __construct(){

    }

    /**
     * 地图状态开关
     */
    public function mapStatus($id,$status,$type = 'advert'){
        if ($id <= 0 || !is_numeric($id)) {
            return false;
        }

        $res = Hprose::getInstance()->call('StatusHandler', strval($id), $type, strval($status));
        if ($res->errors != null) {
            return $res->errors;
        } else {
            $result = json_decode($res->result);
            return $result->Ret ? $result->Msg : $result->Data;
        }
    }



}
