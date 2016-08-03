<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午12:29
 */

/**
 * Class Index 后台主页
 */

require_once CONTROLPATH.'/SI_BaseController.php';

class Index extends SI_BaseController
{

    public function __construct() {
        parent::__construct();
        $this->load->helper('view');
        $this->config->load('config');
    }

    /**
     * 后台主页
     */
    public function index() {
        $data['title'] = '数魔法后台首页';
        $data['text'] = '具体内容';
        loadView('Index/index', $data);
    }

    public function test() {
        $this->config->load('config');
        echo md5($this->config->item('si_md5').'123456');
    }
}