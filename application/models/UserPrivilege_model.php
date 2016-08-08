<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 下午2:02
 */
class UserPrivilege_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getUserPrivilege($id) {
        $query = $this->db->where([
            'id' => $id
        ])->get('user_privilege');
        return $query->row_array();
    }

    public function createRow() {
        $this->db->insert('user_privilege', [
            'friend_visibility' => 1,
            'follow_visibility' => 2,
            'sex_visibility' => 2,
            'name_visibility' => 1,
            'phone_visibility' => 1,
            'email_visibility' => 1
        ]);
        return $this->db->insert_id();
    }
}