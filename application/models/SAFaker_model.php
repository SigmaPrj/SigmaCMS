<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/16
 * Time: 下午11:23
 */
class SAFaker_model extends CI_Model
{
    /**
     * Faker_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * @param $data array 向表si_city中添加数据
     */
    public function addFakerCity($data) {
        return $this->db->insert_batch('city', $data);
    }

    /**
     * @param $data array 向表si_user中添加数据
     */
    public function addFakerUser($data){

    }
}