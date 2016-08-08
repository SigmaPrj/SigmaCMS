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
     * 根据 动态id 获得该动态下所有评论
     *
     * @param $id
     */
    public function getCommentsByDynamicId($id) {
        $this->_do_select($id);

        return $this->_get_comments();
    }

    public function _do_select($id) {
        // 热门评论, 第n页评论, 第n页热门评论
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

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

        if (isset($state) && ($state === 'hot')) {
            // 根据点赞数目 倒序排列
            $this->db->order_by('praise', 'DESC');
        } else {
            // 时间先后排序
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && $page >= 1) {
            $start_index = ($page-1)*$comment_per_request;
            $this->db->limit($comment_per_request, $start_index);
        } else {
            $this->db->limit($hot_comment_default_num, 0);
        }
    }

    public function _get_comments() {
        $query = $this->db->get('dynamic_comment');

        $comments = $query->result_array();

        // 获取user_id 的信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $comments));

        // 获取sub_id 的信息
        $sub_users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            if ($value['sub_id'] !== 0) {
                return $value['sub_id'];
            }
        }, $comments));

        foreach ($comments as &$value) {
            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = null;
            }

            if (array_key_exists($value['sub_id'], $sub_users)) {
                $value['sub_user'] = $sub_users[$value['sub_id']];
            } else {
                $value['sub_user'] = null;
            }
        }

        return $comments;
    }

    /**
     * @param $comments array 将评论数据与用户信息进行组合
     */
    private function _addUserInfoToComments(&$comments) {

    }

    /**
     * @param $comment
     * @return int 插入id
     */
    public function addComment($comment) {
        $this->db->insert('dynamic_comment', $comment);
        return $this->db->insert_id();
    }
}