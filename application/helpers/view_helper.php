<?php
/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午12:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sigma Inc. Array Helpers
 *
 * @package		Sigma Inc.
 * @subpackage	Helpers
 * @category	Helpers
 * @author		blackcater
 */

// ------------------------------------------------------------------------


if (!function_exists('loadView')) {
    /**
     *
     * 组合模板视图和主视图
     *
     * @param $name string 主视图名称
     * @param $data array 传入数据
     */
    function loadView($name, $data) {
        $CI = &get_instance();
        $CI->load->view('templates/header', $data);
        $CI->load->view('templates/nav', $data);
        $CI->load->view($name, $data);
        $CI->load->view('templates/footer', $data);
    }
}