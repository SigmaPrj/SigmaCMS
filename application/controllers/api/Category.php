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

        switch ($type) {
            case 'video':
            {

            }
                break;
            default:
            {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'error' => 'Invalid API'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
                break;
        }
    }
}