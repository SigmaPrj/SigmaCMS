<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午9:48
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Experience
 */
class Experience extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function experiences_get() {
        $id = $this->get('id'); // 是否有id
        $type = $this->get('type'); // 是否有type

        if (!isset($id)) {
            // 获取所有experience
            $this->load->model('Experience_model', 'exModel');
            $experiences = $this->exModel->getAllExperiences();

            if (empty($experiences)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any more experience!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($experiences, REST_Controller::HTTP_OK);
            }
        }

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid parameter'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($type)) {
            // 获取特定的experience
            $this->load->model('Experience_model', 'exModel');
            $experience = $this->exModel->getExperienceById($id);
            if (empty($experience)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find this experience!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($experience, REST_Controller::HTTP_OK);
            }
        }

        // 设置了type
        switch ($type) {
            case 'comment':
            {
                $this->load->model('ExperienceComment_model', 'ecModel');
                $comments = $this->ecModel->getAllCommentsByExperienceID($id);

                if (empty($comments)) {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'error' => 'Can\'t find any comments!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $this->response($comments, REST_Controller::HTTP_OK);
                }
            }
                break;
            default:
            {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'error' => 'Invalid API'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
                break;
        }

    }
}