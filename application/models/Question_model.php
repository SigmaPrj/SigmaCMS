<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午2:43
 */
class Question_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取所有问题 提供一些筛选调剂
     *
     * 业务 : 1 p 分页 t 时间戳 state 排序方式 c 话费方式
     */
    public function getAllQuestions() {
        $this->_do_select();

        return $this->_get_question();
    }

    /**
     * 根据id获取问题
     *
     * @param $q_id
     * @return mixed
     */
    public function getQuestionById($q_id) {
        $query = $this->where('id', $q_id)->get('question');

        $question = $query->row_array();

        // 获取用户信息
        $this->load->model('User_model', 'userModel');
        $user = $this->userModel->getUserDataBrief([$question['user_id']]);

        $question['user'] = $user[$question['user_id']];

        return $question;
    }


    /**
     * 根据特定话题获取问题
     *
     * @param $topic_id
     * @return mixed
     */
    public function getQuestionsByTopicId($topic_id) {
        $this->_do_select();
        // 添加筛选条件
        $this->db->where('topic_id', $topic_id);

        return $this->_get_question();
    }

    /**
     * 设置完筛选条件后, 组合user数据,返回组合后结果
     *
     * @return array
     */
    public function _get_question() {
        $query = $this->db->get('question');

        $questions = $query->result_array();

        if (empty($questions)) {
            return [];
        }

        // 添加用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $questions));

        foreach ($questions as &$value) {
            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = null;
            }
        }

        return $questions;
    }

    /**
     * 提取公共方法
     */
    public function _do_select() {
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $cost = $this->input->get('c'); // 默认全部
        $state = $this->input->get('state');

        // 载入一些配置
        $this->config->load('config');
        $question_per_request = $this->config->item('question_per_request');
        $question_default_num = $this->config->item('question_default_num');

        $this->db->select('question.id as id, user_id, topic_id, topic.name as topic, title, url, duration, pay_type, pay_num, is_free, look, save, praise, publish_date, last_look_date');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($cost) && ($cost === 'coin')) {
            // 硬币方式支付
            $this->db->where('is_free', 0);
            $this->db->where('pay_type', 2);
        } else if (isset($cost) && ($cost === 'point')) {
            // 积分方式支付
            $this->db->where('is_free', 0);
            $this->db->where('pay_type', 1);
        } else if (isset($cost) && ($cost === 'free')) {
            // 免费的
            $this->db->where('is_free', 1);
        }

        // 连接topic
        $this->db->join('topic', 'question.topic_id = topic.id');

        if (isset($state) && ($state === 'hot')) {
            // 按热度排序
            $this->db->order_by('praise', 'DESC');
        } else {
            // 默认按时间选后排序
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$question_per_request;
            $this->db->limit($question_per_request, $start_index);
        } else {
            $this->db->limit($question_default_num, 0);
        }
    }
}