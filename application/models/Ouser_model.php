<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午1:54
 */
class Ouser_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function upd($new, $id) {
        return $this->db->where(['id' => $id])->update('ouser', $new);
    }


    /**
     * 根据 id 数组, 获取所有Ouser的简洁信息
     *
     * @param $ids array
     * @return mixed
     */
    public function getOuserDataBrief($ids) {
        if (empty($ids)) {
            return [];
        }

        $this->db->select('ouser.id as id, nickname, image, is_approved, city.name as city')
            ->from('ouser')
            ->join('city', 'ouser.city_code = city.code')
            ->where('is_active', 1);

        $this->db->where_in('id', $ids);

        $query = $this->db->get(null);

        $tmpData = $query->result_array();
        $ousers = [];

        foreach ($tmpData as $item) {
            $ousers[$item['id']] = $item;
        }

        return $ousers;
    }

}