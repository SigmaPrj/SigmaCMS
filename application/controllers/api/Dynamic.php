<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午4:53
 */
require APPPATH . '/libraries/REST_Controller.php';
class Dynamic extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dynamic_model', 'dyModel');
    }

    public function dynamics_get(){
        // TODO :
    }
}