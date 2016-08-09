<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/20
 * Time: 下午2:55
 */
class DynamicImage_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function addImageCallback($data) {
        return $this->db->insert('dynamic_image', $data);
    }

    /**
     * @param $ids array 所有动态的数组
     */
    public function getDynamicImages($ids) {
        $this->db->select('url, dynamic_id');

        if (empty($ids)) {
          return [];
        }

        if (is_array($ids)) {
            $this->db->where_in('dynamic_id', $ids);
        } else {
            $this->db->where('dynamic', $ids);
        }

        $query = $this->db->get('dynamic_image');
        $tmpImages = $query->result_array();
        $images = [];

        for ($i =0; $i < count($tmpImages); $i++) {
            $tmpImage = $tmpImages[$i];
            $images[$tmpImage['dynamic_id']][] = $tmpImage['url'];
        }

        return $images;
    }

    /**
     * @param $images array
     * @param $dynamic_id int
     */
    public function addImages($images, $dynamic_id) {
        $data = [];
        foreach ($images as $val) {
            $data[] = [
                'dynamic_id' => $dynamic_id,
                'url' => $val
            ];
        }

        $this->db->insert_batch('dynamic_image', $data);
    }
}
