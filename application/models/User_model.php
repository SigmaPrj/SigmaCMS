<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/19
 * Time: 上午10:07
 */
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('user', $new);
    }

    /**
     * @param $username string 根据用户名获取用户的数据
     */
    public function getUserByUsername($username) {
        $query = $this->db->where([
            'username' => $username
        ])->get('user');
        return $query->row_array();
    }
}