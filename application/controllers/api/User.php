<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午8:23
 */
require __DIR__.'/API_Middleware.php';
/**
 * Class User
 */
class User extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function users_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if (!isset($type)) {
            $this->load->model('User_model', 'userModel');
            $user = $this->userModel->getUserDataBrief($id);
            if (empty($user)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the user!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($user[$id], REST_Controller::HTTP_OK);
            }
        }

        $this->load->model('User_model', 'userModel');
        if ($type === 'brief') {
            $user = $this->userModel->getUserDataBrief($id);
            if (empty($user)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the user!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($user[$id], REST_Controller::HTTP_OK);
            }
        } else if ($type === 'basic') {
            $user = $this->userModel->getUserDataBasic($id);
            if (empty($user)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the user!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($user[$id], REST_Controller::HTTP_OK);
            }
        } else if ($type === 'all') {
            // TODO: 用户生分验证
            $user = $this->userModel->getUserDataAll($id);
            if (empty($user)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the user!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($user[$id], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}