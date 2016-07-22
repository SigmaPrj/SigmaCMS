<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午5:00
 */
class Resource_model extends CI_Model
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

        // 获取配置信息
        $this->config->load('config');
        $resource_pre_request = $this->config->item('resource_pre_request');
        $resource_default_num = $this->config->item('resource_default_num');

        $this->db->select('resource.id as id, user_id, category_id, category.name as category, image, title, description, resource_type, url, save, look, download, publish_date, last_look_date');
        $this->db->join('category', 'resource.category_id = category.id');

        if (isset($time)) {
            $this->db->where('publish_date <', $time);
        }

        if (isset($state) && ($state === 'hot')) {
            $this->db->order_by('download', 'DESC');
        } else {
            $this->db->order_by('publish_date', 'DESC');
        }

        if (isset($page) && ($page >= 1)) {
            $start_index = ($page-1)*$resource_pre_request;
            $this->db->limit($resource_pre_request, $start_index);
        } else {
            $this->db->limit($resource_default_num, 0);
        }
    }

    public function _get_resources() {
        $query = $this->db->get('resource');

        $resources = $query->result_array();

        if (empty($resources)) {
            return [];
        }

        // 获取用户信息
        $this->load->model('User_model', 'userModel');
        $users = $this->userModel->getUserDataBrief(array_map(function ($value) {
            return $value['user_id'];
        }, $resources));

        foreach ($resources as &$value) {
            if (array_key_exists($value['user_id'], $users)) {
                $value['user'] = $users[$value['user_id']];
            } else {
                $value['user'] = null;
            }
        }

        return $resources;
    }

    /**
     * 获得所有资源
     */
    public function getAllResources() {
        $this->_do_select();

        return $this->_get_resources();
    }

    public function getResourceById($r_id) {
        $this->db->select('resource.id as id, user_id, category_id, category.name as category, image, title, description, resource_type, url, save, look, download, publish_date, last_look_date');
        $this->db->join('category', 'resource.category_id = category.id');
        $this->db->where('resource.id', $r_id);
        $query = $this->db->get('resource');

        $resource = $query->row_array();

        if (empty($resource)) {
            return [];
        }

        // 获取用户信息
        $this->load->model('User_model', 'userModel');
        $user = $this->userModel->getUserDataBrief([$resource['user_id']]);

        if (!empty($user)) {
            $resource['user'] = $user;
        } else {
            $resource['user'] = null;
        }

        return $resource;
    }

    /**
     * 根据category_id进行获取类别下所有资源
     *
     * @param $cat_id int
     * @return mixed
     */
    public function getResourcesByCategoryId($cat_id) {
        $this->_do_select();

        // category_id
        $this->db->where('category_id', $cat_id);

        return $this->_get_resources();
    }

}