<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/8
 * Time: 上午9:34
 */
class UserSocial_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function createRow() {
        $this->db->insert('user_social', [
            'qq' => '',
            'is_qq' => 0,
            'wechat' => '',
            'is_wechat' => 0,
            'weibo' => '',
            'is_weibo' => 0
        ]);
        return $this->db->insert_id();
    }
}