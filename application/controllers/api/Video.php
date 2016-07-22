<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/22
 * Time: 下午1:27
 */
require_once __DIR__.'/API_Middleware.php';

/**
 * Class Video
 */
class Video extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 业务 : 1 获取热门视频 没给定id时
     *       2 获取特定视频 给定id,没给type时
     *       3 获得特定id下的评论内容 给定id, type=comment
     */
    public function videos_get() {
        $id = $this->get('id');
        $type = $this->get('type');

        if (!isset($id)) {
            // 获取所有video
            $this->load->model('Video_model', 'videoModel');
            $videos = $this->videoModel->getAllVideos();

            if (empty($videos)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any videos!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($videos, REST_Controller::HTTP_OK);
            }
        }

        if (!isset($type)) {
            // 获取特定id的值
            $this->load->model('Video_model', 'videoModel');
            $video = $this->videoModel->getVideoById($id);

            if (empty($video)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find the video!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($video, REST_Controller::HTTP_OK);
            }
        }

        if ($type === 'comment') {
            // 获取该视频下的所有评论信息
            $this->load->model('VideoComment_model', 'vcModel');
            $comments = $this->vcModel->getAllCommentsByVideoId($id);

            if (empty($comments)) {
                $this->response([
                    'status' => false,
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'error' => 'Can\'t find any comments!'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response($comments, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Invalid API'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}