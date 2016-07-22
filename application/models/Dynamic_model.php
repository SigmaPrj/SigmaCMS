<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 下午1:16
 */
class Dynamic_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function _do_select($ids) {
        $page = $this->input->get('p');
        $time = $this->input->get('t');

        // 载入配置
        $this->config->load('config');
        $num_per_request = $this->config->item('num_per_request');

        // 查询
        $this->db->select('dynamic.id as id, user_id, has_topic, topic_id, topic.name as topic_name, content, publish_date, last_look_date, share, look, praise')
            ->from('dynamic')
            ->join('topic', 'dynamic.topic_id = topic.id');
        if (is_array($ids)) {
            $this->db->where_in('user_id', $ids);
        } else {
            $this->db->where('user_id', $ids);
        }
        if ($time) {
            // 时间
            $this->db->where([
                'publish_date <' => $time
            ]);
        }

        // 按时间从早到晚排序呢
        $this->db->order_by('publish_date', 'DESC');

        if ($page) {
            // 分页
            $start_index = ($page-1)*$num_per_request;
            $this->db->limit($num_per_request, $start_index);
        } else {
            $this->db->limit($num_per_request, 0);
        }
    }

    public function _get_dynamics() {
        $query = $this->db->get('dynamic');
        $tmpData = $query->result_array();
        if (empty($tmpData)) {
            return [];
        }

        // 获取每条动态的图片
        $this->load->model('DynamicImage_model', 'diModel');
        $images = $this->diModel->getDynamicImages(array_map(function ($value) {
            return $value['id'];
        }, $tmpData));
        // 获取每条动态的用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $tmpData));

        foreach ($tmpData as $key => &$value) {
            if (array_key_exists($value['id'], $images)) {
                $value['images'] = $images[$value['id']];
            } else {
                $value['images'] = null;
            }

            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = null;
            }
        }

        return $tmpData;
    }

    /**
     * 判断有没有page
     *
     * @param $ids array user_id 数组
     * @return mixed
     */
    public function getFriendsDynamics($ids) {
        $this->_do_select($ids);

        return $this->_get_dynamics();
    }

    /**
     * 根据id获取特定的动态信息
     *
     * @param $id int
     * @return mixed
     */
    public function getDynamicById($id) {
        $query = $this->db->where('id', $id)->get('dynamic');
        $data = $query->row_array();

        // 获取每条动态的图片
        $this->load->model('DynamicImage_model', 'diModel');
        $images = $this->diModel->getDynamicImages([$id]);
        // 获取每条动态的用户信息
        $this->load->model('User_model', 'userModel');
        $user = $this->userModel->getUserDataBrief([$data['user_id']]);

        if (array_key_exists($data['id'], $images)) {
            $data['images'] = $images[$data['id']];
        } else {
            $data['images'] = null;
        }

        if (array_key_exists($data['user_id'], $user)) {
            $data['user'] = $user[$data['user_id']];
        } else {
            $data['user'] = null;
        }

        return $data;
    }

}
