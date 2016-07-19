<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/19
 * Time: 下午3:31
 */
class Upload extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config');
    }

    public function upload_file($file) {
        $fname = ROOTPATH.'tmp/'.$file;

        $access_key = $this->config->item('qiniu_access');
        $secret_key = $this->config->item('qiniu_secret');
        $bucket_name = $this->config->item('qiniu_bucket');

        $auth = new Qiniu\Auth($access_key, $secret_key);

        $policy = array(
            'callbackUrl' => $this->config->item('base_url').'/Callback',
            'callbackBody' => 'fname=$(fname)&fkey=$(fkey)&fsize=$(fsize)'
        );

        $uploadToken = $auth->uploadToken($bucket_name, null, 3600, $policy);

        $uploadMgr = new Qiniu\Storage\UploadManager();

        list($ret, $err) = $uploadMgr->putFile($uploadToken, null, $fname);

        if ($err !== null) {
            return 0;
        } else {
            return $ret;
        }
    }

    public function download_file() {
        $url = 'http://oaetkzt9k.bkt.clouddn.com/Fnq6z1szzxgopzJW3GeeZiL7goSM';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);
        
        file_put_contents(ROOTPATH.'tmp/tmp_'.time().'.png', $output);
    }
}