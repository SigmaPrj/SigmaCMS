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

            foreach ($data as &$value) {
                $value['group'] = $this->getfirstchar($value['name']);
            }

            $cities = [];
            foreach($data as &$v){
                $cities[$v['group']][] = $v;
            }

            if ($data) {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => $cities
                ], REST_Controller::HTTP_OK);
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
            $this->response([
                'status' => true,
                'code' => REST_Controller::HTTP_OK,
                'data' => $city
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'City not found'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function getfirstchar($s0){
        $firstchar_ord=ord(strtoupper($s0{0}));
        if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0};
        $s=iconv("UTF-8","gb2312", $s0);
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319 and $asc<=-20284)return "A";
        if($asc>=-20283 and $asc<=-19776)return "B";
        if($asc>=-19775 and $asc<=-19219)return "C";
        if($asc>=-19218 and $asc<=-18711)return "D";
        if($asc>=-18710 and $asc<=-18527)return "E";
        if($asc>=-18526 and $asc<=-18240)return "F";
        if($asc>=-18239 and $asc<=-17923)return "G";
        if($asc>=-17922 and $asc<=-17418)return "H";
        if($asc>=-17417 and $asc<=-16475)return "J";
        if($asc>=-16474 and $asc<=-16213)return "K";
        if($asc>=-16212 and $asc<=-15641)return "L";
        if($asc>=-15640 and $asc<=-15166)return "M";
        if($asc>=-15165 and $asc<=-14923)return "N";
        if($asc>=-14922 and $asc<=-14915)return "O";
        if($asc>=-14914 and $asc<=-14631)return "P";
        if($asc>=-14630 and $asc<=-14150)return "Q";
        if($asc>=-14149 and $asc<=-14091)return "R";
        if($asc>=-14090 and $asc<=-13319)return "S";
        if($asc>=-13318 and $asc<=-12839)return "T";
        if($asc>=-12838 and $asc<=-12557)return "W";
        if($asc>=-12556 and $asc<=-11848)return "X";
        if($asc>=-11847 and $asc<=-11056)return "Y";
        if($asc>=-11055 and $asc<=-10247)return "Z";
        return null;
    }
}