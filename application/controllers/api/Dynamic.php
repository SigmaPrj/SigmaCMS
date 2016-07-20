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

    /**
     * 业务 : 1 根据 用户id 获取用户所有动态 /user/user_id/dynamic/dynamic_id
     *       2 根据 话题id 获取话题下所有动态 /dynamic/dynamic_id/topic/topic_id
     *       3 根据 用户id 获取该用户所有好友的动态 /dynamic/dynamic_id/user/user_id
     */
    public function dynamics_get(){

    }
}