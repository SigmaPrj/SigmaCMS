<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午2:55
 */
class DynamicImage_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function addImageCallback($data) {
        return $this->db->insert('dynamic_image', $data);
    }
}