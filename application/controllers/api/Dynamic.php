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
                'code' => REST_Controller::HTTP_BAD_REQUEST,
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
                  'code' => REST_Controller::HTTP_NOT_FOUND,
                  'error' => 'Can\'t find any dynamic!'
              ], REST_Controller::HTTP_NOT_FOUND);
            } else {
              $this->response([
                  'status' => true,
                  'code' => REST_Controller::HTTP_OK,
                  'data' => $data
              ], REST_Controller::HTTP_OK);
            }
      }

        // 获取特定type内容
        switch ($type) {
            case 'comment':
            {
                // 获取当前动态的所有评论
                $this->load->model('DynamicComment_model', 'dcModel');
                $comments = $this->dcModel->getCommentsByDynamicId($dynamic_id);

                if (empty($comments)) {
                    $this->response([
                        'status' => false,
                        'code' => REST_Controller::HTTP_NOT_FOUND,
                        'error' => 'Can\'t find any comment!'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $this->response([
                        'status' => true,
                        'code' => REST_Controller::HTTP_OK,
                        'data' => $comments
                    ], REST_Controller::HTTP_OK);
                }
            }
                break;
            default:
            {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_REQUEST,
                    'error' => 'Invalid API'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
                break;
        }
    }

    /**
     * 业务 : 1 没有id, 上传动态
     * 业务 : 2 有id ,有type 为comment ,上传评论
     */
    public function dynamics_post() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 发布动态
            $content = $this->post('content');
            $user_id = $this->post('user_id');
            $now = time();
            $dynamic = [
                'user_id' => $user_id,
                'has_topic' => 0,
                'topic_id' => 0,
                'content' => $content,
                'publish_date' => $now,
                'last_look_date' => $now,
                'share' => 0,
                'look' => 0,
                'praise' => 0
            ];
            $this->load->model('Dynamic_model', 'dyModel');
            if ($insertId = $this->dyModel->addDynamic($dynamic)) {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => [
                        'id' => $insertId
                    ]
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_GATEWAY,
                    'error' => 'Some error occurred!'
                ], REST_Controller::HTTP_BAD_GATEWAY);
            }
        }

        if (!isset($type)) {
            // TODO : 修改动态
        }

        if ($type === 'comment') {
            // 发布评论
            $user_id = $this->post('user_id');
            $content = $this->post('comment');
            $publish_date = time();
            $last_look_date = time();
            $praise = 0;
            $sub_id = $this->post('sub_id') ?$this->post('sub_id'):0;
            $comment = [
                'dynamic_id' => $id,
                'user_id' => $user_id,
                'comment' => $content,
                'publish_date' => $publish_date,
                'last_look_date' => $last_look_date,
                'praise' => $praise,
                'sub_id' => $sub_id
            ];
            $this->load->model('DynamicComment_model', 'dmModel');
            if ($insertId = $this->dmModel->addComment($comment)) {
                $this->response([
                    'status' => true,
                    'code' => REST_Controller::HTTP_OK,
                    'data' => [
                        'id' =>$insertId
                    ]
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_BAD_GATEWAY,
                    'error' => 'Some error occurred!'
                ], REST_Controller::HTTP_BAD_GATEWAY);
            }
        } else if ($type === 'image') {
            // TODO : 添加图片
            // 涉及到七牛

        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
