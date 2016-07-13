<?php
/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午4:30
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

if (!function_exists('IS_GET')) {
    function IS_GET() {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }
}

if (!function_exists('IS_POST')) {
    function IS_POST() {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }
}

if (!function_exists('IS_DELETE')) {
    function IS_DELETE() {
        return ($_SERVER['REQUEST_METHOD'] === 'DELETE');
    }
}

if (!function_exists('IS_AJAX')) {
    function IS_AJAX() {
        return ($_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest");
    }
}