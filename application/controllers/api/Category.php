<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午11:16
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Category
 */
class Category extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务: 1 获取所有分类
     *      2 获取特定一个分类 没什么用
     *      3 获取特定分类下的 特定type内容
     */
    public function categories_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 获取所有分类
            $this->load->model('Category_model', 'catModel');
            $categories = $this->catModel->getAllCategories();

            if (empty($categories)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any categories!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($categories, REST_Controller::HTTP_OK);
            }
        }

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_NOT_ACCEPTABLE,
                'error' => 'Invalid parameter!'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($type)) {
            // 获取特定分类
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($type === 'video') {
            // 获取特定分类下的所有视频
            $this->load->model('Video_model', 'videoModel');
            $videos = $this->videoModel->getVideosByCategoryId($id);

            if (empty($videos)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any videos!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($videos, REST_Controller::HTTP_OK);
            }

        } else if ($type === 'resource') {
            // 根据分类获取资源
            $this->load->model('Resource_model', 'reModel');
            $resources = $this->reModel->getResourcesByCategoryId($id);
            if (empty($resources)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any resources!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($resources, REST_Controller::HTTP_OK);
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