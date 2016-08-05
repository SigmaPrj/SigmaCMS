<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/4
 * Time: 下午1:58
 */
require_once __DIR__.'/API_Middleware.php';
class Message extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function messages_get() {
        $user_id = $this->get('user_id');
        $type = $this->get('type');
        $type_id = $this->get('type_id');

        if (!isset($user_id)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if (!isset($type)) {
            // 获取所有消息
            $this->load->model('Message_model', 'messageModel');
            $messages = $this->messageModel->getAllMessages($user_id);
            if (!isset($messages)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any message!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $messages
                ], REST_Controller::HTTP_OK);
            }
        }

        if (!isset($type_id)) {
            if ($type !== 'team') {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'error' => 'Invalid API'
                ], REST_Controller::HTTP_BAD_REQUEST);
            } else {
                // 获取所有team消息
            }
        }

        if ($type === 'user') {
            $this->load->model('Message_model', 'messModel');
            $messages = $this->messModel->getMessagesAboutUser($user_id, $type_id);
            if (empty($messages)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any messages!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $messages
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
}