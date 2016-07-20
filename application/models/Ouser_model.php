<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午1:54
 */
class Ouser_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('ouser', $new);
    }

}