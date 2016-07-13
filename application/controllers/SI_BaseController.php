<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午8:49
 */
class SI_BaseController extends CI_Controller
{
    /**
     * SI_BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 登录验证
        if (!$this->session->userdata('is_login')) {
            redirect(base_url('login/index.html'));
        }
    }
}