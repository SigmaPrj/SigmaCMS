<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 下午3:21
 */
class UserType_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllUserTypes() {
        $query = $this->db->get('user_type');
        return $query->result_array();
    }

    public function getUserTypeByCode($code) {
        $query = $this->db->where(['code' => $code])
            ->get('user_type');
        return $query->row_array();
    }
}