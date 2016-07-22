<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 上午9:30
 */

require APPPATH . '/libraries/REST_Controller.php';
class City extends REST_Controller
{
    /**
     * City constructor.
     */
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('city_model', 'cityModel');
    }

    /**
     *  获取所有城市
     *  根据code获取城市
     */
    public function cities_get() {
        $id = $this->get('id');
        if ($id === null) {
            // 获取所有数据
            $data = $this->cityModel->getAllCities();
            if ($data) {
                $this->response($data, REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'No cities were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }

        // 获取特定的city
        $code = (int)$id;

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'code '=> REST_Controller::HTTP_NOT_ACCEPTABLE,
                'error' => 'Invalid parameter'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }

        $city = $this->cityModel->getCityByCode($code);
        if ($city) {
            $this->response($city, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'City not found'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}