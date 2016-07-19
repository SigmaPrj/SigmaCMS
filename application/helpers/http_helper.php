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

if (!function_exists('download_file_by_curl')) {
    function download_file_by_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        return curl_exec($ch);
    }
}

if (!function_exists('upload_file_to_qiniu')) {
    function upload_file_to_qiniu($output, $dbname, $field, $id) {

        $ci = &get_instance();
        $ci->config->load('config');

        $access_key = $ci->config->item('qiniu_access');
        $secret_key = $ci->config->item('qiniu_secret');
        $bucket_name = $ci->config->item('qiniu_bucket');

        $auth = new Qiniu\Auth($access_key, $secret_key);

        $policy = array(
            'callbackUrl' => $ci->config->item('base_url').'/Callback/'.$dbname.'/'.$field.'/'.$id,
            'callbackBody' => 'fname=$(fname)&fkey=$(fkey)&fsize=$(fsize)&hash=$(etag)'
        );

        $uploadToken = $auth->uploadToken($bucket_name, null, 3600, $policy);

        $uploadMgr = new Qiniu\Storage\UploadManager();

        list($ret, $err) = $uploadMgr->put($uploadToken, null, $output);

        return $ret;
    }
}