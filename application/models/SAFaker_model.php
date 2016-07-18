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
     * @return mixed
     */
    public function addFakerCity($data) {
        return $this->db->insert_batch('city', $data);
    }

    /**
     * @param $data array 向表si_school中添加数据
     * @return
     */
    public function addFakerSchool($data) {
        return $this->db->insert_batch('school', $data);
    }

    /**
     * @param $data array 向表si_user_type添加假数据
     * @return bool
     */
    public function addFakerUserType($data) {
        return $this->db->insert_batch('user_type', $data);
    }

    /**
     * @param $data array 向表si_user_social中添加数据
     */
    public function addFakerUserSocial($data){
        return $this->db->insert_batch('user_social', $data);
    }

    /**
     * @param $data array 向表 si_user 中添加数据
     * @return mixed
     */
    public function addFakerUser($data) {
        return $this->db->insert_batch('user', $data);
    }
}