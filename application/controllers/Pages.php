<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/12
 * Time: 上午9:45
 */
class Pages extends CI_Controller
{
    /**
     * @param string $page
     */
    public function view($page = 'home'){
        if (!file_exists(APPPATH.'/views/pages/'.$page.'.php')) {
            show_404();
        }

        $data['title'] = ucfirst($page);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);

    }
}