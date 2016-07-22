<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午11:22
 */
class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获得 所有分类
     */
    public function getAllCategories() {
        $query = $this->db->get('category');
        $tmpData = $query->result_array();
        $categories = [];

        foreach ($tmpData as $item) {
            if ((int)$item['parent_id'] === 0) {
                $categories[$item['id']] = $item;
            }
        }

        foreach ($tmpData as $item) {
            if ((int)$item['parent_id'] !== 0) {
                $categories[$item['parent_id']]['categories'][] = $item;
            }
        }

        return $categories;
    }


}