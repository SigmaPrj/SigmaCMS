<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/8/4
 * Time: 下午2:02
 */
class Message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 所有消息
     */
    public function getAllMessages($u_id) {
        // 获取所有消息
        $messages = [];
        $fromQuery = $this->db->distinct('to')->where([
            'from' => $u_id,
            'team_id' => 0
        ])->order_by('date', 'DESC')->get('message');
        $fromData = $fromQuery->result_array();
        foreach ($fromData as $value) {
            $messages[] = $value;
        }

        $toQuery = $this->db->distinct('from')->where([
            'to' => $u_id,
            'team_id' => 0
        ])->order_by('date', 'DESC')->get('message');
        $toData = $toQuery->result_array();
        foreach ($toData as $value) {
            $messages[] = $value;
        }

        $messages[] = $toQuery->result_array();

        // TODO: 队伍信息

        return $messages;
    }
}