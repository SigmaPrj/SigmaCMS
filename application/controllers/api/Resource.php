<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午4:46
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Resource
 */
class Resource extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 如果没有id 获取所有资源
     *       如果有id 获得特定资源
     *       如果有id和特定type comment 获得评论
     *
     */
    public function resource_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 获取所有资源
            $this->load->model('Resource_model', 'reModel');
            $resources = $this->reModel->getAllResources();

            if (empty($resources)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any resources!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($resources, REST_Controller::HTTP_OK);
            }
        }

        if (!isset($type)) {
            // 获得特定id资源内容
            $this->load->model('Resource_model', 'reModel');
            $resource = $this->reModel->getResourceById($id);

            if (empty($resource)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the resource!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($resource, REST_Controller::HTTP_OK);
            }
        }

        if ($type === 'comment') {
            // 载入数据
            $this->load->model('ResourceComment_model', 'rcModel');
            $comments = $this->rcModel->getAllComments($id);
            if (empty($comments)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any comments!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($comments, REST_Controller::HTTP_OK);
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