<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午4:53
 */
require_once __DIR__.'/API_Middleware.php';
/**
 * Class Dynamic
 */
class Dynamic extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 1 根据 用户id 获取用户所有动态 /user/user_id/dynamic
     *       2 根据 话题id 获取话题下所有动态 /topic/topic_id/dynamic
     *       3 根据 用户id 获取该用户所有好友的动态 /dynamic?user_id=user_id
     */
    public function dynamics_get() {

    }

    /**
     * 业务 : 1 根据 动态的id 获取该动态下的评论 /dynamic/dynamic_id/comment
     */
    public function comments_get() {

    }
}