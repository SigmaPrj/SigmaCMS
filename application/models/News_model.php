<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午2:06
 */
class News_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('news', $new);
    }

    public function _do_select() {
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

        // 载入配置
        $this->config->load('config');
        $news_pre_request = $this->config->item('news_pre_request');
        $news_default_num = $this->config->item('news_default_num');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state) && ($state === 'hot')) {
            $this->db->order_by('join', 'DESC');
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$news_pre_request;
            $this->db->limit($news_pre_request, $start_index);
        } else {
            $this->db->limit($news_default_num, 0);
        }
    }

    public function _get_news() {
        $query = $this->db->get('news');
        $news = $query->result_array();

        if (empty($news)) {
            return [];
        }

        return $news;
    }

    public function getAllNews() {
        $this->_do_select();

        return $this->_get_news();
    }

    public function getNewsById($id) {
        $query = $this->db->where('id', $id)->get('news');
        $news = $query->row_array();
        if (empty($news)) {
            return [];
        }

        return $news;
    }
}