<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/2
 * Time: 下午4:51
 */

require_once __DIR__.'/API_Middleware.php';
class NewsType extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function newsTypes_get() {
        $id = $this->get('id');

        if (!isset($id)) {
            // 获得全部活动
            $this->load->model('NewsType_model', 'ntModel');
            $newsTypes = $this->ntModel->getAllNewsTypes();
            if (empty($newsTypes)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any activities!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $newsTypes
                ], REST_Controller::HTTP_OK);
            }
        }

        // 获得特定id
        $this->load->model('NewsType_model', 'ntModel');
        $newsType = $this->ntModel->getNewsTypeById($id);

        if (empty($newsType)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_NOT_FOUND,
                'error' => 'Can\'t find the activity!'
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response([
                'status' => true,
                'code' => REST_Controller::HTTP_OK,
                'data' => $newsType
            ], REST_Controller::HTTP_OK);
        }

    }
}