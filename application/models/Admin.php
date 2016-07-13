<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午5:12
 */
class Admin extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // 判断是否是有效的用户名
    public function is_valid_username($username) {

        $query = $this->db->get_where('admin', [
            'username' => $username
        ]);

        return $query->num_rows();
    }

    // 判断是否是有效的密码
    public function is_valid_password($username, $password) {
        $md5_pre = $this->config->item('si_md5');
        $md5_pass = md5($md5_pre.$password);
        $query = $this->db->where([
            'username' => $username,
            'password' => $md5_pass
        ])->get('admin');

        return  $query->num_rows();
    }

}