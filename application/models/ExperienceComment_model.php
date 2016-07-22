<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午10:25
 */
class ExperienceComment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取特定 经验分享 中的所有评论内容
     *
     * @param $ex_id int
     * @return mixed
     */
    public function getAllCommentsByExperienceID($ex_id) {
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

        // 载入配置
        $this->config->load('config');
        $comment_per_request = $this->config->item('comment_per_request');
        $hot_comment_default_num = $this->config->item('hot_comment_default_num');
        $hot_comment_praise_start_num = $this->config->item('hot_comment_praise_start_num');

        $this->db->where('experience_id', $ex_id);

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state)) {
            switch ($state) {
                case 'hot':
                {
                    $this->db->where('praise >=', $hot_comment_praise_start_num);
                    $this->db->order_by('praise', 'DESC');
                }
                    break;
                case 'basic':
                default:
                {
                    $this->db->order_by('publish_date', 'DESC');
                }
                    break;
            }
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$comment_per_request;
            $query = $this->db->get('experience_comment', $comment_per_request, $start_index);
        } else {
            if (isset($state)) {
                switch ($state) {
                    case 'hot':
                    {
                        $query = $this->db->get('experience_comment', $hot_comment_default_num, 0);
                    }
                        break;
                    case 'basic':
                    default:
                    {
                        $query = $this->db->get('experience_comment', $comment_per_request, 0);
                    }
                        break;
                }
            } else {
                $query = $this->db->get('experience_comment', $comment_per_request, 0);
            }
        }

        $comments = $query->result_array();

        // 载入用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function($value) {
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