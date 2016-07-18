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
        $this->load->model('safaker_model', 'fakerModel');
        $this->load->model('school_model', 'schoolModel');
        $this->faker = Faker\Factory::create('zh_CN');
    }

    /**
     * faker city数据
     */
    public function faker_city() {
        // 载入数据
        $filePath = ROOTPATH.'city.xml';
        $xml = simplexml_load_file($filePath);
        $index = 1;
        $data = [];
//        print_r($xml);
        foreach ($xml->root->row as $row) {
            $key = (string)$row->key;
            foreach ($row->array->string as $item) {
                $data[] = [
                    'code' => $index,
                    'name' => (string)$item,
                    'key'  => $key
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

    /**
     * faker school数据
     */
    public function faker_school(){
        // 载入数据
        $filePath = ROOTPATH.'school.xml';
        $xml = simplexml_load_file($filePath);
        $data = [];
        foreach ($xml->Root->Row as $row) {
            $code = (int)$row->Cell[0]->Data;
            $name = (string)$row->Cell[1]->Data;
            $city_code = (int)$row->Cell[3]->Data;
            if ($city_code !== 0) {
                $data[] = [
                    'code' => $code,
                    'name' => $name,
                    'city_code' => $city_code
                ];
            }
        }


        if ($this->fakerModel->addFakerSchool($data)) {
            echo 'School数据添加成功!';
        } else {
            echo 'School数据添加失败!';
        }
    }

    /**
     * faker user type数据
     */
    public function faker_userType() {
        $data = [
            [
                'code' => 1,
                'name' => '学生'
            ],
            [
                'code' => 2,
                'name' => '老师'
            ],
            [
                'code' => 3,
                'name' => '竞赛组委会'
            ],
            [
                'code' => 4,
                'name' => '高校活动方'
            ],
            [
                'code' => 5,
                'name' => '高校代理点'
            ]
        ];

        if ($this->fakerModel->addFakerUserType($data)) {
            echo 'UserType添加成功!';
        } else {
            echo 'UserType添加失败!';
        }
    }

    /**
     * faker user social数据
     */
    public function faker_userSocial() {

        $data = [];
        for ($i = 0; $i < 500; $i++) {
            $is_qq = $this->faker->randomElement([0, 1]);
            if ($is_qq) {
                $qq = $this->faker->regexify('[1-9][0-9]{8,11}');
            } else {
                $qq = '';
            }
            $is_weibo = $this->faker->randomElement([0, 1]);
            if ($is_weibo) {
                $weibo = $this->faker->regexify('[a-z]{2,6}[0-9]{4,6}');
            } else {
                $weibo = '';
            }
            $is_wechat = $this->faker->randomElement([0, 1]);
            if ($is_wechat) {
                $wechat = $this->faker->regexify('[a-z]{2,6}[0-9]{4,6}');
            } else {
                $wechat = '';
            }
            $data[] = [
                'id' => $i+1,
                'qq' => $qq,
                'is_qq' => $is_qq,
                'weibo' => $weibo,
                'is_weibo' => $is_weibo,
                'wechat' => $wechat,
                'is_wechat' => $is_wechat
            ];
        }

        if ($this->fakerModel->addFakerUserSocial($data)) {
            echo 'User Social数据添加成功!';
        } else {
            echo 'User Social数据添加失败!';
        }
    }

    /**
     * faker user 数据
     */
    public function faker_user() {
        $data = [];
        for ($i=0; $i < 500; $i++) {
            $username_type = $this->faker->randomElement(['email', 'phone', 'customer']);
            switch ($username_type) {
                case 'email': {
                    $username = $this->faker->safeEmail;
                    $password = md5($username);
                    $email = $username;
                    $phone = '';
                }
                    break;
                case 'phone' : {
                    $username = $this->faker->phoneNumber;
                    $password = md5($username);
                    $phone = $username;
                    $email = '';
                }
                    break;
                case 'customer' : {
                    $username = $this->faker->regexify('[a-zA-Z0-9]{6,10}');
                    $password = md5($username);
                    $email = '';
                    $phone = '';
                }
                    break;
            }
            $nickname = $this->faker->text(15);
            $image = $this->faker->imageUrl(120, 120);
            $signature = $this->faker->text(60);
            $point = $this->faker->randomNumber(4);
            $coin = $this->faker->randomNumber(4);
            $user_level = $this->faker->randomNumber(2);
            $school_code = $this->faker->numberBetween(1, 2553);
            while (!$this->schoolModel->getSchoolWithCode($school_code)) {
                // 判断是否为有效的school_code
                $school_code = $this->faker->numberBetween(1, 2553);
            }
            $city_code = $this->faker->numberBetween(1, 216);
            $user_type = $this->faker->numberBetween(1, 2);
            $user_social = ($i + 1);
            $last_login_city = $this->faker->numberBetween(1, 216);
            $last_login_date = $this->faker->unixTime('now');
            $last_register_date = $this->faker->unixTime($last_login_date);
            $is_active = 1;
            $active_date = $this->faker->unixTime($last_register_date);
            $apply_date = 1800;
            $apply_code = $this->faker->regexify('[0-9A-Z]{6}');
            $data[] = [
                'id' => $i + 1,
                'username' => $username,
                'password' => $password,
                'nickname' => $nickname,
                'username_type' => $username_type,
                'email' => $email,
                'phone' => $phone,
                'image' => $image,
                'signature' => $signature,
                'point' => $point,
                'coin' => $coin,
                'user_level' => $user_level,
                'school_code' => $school_code,
                'city_code' => $city_code,
                'user_type' => $user_type,
                'user_social' => $user_social,
                'last_login_city' => $last_login_city,
                'last_login_date' => $last_login_date,
                'last_register_date' => $last_register_date,
                'is_active' => $is_active,
                'active_date' => $active_date,
                'apply_date' => $apply_date,
                'apply_code' => $apply_code
            ];
        }

        if ($this->fakerModel->addFakerUser($data)) {
            echo 'User 数据添加成功!';
        } else {
            echo 'User 数据添加失败!';
        }
    }
}