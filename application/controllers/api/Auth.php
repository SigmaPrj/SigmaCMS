<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/10
 * Time: 上午9:08
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Auth
 */
class Auth extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function auth_post(){
        $username = $this->post('username');
        $password = $this->post('password');

        if ($username && $password) {
            // 进行用户验证
            $this->load->model('User_model', 'userModel');
            $userData = $this->userModel->getUserByUsername($username);
            if ($userData) {
                if ($password === $userData['password']) {
                    // 载入配置
                    $this->config->load('config');
                    $faker = \Faker\Factory::create();
                    $auth_prefix = $faker->regexify('[0-9a-zA-Z]{4,6}');
                    // 生成token
                    $token = md5($auth_prefix.md5($username).md5($password));
                    // 写入token
                    $time = time()+$this->config->item('auth_timeout');
                    $user_id = (int)$userData['id'];
                    $tokenData = [
                        'token' => $token,
                        'user_id' => $user_id,
                        'dead_time' => $time
                    ];
                    $this->load->model('Token_model', 'tokenModel');
                    if ($this->tokenModel->addToken($tokenData)) {
                        $this->response([
                            'status' => true,
                            'code' => REST_Controller::HTTP_OK,
                            'data' => [
                                'token' => $token,
                                'user' => $userData,
                                'time' => $time
                            ]
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'code' => 3,
                            'error' => 'Can\'t get token from host, wait for a minute!'
                        ], REST_Controller::HTTP_GATEWAY_TIMEOUT);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'code' => 2,
                        'error' => 'Username or Password is not right!'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'code' => '1',
                    'error' => 'Can\'t find user!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'code'  => 5,
                'error' => 'Your content-type should be application/x-www-form-urlencoded!'
            ], REST_Controller::HTTP_BAD_GATEWAY);
        }
    }
}