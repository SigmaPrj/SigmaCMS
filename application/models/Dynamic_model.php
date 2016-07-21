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

    /**
     * 判断有没有page
     */
    public function getFriendsDynamics($ids) {
        $page = $this->input->get('p');
        $time = $this->input->get('t');

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
            $this->config->load('config');
            $num_per_request = $this->config->item('num_per_request');
            $start_index = ($page-1)*$num_per_request;
            $query = $this->db->get(null, $num_per_request, $start_index);
        } else {
            $query = $this->db->get();
        }

        $tmpData = $query->result_array();
        if (empty($tmpData)) {
            return [];
        }

        // 获取每条动态的图片
        $this->load->model('DynamicImage_model', 'diModel');
        $images = $this->diModel->getDynamicImages(array_map(function ($value) {
            return $value['id'];
        }, $tmpData));
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $tmpData));

        foreach ($tmpData as $key => &$value) {
          if (array_key_exists($value['id'], $images)) {
              $value['images'] = $images[$value['id']];
          } else {
              $value['images'] = [];
          }

          if (array_key_exists($value['user_id'], $users)) {
              $value['user'] = $users[$value['user_id']];
          } else {
              $value['user'] = [];
          }
        }

        return $tmpData;
    }

}
