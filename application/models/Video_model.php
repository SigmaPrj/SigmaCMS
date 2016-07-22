<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午1:18
 */
class Video_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('video', $new);
    }

    /**
     * 获得所有视频
     *
     * @return mixed
     */
    public function getAllVideos() {
        $page = $this->input->get('p');
        $time = $this->input->get('time');
        $state = $this->input->get('state');

        // 载入配置
        $this->config->load('config');
        $video_per_request = $this->config->item('video_per_request');
        $video_default_num = $this->config->item('video_default_num');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state)) {
            if ($state === 'hot') {
                $this->db->order_by('learn', 'DESC');
            } else {
                $this->db->order_by('publish_date', 'DESC');
            }
        } else {
            // 默认按新旧排序
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$video_per_request;
            $query = $this->db->get('video', $video_per_request, $start_index);
        } else {
            $query = $this->db->get('video', $video_default_num, 0);
        }

        $videos = $query->result_array();

        // 获取所有ouser信息
        $this->load->model('Ouser_model', 'ouserModel');
        $ousers = $this->ouserModel->getOuserDataBrief(array_map(function($value) {
            return $value['ouser_id'];
        }, $videos));

        foreach ($videos as &$item) {
            if (array_key_exists($item['ouser_id'], $ousers)) {
                $item['ouser'] = $ousers[$item['ouser_id']];
            } else {
                $item['ouser'] = null;
            }
        }

        return $videos;
    }

    /**
     * 获得特定分类下的视频
     *
     * @param $cat_id
     * @return mixed
     */
    public function getVideosByCategoryId($cat_id) {
        $page = $this->input->get('p');
        $time = $this->input->get('time');
        $state = $this->input->get('state');

        // 载入配置
        $this->config->load('config');
        $video_per_request = $this->config->item('video_per_request');
        $video_default_num = $this->config->item('video_default_num');

        $this->db->where('category', $cat_id);

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state) && ($state === 'hot')) {
            $this->db->order_by('learn', 'DESC');
        } else {
            // 默认按新旧排序
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$video_per_request;
            $query = $this->db->get('video', $video_per_request, $start_index);
        } else {
            $query = $this->db->get('video', $video_default_num, 0);
        }

        $videos = $query->result_array();

        // 获取所有ouser信息
        $this->load->model('Ouser_model', 'ouserModel');
        $ousers = $this->ouserModel->getOuserDataBrief(array_map(function($value) {
            return $value['ouser_id'];
        }, $videos));

        foreach ($videos as &$item) {
            if (array_key_exists($item['ouser_id'], $ousers)) {
                $item['ouser'] = $ousers[$item['ouser_id']];
            } else {
                $item['ouser'] = null;
            }
        }

        return $videos;
    }

    /**
     * 根据特定$video_id获取特定video内容
     *
     * @param $video_id int
     * @return mixed
     */
    public function getVideoById($video_id) {
        $query = $this->db->where('id', $video_id)->get('video');
        $video = $query->row_array();

        if (empty($video)) {
            return [];
        }

        // 获取ouser信息
        $this->load->model('Ouser_model', 'ouserModel');
        $ouser = $this->ouserModel->getOuserDataBrief([$video['ouser_id']]);

        $video['ouser'] = $ouser;

        return $video;
    }
}