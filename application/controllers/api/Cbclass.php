<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/9
 * Time: 下午8:56
 */
require __DIR__.'/API_Middleware.php';

/**
 * Class Callback
 */
class Cbclass extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 处理所有文件上传到七牛的回调
     */
    public function callback_post() {
        $type = $this->get('type');

        if (!isset($type)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        

        // 上传用户头像回调
        if ($type === 'user_avatar') {
            $userId = (int)$this->post('user_id');
            $hash = $this->post('hash');
            $url = getQNFileUrl($hash);

            $this->load->model('User_model', 'userModel');
            if ($this->userModel->updateUserInfo([
                'image' => $url
            ], $userId)) {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => [
                        'user_id' => $userId,
                        'image' => $url
                    ]
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_SERVICE_UNAVAILABLE,
                    'error' => 'Can not modify user avatar!'
                ], REST_Controller::HTTP_SERVICE_UNAVAILABLE);
            }
        }
    }
}