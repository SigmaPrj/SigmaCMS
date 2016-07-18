<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 上午9:35
 */
class City_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllCities() {
        $query = $this->db->get('city');
        return $query->result_array();
    }

    public function getCityByCode($code) {
        $query = $this->db->where(['code' => $code])
                          ->get('city');
        return $query->row_array();
    }
}