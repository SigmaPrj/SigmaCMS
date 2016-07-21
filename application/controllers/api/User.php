<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/21
 * Time: 上午8:23
 */
require __DIR__.'/API_Middleware.php';
/**
 * Class User
 */
class User extends API_Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function users_get() {

    }
}