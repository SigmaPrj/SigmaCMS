<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午1:59
 */
class Activity_model extends CI_Model
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

        // 载入配置
        $this->config->load('config');
        $activity_pre_request = $this->config->item('activity_pre_request');
        $activity_default_num = $this->config->item('activity_default_num');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state) && ($state === 'hot')) {
            $this->db->order_by('join', 'DESC');
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$activity_pre_request;
            $this->db->limit($activity_pre_request, $start_index);
        } else {
            $this->db->limit($activity_default_num, 0);
        }
    }

    public function _get_activities() {
        $query = $this->db->get('activity');
        $activities = $query->result_array();

        if (empty($activities)) {
            return [];
        }

        // 载入ouser信息
        $this->load->model('Ouser_model', 'ouserModel');
        $ousers = $this->ouserModel->getOuserDataBrief(array_map(function ($value) {
            return $value['ouser_id'];
        }, $activities));

        foreach ($activities as &$value) {
            if (array_key_exists($value['ouser_id'], $ousers)) {
                $value['ouser'] = $ousers[$value['ouser_id']];
            } else {
                $value['ouser'] = null;
            }
        }

        return $activities;
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('activity', $new);
    }

    public function getAllActivities() {
        $this->_do_select();

        return $this->_get_activities();
    }

    public function getActivityById($id) {
        $query = $this->db->where('id', $id)->get('activity');
        $activity = $query->row_array();
        if (empty($activity)) {
            return [];
        }

        // 添加ouser信息
        $this->load->model('Ouser_model', 'ouserModel');
        $ouser = $this->ouserModel->getOuserDataBrief([$activity['ouser_id']]);

        if (empty($ouser)) {
            $activity['ouser'] = null;
        } else {
            $activity['ouser'] = $ouser[$activity['ouser_id']];
        }

        return $activity;
    }
}