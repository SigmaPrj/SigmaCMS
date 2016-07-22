<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 上午9:52
 */
class Experience_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function _do_select() {
        // 获得一些设定参数 page time hot
        $page = $this->input->get('p');
        $time = $this->input->get('t');
        $state = $this->input->get('state');

        // 载入配置
        $this->config->load('config');
        $experience_per_request = $this->config->item('experience_per_request');
        $experience_default_num = $this->config->item('experience_default_num');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state)) {
            switch ($state) {
                case 'time':
                {
                    $this->db->order_by('publish_date', 'DESC');
                }
                    break;
                case 'hot':
                default:
                {
                    $this->db->order_by('praise', 'DESC');
                }
                    break;
            }
        } else {
            $this->db->order_by('praise', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$experience_per_request;
            $this->db->limit($experience_per_request, $start_index);
        } else {
            $this->db->limit($experience_default_num, 0);
        }
    }

    public function _get_experiences() {
        $query = $this->db->get('experience');

        $experiences = $query->result_array();

        // 添加用户数据
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $experiences));

        foreach ($experiences as &$value) {
            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = [];
            }
        }

        return $experiences;
    }

    /**
     * 获取所有经验分享, 支持p t state
     *
     * @return mixed
     */
    public function getAllExperiences() {
        $this->_do_select();

        return $this->_get_experiences();
    }

    /**
     * 根据 id 获取具体的 experience
     *
     * @param $ex_id int
     * @return mixed
     */
    public function getExperienceById($ex_id) {
        $query = $this->db->where([
            'id' => $ex_id
        ])->get('experience');
        $experience = $query->row_array();

        // 获取user信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief([$experience['user_id']]);

        if (array_key_exists($experience['user_id'], $users)) {
            $experience['user'] = $users[$experience['user_id']];
        } else {
            $experience['user'] = null;
        }

        return $experience;
    }
}