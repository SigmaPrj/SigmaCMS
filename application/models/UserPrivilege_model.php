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
}