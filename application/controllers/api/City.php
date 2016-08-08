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
                $value['group'] = $this->_getFirstCharter($value['name']);
            }

            $cities = [];
            foreach($data as &$v){
                $cities[$v['group']][] = $v;
                unset($v['group']);
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

    public function _getFirstCharter($str){
        if(empty($str)){return '';}
        $fchar=ord($str{0});
        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
        $s1=iconv('UTF-8','gb2312',$str);
        $s2=iconv('gb2312','UTF-8',$s1);
        $s=$s2==$str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return null;
    }
}