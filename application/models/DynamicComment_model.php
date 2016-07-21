<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 下午8:49
 */
class DynamicComment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * // 获取当前 动态的 所有评论
     * // 按评论个数先后顺序 大于10
     *
     * @param $id
     * @return array comments
     */
    public function getHotCommentsById($id) {
        // 热门评论, 第n页评论, 第n页热门评论
        $page = $this->input->get('p');
        $time = $this->input->get('t');

        // 获取默认请求
        $this->config->load('config');
        $hot_comment_default_num = $this->config->item('hot_comment_default_num');
        $comment_per_request = $this->config->item('comment_per_request');
        $hot_comment_praise_start_num = $this->config->item('hot_comment_praise_start_num');

        $this->db->where('dynamic_id', $id);
        $this->db->where('praise >=', $hot_comment_praise_start_num);

        if (isset($time)) {
            $this->db->where('publish_date <', time());
        }

        // 根据点赞数目 倒序排列
        $this->db->order_by('praise', 'DESC');

        if (isset($page) && $page >= 1) {
            $start_index = ($page-1)*$comment_per_request;
            $comments = $this->db->get('dynamic_comment', $comment_per_request, $start_index);
        } else {
            $comments = $this->db->get('dynamic_comment', $hot_comment_default_num);
        }

        return $comments->result_array();
    }

    /**
     * // 获取当前 动态的 所有评论
     * // 默认按时间先后顺序
     *
     * @param $id int
     * @return array comments
     */
    public function getBasicCommentsById($id) {
        // 热门评论, 第n页评论, 第n页热门评论
        $page = $this->input->get('p');
        $time = $this->input->get('t');

        // 获取默认请求
        $this->config->load('config');
        $comment_per_request = $this->config->item('comment_per_request');

        $this->db->where('dynamic_id', $id);

        if (isset($time)) {
            $this->db->where('publish_date <', time());
        }

        // 根据点赞数目 倒序排列
        $this->db->order_by('publish_date', 'DESC');

        if (isset($page) && $page >= 1) {
            $start_index = ($page-1)*$comment_per_request;
            $comments = $this->db->get('dynamic_comment', $comment_per_request, $start_index);
        } else {
            $comments = $this->db->get('dynamic_comment', $comment_per_request);
        }

        return $comments->result_array();
    }
}