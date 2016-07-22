<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 下午3:19
 */
require APPPATH . '/libraries/REST_Controller.php';
class UserType extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserType_model', 'userTypeModel');
    }

    public function userTypes_get() {
        $id = $this->get('id');

        if ($id === null) {
            // 获取所有数据
            $data = $this->userTypeModel->getAllUserTypes();
            if ($data) {
                $this->response($data, REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'No user types were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }

        // 获取特定的user type
        $code = (int)$id;

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_NOT_ACCEPTABLE,
                'error' => 'Invalid parameter'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }

        $city = $this->userTypeModel->getUserTypeByCode($code);
        if ($city) {
            $this->response($city, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'User type not found'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}