<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/19
 * Time: 上午10:07
 */
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('user', $new);
    }

    /**
     * @param $username string 根据用户名获取用户的数据
     */
    public function getUserByUsername($username) {
        $query = $this->db->where([
            'username' => $username
        ])->get('user');
        return $query->row_array();
    }

    /**
     * 只获取用户的权限信息
     *
     * @param $id int
     * @return mixed
     */
    public function getUserPrivilegeData($id) {
        $query = $this->db->select('user_privilege')->where([
            'id' => $id
        ])->get('user');
        $data = $query->row_array();
        $user_privilege_id = $data['user_privilege'];
        $uquery = $this->db->where([
            'id' => $user_privilege_id
        ])->get('user_privilege');

        return $uquery->row_array();
    }

    /**
     * @param $ids mixed
     * @return mixed
     */
    public function getUserDataBrief($ids) {
        $this->db->select('user.id as id, nickname, is_approved, image, user_level, school.name as school_name, user_type')
            ->from('user')
            ->join('school', 'user.school_code = school.code');

        if (empty($ids)) {
            return [];
        }

        if (is_array($ids)) {
            $query = $this->db->where_in('user.id', $ids)->get();
        } else {
            $query = $this->db->where([
                'user.id' => $ids
            ])->get();
        }

        $tmpData = $query->result_array();
        $data = [];

        foreach ($tmpData as $key => $value) {
            $data[$value['id']] = $value;
        }

        return $data;
    }

    /**
     * @param $ids mixed
     * @return mixed
     */
    public function getUserDataBasic($ids) {
        $this->db->select('user.id as id, nickname, is_approved, image, bgImage, signature, user_level, school.name as school_name, user_type')
            ->from('user')
            ->join('school', 'user.school_code = school.code');

        if (empty($ids)) {
            return [];
        }

        if (is_array($ids)) {
            $query = $this->db->where_in('user.id', $ids)->get();
        } else {
            $query = $this->db->where([
                'user.id' => $ids
            ])->get();
        }

        $tmpData = $query->result_array();
        $data = [];

        foreach ($tmpData as $key => $value) {
            $data[$value['id']] = $value;
        }

        return $data;
    }

    /**
     * @param $ids mixed
     * @return mixed
     */
    public function getUserDataAll($ids) {
        // 得到用户的所有信息内容
        if (empty($ids)) {
            return [];
        }

        $query = $this->db->where([
            'id' => $ids
        ])->get('user');

        return $query->row_array();
    }

    /**
     * 添加用户
     *
     * @param $user
     * @return int
     */
    public function addUser($user) {
        $this->db->insert('user', $user);
        return $this->db->insert_id();
    }

    /**
     *  检查用户是否存在
     *
     * @param $username
     * @return bool
     */
    public function checkoutUsername($username) {
        $query = $this->db->where([
            'username' => $username
        ])->get('user');
        $user = $query->row_array();
        if (empty($user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设置密码
     * @param $password
     * @param $id
     */
    public function setPassword($password, $id) {
        $this->db->set('password', $password);
        $this->db->where('id', $id);
        return $this->db->update('user');
    }

    /**
     * 给用户设置更多信息数据
     * @param $infos
     * @param $id
     */
    public function setInfo($infos, $id) {
        $this->db->where('id', $id);
        return $this->db->update('user', $infos);
    }
}