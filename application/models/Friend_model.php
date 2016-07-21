<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 下午1:53
 */
class Friend_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 判断用户 a/b是不是好友关系
     *
     * @param $id int
     * @param $friend_id int
     * @return bool
     */
    public function is_friend($id, $friend_id) {
        $query = $this->db->where([
            'user_id' => $id,
            'friend_user_id' => $friend_id
        ])->get('friend');

        $data = $query->row_array();

        return !empty($data);
    }


    /**
     * 返回当前 用户 所有朋友用户id
     *
     * @param $id int
     * @return array
     */
    public function getFriends($id) {
        $query = $this->db->where([
            'user_id' => $id
        ])->get('friend');
        $friends = $query->result_array();
        $result = [];
        foreach ($friends as $val) {
            $result[] = $val['friend_user_id'];
        }

        return $result;
    }

}