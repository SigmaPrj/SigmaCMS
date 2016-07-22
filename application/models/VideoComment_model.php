<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午2:09
 */
class VideoComment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 根据 $video_id 获取特定的视频下评论
     *
     * @param $video_id int
     * @return mixed
     */
    public function getAllCommentsByVideoId($video_id) {
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

        // 载入一些配置
        $this->config->load('config');
        $comment_per_request = $this->config->item('comment_per_request');
        $hot_comment_default_num = $this->config->item('hot_comment_default_num');
        $hot_comment_praise_start_num = $this->config->item('hot_comment_praise_start_num');

        $this->db->where('video_id', $video_id);

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state)) {
            if ($state === 'hot') {
                $this->db->order_by('praise', 'DESC');
                $this->db->where('praise >', $hot_comment_praise_start_num);
            } else {
                $this->db->order_by('publish_date', 'DESC');
            }
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$comment_per_request;
            $query = $this->db->get('video_comment', $comment_per_request, $start_index);
        } else {
            if (isset($state) && ($state === 'hot')) {
                $query = $this->db->get('video_comment', $hot_comment_default_num);
            } else {
                $query = $this->db->get('video_comment', $comment_per_request, 0);
            }
        }

        $comments = $query->result_array();

        if (empty($comments)) {
            return [];
        }

        // 获得用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $comments));

        foreach ($comments as &$value) {
            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = null;
            }
        }

        return $comments;
    }
}