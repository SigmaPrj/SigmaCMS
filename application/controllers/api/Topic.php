<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午8:24
 */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class Topic
 */
class Topic extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 不传入id 返回所有的topic
     */
    public function topics() {
        $id = $this->get('id');
        $type = $this->get('type');
    }
}