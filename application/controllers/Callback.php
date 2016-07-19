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
        if (IS_POST()) {
            $fname = $this->input->post('fname');
            $fkey = $this->input->post('fkey');
            $fsize = $this->input->post('fsize');
            $hash = $this->input->post('hash');

            $data = [
                'fname' => $fname,
                'fkey' => $fkey,
                'fsize' => $fsize,
                'hash' => $hash
            ];

            // 添加图片
            $this->imageModel->add($data);

            if ($dbname === 'user') {
                $this->load->model('User_model', 'userModel');
                $this->userModel->update([
                    'id' => $id,
                    $field => $this->config->item('qiniu_domain').$fkey
                ]);
            }
        }
    }
}