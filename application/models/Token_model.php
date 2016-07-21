<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午9:46
 */
class Token_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function addToken($data) {
        return $this->db->insert('token', $data);
    }

    /**
     * @param $token string 判断token是否过期
     * @return bool 是否成功
     */
    public function is_valid($token) {
        $query = $this->db->where([
            'token' => $token
        ])->get('token');
        $data = $query->row_array();
        $now = time();

        // 万分之一的机会清除一次过期token
        if (random_int(1, 10000) === 100) {
            $this->db->where('dead_time <', $now)->delete('token');
        }

        if ($data) {
            if ($now < $data['dead_time']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}