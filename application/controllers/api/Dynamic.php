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
     *
     * 业务 : 1 根据 动态的id 获取该动态下的评论 /dynamic/dynamic_id/comment
     */
    public function dynamics_get() {
        $dynamic_id = $this->get('id');
        $type = $this->get('type');
        $state = $this->get('state'); // 表示 是人们评论 还是普通评论 默认是普通评论

        if (!isset($dynamic_id)) {
            $this->response([
                'status' => false,
                'code' => 1,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // 获得特定id的动态内容
        if (!isset($type)) {
            // 返回特定dynamic信息
            $this->load->model('Dynamic_model', 'dyModel');
            $data = $this->dyModel->getDynamicById($dynamic_id);

            if (empty($data)) {
              $this->response([
                  'status' => false,
                  'code' => 2,
                  'error' => 'Can\'t find any dynamic!'
              ], REST_Controller::HTTP_NOT_FOUND);
            } else {
              $this->response($data, REST_Controller::HTTP_OK);
            }
      }

        // 获取特定type内容
        switch ($type) {
            case 'comment':
            {
                // 获取当前动态的所有评论
                $this->load->model('DynamicComment_model', 'dcModel');
                if ($state === 'hot') {
                    $comments = $this->dcModel->getHotCommentsById($dynamic_id);
                } else {
                    $comments = $this->dcModel->getBasicCommentsById($dynamic_id);
                }

                if (empty($comments)) {
                    $this->response([
                        'status' => false,
                        'code' => 3,
                        'error' => 'Can\'t find any comment!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $this->response($comments, REST_Controller::HTTP_OK);
                }
            }
                break;
            default:
            {
                $this->response([
                    'status' => false,
                    'code' => 1,
                    'error' => 'Invalid API'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
                break;
        }
    }
}
