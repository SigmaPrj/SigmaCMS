<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 下午1:18
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Friend
 */
class Friend extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  业务 : 1 根据id 获取所有好友id
     *        2 根据id type=dynamic 获得该用户好友的所有动态
     *        3 根据id type=user 获取该用户好友的基本信息
     *
     *  难点 : 用户有用户信息访问权限。所以,需要判断,当前用户是否是id本身。
     */
    public function friends_get() {
        $user_id = $this->get('user_id');
        $type = $this->get('type');
        $this->config->load('config');
        $token = getHeader($this->config->item('auth_token_name'));

        if (!$user_id) {
            // 没有传入id , 直接请求 返回错误
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // 判断token是否有效
        $this->load->model('Token_model', 'tokenModel');
        if (!$this->tokenModel->is_valid($token)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST, // token无效
                'error' => 'This is not a valid token!',
                'token' => $token
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // 是否有权限继续后续操作
        if (!$this->_has_privilege($token, $user_id)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_FORBIDDEN,
                'error' => 'You have no privilege!'
            ], REST_Controller::HTTP_FORBIDDEN);
        }

        // 获取所有用户id
        $this->load->model('Friend_model', 'friendModel');
        $friends = $this->friendModel->getFriends($user_id);

        if (!$type) {
            // 返回所有人id
            $this->response([
                'friends' => $friends
            ], REST_Controller::HTTP_OK);
        }

        // 有type属性
        if ($type === 'user') {
            $this->load->model('User_Model', 'userModel');
            $datas = $this->userModel->getUserDataBrief($friends);
            if (empty($datas)) {
                $this->response([
                    'status' => false ,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Not found any friends'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $datas
                ], REST_Controller::HTTP_OK);
            }
        } else if ($type === 'dynamic') {
            $this->load->model('Dynamic_model', 'dynamicModel');

            $friends[] = $user_id;

            $datas = $this->dynamicModel->getFriendsDynamics($friends);

            if (empty($datas)) {
                $tNow = $this->get('now');
                $tC = $this->get('c');
                if (isset($tNow)) {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'requestType' => 1,
                        'error' => 'Can\'t find any dynamic!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else if (isset($tC)) {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'requestType' => 2,
                        'error' => 'Can\'t find any dynamic!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'requestType' => 0,
                        'error' => 'Can\'t find any dynamic!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
            $this->response([
                'status' => true,
                'code' => REST_Controller::HTTP_OK,
                'data' => $datas
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false ,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    private function _has_privilege($token, $user_id) {
        // 获取当前用户user_id的权限
        $token_user_id = $this->tokenModel->getUserIdByToken($token);
        $this->load->model('User_model', 'userModel');
        $user_privilege_data = $this->userModel->getUserPrivilegeData($user_id);

        // 获取当前用户user_id的权限
        switch ($user_privilege_data['friend_visibility']) {
            case 0:
            {
                // 仅自己可见
                if ($token_user_id === $user_id) {
                    return true;
                } else {
                    return false;
                }
            }
                break;
            case 1:
            {
                // 朋友可见
                $this->load->model('Friend_model', 'friendModel');
                if ($this->friendModel->is_friend($user_id, $token_user_id)) {
                    return true;
                } else {

                    return false;
                }
            }
                break;
            case 2:
            {
                return true;
            }
                break;
            default:
            {
                return false;
            }
                break;
        }
    }
}
