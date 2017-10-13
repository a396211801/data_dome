<?php

class Controller_Index extends Front
{

    protected $layout = 'base';
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->display('index');
        Header("Location: /plan/index");
        exit;
    }

}
