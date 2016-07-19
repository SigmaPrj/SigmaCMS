<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/16
 * Time: 下午11:23
 */
class SAFaker_model extends CI_Model
{
    /**
     * Faker_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * @param $data array 向表si_city中添加数据
     * @return mixed
     */
    public function addFakerCity($data) {
        return $this->db->insert_batch('city', $data);
    }

    /**
     * @param $data array 向表si_school中添加数据
     * @return
     */
    public function addFakerSchool($data) {
        return $this->db->insert_batch('school', $data);
    }

    /**
     * @param $data array 向表si_user_type添加假数据
     * @return bool
     */
    public function addFakerUserType($data) {
        return $this->db->insert_batch('user_type', $data);
    }

    /**
     * @param $data array 向表si_user_social中添加数据
     */
    public function addFakerUserSocial($data){
        return $this->db->insert_batch('user_social', $data);
    }

    /**
     * @param $data array 向表 si_user 中添加数据
     * @return mixed
     */
    public function addFakerUser($data) {
        return $this->db->insert_batch('user', $data);
    }

    /**
     * @param $data array 向表 si_advertisement 中添加数据
     * @return mixed
     */
    public function addFakerAdvertisement($data) {
        return $this->db->insert_batch('advertisement', $data);
    }

    /**
     * @param $data array 向表 si_question 中提那家数据
     * @return mixed
     */
    public function addFakerQuestion($data) {
        return $this->db->insert_batch('question', $data);
    }

    /**
     * @param $data array 向表 si_category 中添加数据
     * @return mixed
     */
    public function addFakerCategory($data) {
        return $this->db->insert_batch('category', $data);
    }

    /**
     * @param $data array 向表 si_video 中添加数据
     * @return mixed
     */
    public function addFakerVideo($data) {
        return $this->db->insert_batch('video', $data);
    }

    /**
     * @param $data array 向表 si_videoComment中添加数据
     * @return mixed
     */
    public function addFakerVideoComment($data) {
        return $this->db->insert_batch('video_comment', $data);
    }

    /**
     * @param $data array 向表 si_resource 中添加数据
     */
    public function addFakerResource($data) {
        return $this->db->insert_batch('resource', $data);
    }

    /**
     * @param $data array 向表 si_resource_comment 中添加数据
     * @return mixed
     */
    public function addFakerResourceComment($data) {
        return $this->db->insert_batch('resource_comment', $data);
    }

    /**
     * @param $data array 向表 si_ouser 中添加数据
     * @return mixed
     */
    public function addFakerOuser($data) {
        return $this->db->insert_batch('ouser', $data);
    }

    /**
     * @param $data array 向表 si_activity 中添加数据
     * @return mixed
     */
    public function addFakerActivity($data) {
        return $this->db->insert_batch('activity', $data);
    }

    /**
     * @param $data array 向表 si_activity_comment 中添加数据
     */
    public function addFakerActivityComment($data) {
        return $this->db->insert_batch('activity_comment', $data);
    }

    /**
     * @param $data array 向表 si_experience 中添加数据
     * @return mixed
     */
    public function addFakerExperience($data) {
        return $this->db->insert_batch('experience', $data);
    }

    /**
     * @param $data array 向表 si_experience_comment 中添加数据
     * @return mixed
     */
    public function addFakerExperienceComment($data) {
        return $this->db->insert_batch('experience_comment', $data);
    }

    /**
     * @param $data array 向表 si_topic 中添加数据
     * @return mixed
     */
    public function addFakerTopic($data) {
        return $this->db->insert_batch('topic', $data);
    }

    /**
     * @param $data array 向表 si_dynamic 中添加数据
     * @return mixed
     */
    public function addFakerDynamic($data) {
        return $this->db->insert_batch('dynamic', $data);
    }

    /**
     * @param $data array 向表 si_dynamic_comment 中添加数据
     * @return mixed
     */
    public function addFakerDynamicComment($data) {
        return $this->db->insert_batch('dynamic_comment', $data);
    }

    /**
     * @param $data array 向表 si_news_type 中添加数据
     * @return mixed
     */
    public function addFakerNewsType($data) {
        return $this->db->insert_batch('news_type', $data);
    }

    /**
     * @param $data array 向表 si_news 中添加数据
     * @return mixed
     */
    public function addFakerNews($data) {
        return $this->db->insert_batch('news', $data);
    }

    /**
     * @param $data array 向表 si_news_comment 中添加数据
     * @return mixed
     */
    public function addFakerNewsComment($data) {
        return $this->db->insert_batch('news_comment', $data);
    }

    /**
     * @param $data array 向表 si_follow 中添加数据
     * @return mixed
     */
    public function addFakerFollow($data) {
        return $this->db->insert_batch('follow', $data);
    }

    /**
     * @param $data array 向表 si_friend 中添加数据
     * @return mixed
     */
    public function addFakerFriend($data) {
        return $this->db->insert_batch('friend', $data);
    }
}