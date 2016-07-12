<?php
/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/12
 * Time: 上午10:09
 */
class News_model extends CI_Model {

    /**
     * News_model constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_news($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('news');
            return $query->result_array();
        }

        $slug = urldecode($slug);
        $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row_array();
    }

    public function set_news()
    {
        $this->load->helper('url');

        $slug = url_title($this->input->post('title'), 'dash', TRUE);

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'text' => $this->input->post('text')
        );

        return $this->db->insert('news', $data);
    }
}