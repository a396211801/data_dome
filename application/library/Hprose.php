<?php
/**
 * 为了防止yaf报错而建立的hprose类
 */
class Hprose extends \Hprose\Http\Client
{
    private static $_instance;

    public function __construct($url='') {
        $_url = $url;
        if ( !$url )
        {
            $config = $url ? $url : Yaf_Registry::get('config')->get('hprose.params')->toArray();
            $_url = 'http://' . $config['host'] . ':' . $config['port'];
        }
        parent::__construct($url);
    }

    public static function getInstance($url='')
    {
        if ( !self::$_instance instanceof self )
        {
            $config = $url ? $url : Yaf_Registry::get('config')->get('hprose.params')->toArray();
            $config_url = 'http://' . $config['host'] . ':' . $config['port'];
            if ( $url && $url != $config_url ){
                self::$_instance = new self($url);
            }else{
                self::$_instance = new self($config_url);
            }
        }
        return self::$_instance;
    }

    /**
     * 动态调用rpc方法, 第一个参数方法名称, 后面参数是方法参数
     * 返回参数自动处理异常,有异常会在errors里面显示
     * @return stdClass {result, errors}
     */
    public function call()
    {
        $result =new stdClass();
        $result->result = null;
        $result->errors = null;


        $args = func_get_args();
        if (count($args) < 0) {
            $result->errors = "参数格式错误";
            return $result;
        }

        $funName = $args[0];
        unset($args[0]);
        try {
            $info = call_user_func_array(array($this, $funName), $args);
            if (is_object($info)) {
                $result->result = get_object_vars($info);
            } else {
                $result->result = $info;
            }
        }
        catch (Exception $e) {
            $result->errors = $e->getMessage();
        }
        return $result;
    }
}