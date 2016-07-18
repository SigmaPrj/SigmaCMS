<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/16
 * Time: 下午11:23
 */
class SAFaker extends CI_Controller
{
    protected $faker;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SAFaker_model', 'fakerModel');
        $this->faker = Faker\Factory::create('zh_CN');
    }

    public function faker_city() {
        // 载入数据
        $filePath = ROOTPATH.'city.xml';
        $xml = simplexml_load_file($filePath);
        $index = 1;
//        print_r($xml);
        foreach ($xml->root->row as $row) {
            foreach ($row->array->string as $item) {
                $data[] = [
                    'code' => $index,
                    'name' => (string)$item
                ];
                $index++;
            }
        }

        // 将数据添加到数据库
        if ($this->fakerModel->addFakerCity($data)) {
            echo 'City数据添加成功!';
        } else {
            echo 'City数据添加失败!';
        }
    }

    public function faker_user(){

    }
}