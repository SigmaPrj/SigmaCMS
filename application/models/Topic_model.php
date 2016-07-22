<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午8:54
 */
class Topic_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * @return array 得到所有topic数据
     */
    public function getAllTopics()
    {
        // 按热度排行, 默认获取 config配置条数 每次请求获取config配置条数
        $page = $this->input->get('p'); // 页数
        $time = $this->input->get('t'); // 时间
        $state = $this->input->get('state'); // 按热度还是默认时间排序

        $this->config->load('config');
        $topic_per_request = $this->config->item('topic_per_request');
        $topic_default_num = $this->config->item('topic_default_num');

        if (isset($time)) {
            $this->db->where('active_date <', $time);
        }

        if (isset($state)) {
            switch ($state) {
                case 'hot':
                {
                    $this->db->order_by('dynamic_num', 'DESC');
                }
                    break;
                case 'time':
                default:
                {
                    $this->db->order_by('active_date', 'DESC');
                }
                    break;
            }
        } else {
            $this->db->order_by('active_date', 'DESC');
        }

        if (!isset($page) || ($page <= 0)) {
            $query = $this->db->get('topic', $topic_default_num, 0);
        } else {
            $start_index = ($page-1)*$topic_per_request;
            $query = $this->db->get('topic', $topic_per_request, $start_index);
        }

        return $query->result_array();
    }
}