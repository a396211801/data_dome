<?php

class Controller_Index extends Admin
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->display('index');
        Header("Location: /admin/plan/index");
        exit;
    }


}