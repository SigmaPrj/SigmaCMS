<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 下午2:15
 */
require APPPATH . '/libraries/REST_Controller.php';
class School extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('School_model', 'schoolModel');
    }

    public function schools_get() {
        $id = $this->get('id');
        $code = $this->get('code');
        $limit = $this->get('limit');

        if ($id === null) {
            if ($code === null) {
                // 获取所有school
                $limit = (int)$limit;
                $schools = $limit?$this->schoolModel->getAllSchools($limit):$this->schoolModel->getAllSchools(null);
                if ($schools) {
                    $this->response($schools, REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'error' => 'No schools were found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // 获取特定城市下所有用户
                $code = (int)$code;
                $limit = (int)$limit;
                $schools = $limit?$this->schoolModel->getSchoolsWithCityCode($code, $limit):$this->schoolModel->getSchoolsWithCityCode($code, null);
                if ($schools) {
                    $this->response($schools, REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'code' => REST_Controller::HTTP_BAD_REQUEST,
                        'error' => 'No schools were found . Bad city code'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }

        // 获取特定id的school
        $id = (int)$id;
        $school = $this->schoolModel->getSchoolWithCode($id);
        if ($school) {
            $this->response($school, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'No school were found . Bad school code'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}