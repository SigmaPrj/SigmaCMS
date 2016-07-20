<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/19
 * Time: 下午3:49
 */
class Callback extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config');
        $this->load->model('Image_model', 'imageModel');
    }

    public function handler($dbname, $field, $id) {
        $hash = $this->input->post('hash');

        if ($dbname === 'user') {
            $this->load->model('User_model', 'userModel');
            $this->userModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        }
    }
}