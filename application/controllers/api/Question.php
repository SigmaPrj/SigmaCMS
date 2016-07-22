<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午2:41
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Question
 */
class Question extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function questions_get() {
        $id = $this->get('id');

        if (!isset($id)) {
            // 获取所有的问答
            $this->load->model('Question_model', 'qModel');
            $questions = $this->qModel->getAllQuestions();

            if (empty($questions)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any questions!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($questions, REST_Controller::HTTP_OK);
            }
        }

        // 获取特定 id
        $this->load->model('Question_model', 'qModel');
        $question = $this->qModel->getQuestionById($id);

        if (empty($question)) {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_NOT_FOUND,
                'error' => 'Can\'t find the question!'
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($question, REST_Controller::HTTP_OK);
        }
    }
}