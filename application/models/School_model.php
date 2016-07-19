<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/18
 * Time: 下午2:18
 */
class School_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllSchools($limit) {
        if ($limit) {
            $query = $this->db->limit($limit)->get('school');
        } else {
            $query = $this->db->get('school');
        }
        return $query->result_array();
    }

    /**
     * @param $code int city_code
     * @param $limit int 多少条数据
     * @return mixed
     */
    public function getSchoolsWithCityCode($code, $limit) {
        // SELECT `si_school`.`code` AS `code` , `city_code` , `si_school`.`name` AS `school_name` , `si_city`.`name` AS `city_name` , `key` FROM `si_school`
        //   LEFT JOIN `si_city` ON `si_city`.`code` = `si_school`.`city_code`
        //   LIMIT 10;
        $query = $this->db->select('school.code AS code , city_code , school.name AS school_name , city.name AS city_name , key')
            ->join('city', 'city.code = school.city_code')
            ->where('city.code =', $code)
            ->limit($limit)->get('school');
        return $query->result_array();
    }

    public function getSchoolWithCode($code) {
        $sql = 'SELECT `si_school`.`code` AS `code` , `city_code` , `si_school`.`name` AS `school_name` , `si_city`.`name` AS `city_name` FROM `si_school` LEFT JOIN `si_city` ON `si_city`.`code` = `si_school`.`city_code` WHERE `si_school`.`code` = ?';
        $query = $this->db->query($sql, [$code]);
        return $query->row_array();
    }
}