<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/19
 * Time: 下午3:49
 */
class Callback extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handler() {
        echo 1;
    }
}