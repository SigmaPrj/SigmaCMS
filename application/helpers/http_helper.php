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

if (!function_exists('getQNFileUrl')) {
    function getQNFileUrl($hash) {
        // 载入配置信息
        $ci = &get_instance();
        $ci->config->load('config');

        return $ci->config->item('qiniu_domain').$hash;
    }
}

if (!function_exists('generateQNToken')) {
    /**
     * @param $cbPath string 回调路径
     * @param $params array 自定义参数数组
     * @return string token 上传token
     */
    function generateQNToken($cbPath, $params) {
        // 载入配置信息
        $ci = &get_instance();
        $ci->config->load('config');

        $access_key = $ci->config->item('qiniu_access');
        $secret_key = $ci->config->item('qiniu_secret');
        $bucket_name = $ci->config->item('qiniu_bucket');

        $auth = new Qiniu\Auth($access_key, $secret_key);

        // 构造callback 地址
        $callbackUrl = $ci->config->item('base_url').$cbPath;
        // 构造callbackBody
        if (empty($params)) {
            $callbackBody = 'fname=$(fname)&fkey=$(fkey)&fsize=$(fsize)&hash=$(etag)';
        } else {
            $tmpArray = array_map(function($val){
                return $val.'=$(x:'.$val.')';
            }, $params);
            $callbackBody = 'fname=$(fname)&fkey=$(fkey)&fsize=$(fsize)&hash=$(etag)&'.implode('&', $tmpArray);
        }


        $policy =  array(
            'callbackUrl' => $callbackUrl,
            'callbackBody' => $callbackBody
        );

        // 生成token
        return $auth->uploadToken($bucket_name, null, 3600, $policy);
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

        if ($err) {
            return FALSE;
        } else {
            return $ret;
        }
    }
}

if (!function_exists('getAllHeaders')) {
    function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $key = strtolower(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))));
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('getHeader')) {
    function getHeader($key) {
        $headers = getAllHeaders();
        $lowerKey = strtolower($key);
        if (array_key_exists($lowerKey, $headers)) {
            return $headers[$lowerKey];
        }
        return false;
    }
}