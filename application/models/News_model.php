<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午2:06
 */
class News_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('news', $new);
    }
}