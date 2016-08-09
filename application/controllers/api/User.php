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
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $user[$id]
                ], REST_Controller::HTTP_OK);
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
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $user[$id]
                ], REST_Controller::HTTP_OK);
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
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $user[$id]
                ], REST_Controller::HTTP_OK);
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
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $user[$id]
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function users_post() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            $user = [];
            $user['username_type'] = $this->post('username_type');
            $user['password'] = $this->post('password');

            if ($user['username_type'] === 'email') {
                $user['username'] = $this->post('email');
                $user['email'] = $this->post('email');
            } else if ($user['username_type'] === 'phone') {
                $user['username'] = $this->post('phone');
                $user['phone'] = $this->post('phone');
            } else {
                $user['username'] = $this->post('customer');
                $user['customer'] = $this->post('customer');
            }

            $this->load->model('User_model', 'userModel');
            if ($this->userModel->checkoutUsername($user['username'])) {
                $user['nickname'] = '';
                $this->load->model('UserSocial_model', 'usModel');
                $user['user_social'] = $this->usModel->createRow();
                $this->load->model('UserPrivilege_model', 'upModel');
                $user['user_privilege'] = $this->upModel->createRow();
                $user['is_active'] = 1;
                $user['active_date'] = time();

                if ($user_id = $this->userModel->addUser($user)) {
                    $this->response([
                        'status' => true,
                        'code' => REST_Controller::HTTP_OK,
                        'data' => [
                            'user_id' => $user_id
                        ]
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_FORBIDDEN,
                        'error' => 'Register failed!'
                    ], REST_Controller::HTTP_FORBIDDEN);
                }
            } else {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Username has existed!'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }

        if (!isset($type)) {
            // TODO : 修改一个用户信息
            $params = $this->post();
            $this->load->model('User_model', 'userModel');
            if ($this->updateUserInfo($params, $id)) {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => [
                        'id' => $id
                    ]
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_SERVICE_UNAVAILABLE,
                    'error' => 'Can not modify the user info!'
                ], REST_Controller::HTTP_SERVICE_UNAVAILABLE);
            }
        }
    }
}