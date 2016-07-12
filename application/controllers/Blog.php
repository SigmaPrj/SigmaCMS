<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/12
 * Time: 下午1:15
 */
class Blog extends CI_Controller
{
    public function __construct() {
        parent::__construct();

        $this->load->library('parser');
    }

    public function index($name='non', $age=0) {
        $template = '<h1>Hello World!</h1> <br/> <p>{name} is {age} years old!</p>';

        $data['name'] = $name;
        $data['age'] = $age;

        $this->parser->parse_string($template, $data);
    }
}