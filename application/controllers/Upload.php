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

    public function upload_file() {
        $fname = ROOTPATH.'tmp/'.$this->input->get('fname');

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
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }
}