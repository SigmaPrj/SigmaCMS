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
        // 如果已经存在Token,就更新token, 否则就添加token
        $query = $this->db->where([
            'user_id' => $data['user_id']
        ])->get('token');
        $tokenData = $query->row_array();
        if ($tokenData) {
            return $this->db->set($data)->where('id', $tokenData['id'])->update('token');
        } else {
            return $this->db->insert('token', $data);
        }
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
        if (mt_rand(1, 10000) === 100) {
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


    /**
     * 判断 $user_id 是否是该 $token 所有者
     *
     * @param $token string
     * @param $user_id int
     * @return bool
     */
    public function is_owner($token, $user_id) {
        $query = $this->db->where([
            'token' => $token,
            'user_id' => $user_id
        ])->get('token');
        $tokenData = $query->row_array();
        if ($tokenData) {
            return true;
        } else {
            return false;
        }
    }


    public function getUserIdByToken($token) {
        $query = $this->db->where([
            'token' => $token
        ])->get('token');

        $data = $query->row_array();

        return empty($data)?null:$data['user_id'];
    }
}