<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午8:24
 */
require __DIR__.'/API_Middleware.php';

/**
 * Class Topic
 */
class Topic extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 不传入id 返回所有的topic 支持 p t state [hot/time] 默认hot
     *       传入id 返回特定topic 好像没太大作用
     *
     *       传入id 且 传入type值, 获取特定话题下的所有类型数据
     */
    public function topics_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 获取所有topic
            $this->load->model('Topic_model', 'topicModel');
            $topics = $this->topicModel->getAllTopics();
            if (empty($topics)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any topic!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($topics, REST_Controller::HTTP_OK);
            }
        }

        if ($id <= 0) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_NOT_ACCEPTABLE,
                'error' => 'Invalid parameter!'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($type)) {
            // 不支持获得特定的话题
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($type === 'dynamic') {
            // 获取特定话题下的所有动态
            $this->load->model('Topic_model', 'topicModel');
            $dynamics = $this->topicModel->getAllDynamicsByTopic($id);
            if (empty($dynamics)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any more dynamics!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($dynamics, REST_Controller::HTTP_OK);
            }
        } else if ($type === 'question') {
            //  获取特定话题下的问题
            $this->load->model('Question_model', 'qModel');
            $questions = $this->qModel->getQuestionsByTopicId($id);
            if (empty($questions)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find ant more questions!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($questions, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}