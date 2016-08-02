<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/2
 * Time: 下午4:53
 */
class NewsType_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllNewsTypes() {
        $query = $this->db->get('news_type');
        $newsTypes = $query->result_array();
        if (empty($newsTypes)) {
            return [];
        }

        return $newsTypes;
    }

    public function getNewsTypeById($id) {
        $query = $this->db->where('id', $id)->get('news_type');
        $newsType = $query->row_array();
        if (empty($newsType)) {
            return [];
        }

        return $newsType;
    }
}