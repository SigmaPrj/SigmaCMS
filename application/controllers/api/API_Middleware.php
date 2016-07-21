<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午10:29
 */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Class API_Middleware
 */
class API_Middleware extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 核对token是否有效
     */
    public function check_token() {
        // 载入配置
        $this->config->load('config');
        $token = getHeader(strtolower($this->config->item('auth_token_name')));

        if ($token) {
            // 载入Token_model
            $this->load->model('Token_model', 'tokenModel');
            if ($this->tokenModel->is_valid($token)) {
                return true;
            } else {
                $this->response([
                    'status' => false,
                    'error' => 'Access token may be not exists or out of date!'
                ]);
            }
        } else {
            $this->response([
                'status' => false,
                'error' => 'Invalid access token'
            ]);
        }
    }
}