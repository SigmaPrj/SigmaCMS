<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午10:21
 */

/**
 * Class ActivityComment_model
 */
class ActivityComment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function _do_select() {
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

        // 载入一些配置
        $this->config->load('config');
        $comment_per_request = $this->config->item('comment_per_request');
        $hot_comment_default_num = $this->config->item('hot_comment_default_num');
        $hot_comment_praise_start_num = $this->config->item('hot_comment_praise_start_num');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state) && ($state === 'hot')) {
            $this->db->order_by('praise', 'DESC');
            $this->db->where('praise >=', $hot_comment_praise_start_num);
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$comment_per_request;
            $this->db->limit($comment_per_request, $start_index);
        } else {
            if (isset($state) && ($state === 'hot')) {
                $this->db->limit($hot_comment_default_num, 0);
            } else {
                $this->db->limit($comment_per_request, 0);
            }
        }
    }

    public function _get_comments() {
        $query = $this->db->get('activity_comment');
        $comments = $query->result_array();

        if (empty($comments)) {
            return [];
        }

        // 获得用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $comments));

        foreach ($comments as &$comment) {
            if (array_key_exists($comment['user_id'], $users)) {
                $comment['user'] = $users[$comment['user_id']];
            } else {
                $comment['user'] = null;
            }
        }

        return $comments;
    }

    public function getAllCommentsByActivityId($ac_id) {
        $this->_do_select();

        $this->db->where('activity_id', $ac_id);

        return $this->_get_comments();
    }
}