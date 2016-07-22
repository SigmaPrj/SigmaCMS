<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午9:45
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Activity
 */
class Activity extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 没有id 获取全部活动内容
     *       有id  获得特定活动内容
     *       有id且有type(comment) 获得特定id活动下的评论内容
     */
    public function activities_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 获得全部活动
            $this->load->model('Activity_model', 'acModel');
            $activities = $this->acModel->getAllActivities();
            if (empty($activities)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any activities!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($activities, REST_Controller::HTTP_OK);
            }
        }

        if (!isset($type)) {
            // 获得特定id的活动内容
            $this->load->model('Activity_model', 'acModel');
            $activity = $this->acModel->getActivityById($id);

            if (empty($activity)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the activity!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($activity, REST_Controller::HTTP_OK);
            }
        }

        if ($type === 'comment') {
            // 获取特定id下所有评论
            $this->load->model('ActivityComment_model', 'acModel');
            $comments = $this->acModel->getAllCommentsByActivityId($id);

            if (empty($comments)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any comment!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($comments, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}