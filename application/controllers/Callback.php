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
        } else if ($dbname === 'advertisement') {
            $this->load->model('Advertisement_model', 'advModel');
            $this->advModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        } else if ($dbname === 'video') {
            $this->load->model('Video_model', 'videoModel');
            $this->videoModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        } else if ($dbname === 'ouser') {
            $this->load->model('Ouser_model', 'ouserModel');
            $this->ouserModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        } else if ($dbname === 'activity') {
            $this->load->model('Activity_model', 'acModel');
            $this->acModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        } else if ($dbname === 'news') {
            $this->load->model('News_model', 'newsModel');
            $this->newsModel->upd([
                $field => $this->config->item('qiniu_domain').$hash
            ], $id);
        } else if ($dbname === 'dynamic_image') {
            $this->load->model('DynamicImage_model', 'diModel');
            $this->diModel->addImageCallback([
                'dynamic_id', $id,
                $field => $this->config->item('qiniu_domain').$hash
            ]);
        }

        echo json_encode([
            'hash' => $hash
        ]);
    }
}