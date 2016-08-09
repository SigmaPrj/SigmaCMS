<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/9
 * Time: 下午8:47
 */
require __DIR__.'/API_Middleware.php';
class Upload extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取各种上传七牛的token
     */
    public function upload_get() {
        $type = $this->get('type');
        if (!isset($type)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($type === 'user_avatar') {
            // 上传用户头像
            $token = generateQNToken('/api/callback/user_avatar', ['userId']);
            $this->response([
                'status' => true,
                'code' => REST_Controller::HTTP_OK,
                'data' => [
                    'token' => $token
                ]
            ], REST_Controller::HTTP_OK);
        }
    }
}