<?php

/**
 * Created by PhpStorm.
 * User: blackcater
 * Date: 16/7/13
 * Time: 下午2:13
 */
class Login extends CI_Controller
{

    private $username = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');

        $this->load->model('admin_model', 'adminModel');
    }

    /**
     * 显示登录页面
     */
    public function index() {
        $data = [
            'title' => '数模 - 登录'
        ];
        $this->load->view('Login/index', $data);
    }

    /**
     * 处理登录请求
     */
    public function login() {

        $this->username = $_POST['username'];

        $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_username_check', [
            'required' => '用户名不能为空',
            'username_check' => '用户名不存在'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_password_check', [
            'required' => '密码不能为空',
            'password_check' => '密码不正确或用户名不正确'
        ]);

        if ($this->form_validation->run() === FALSE) {
            redirect(base_url('login/index.html'));
        } else {

            // 设置session
            $this->session->set_userdata('is_login', TRUE);
            $this->session->set_userdata('username', $this->username);

            redirect(base_url('/index.html'));
        }
    }

    /**
     * @param $username string 用户名验证
     * @return bool
     */
    public function username_check($username) {
        if ($this->adminModel->is_valid_username($username)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param $password string 密码验证
     * @return bool
     */
    public function password_check($password) {
        if ($this->adminModel->is_valid_password($this->username, $password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function auth() {
        if (IS_POST()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if ($username && $password) {
                // 进行用户验证
                $this->load->model('User_model', 'userModel');
                $userData = $this->userModel->getUserByUsername($username);
                if ($userData) {
                    if ($password === $userData['password']) {
                        // 载入配置
                        $this->config->load('config');
                        $faker = \Faker\Factory::create();
                        $auth_prefix = $faker->regexify('[0-9a-zA-Z]{4,6}');
                        // 生成token
                        $token = md5($auth_prefix.md5($username).md5($password));
                        // 写入token
                        $time = time()+$this->config->item('auth_timeout');
                        $user_id = (int)$userData['id'];
                        $tokenData = [
                            'token' => $token,
                            'user_id' => $user_id,
                            'dead_time' => $time
                        ];
                        $this->load->model('Token_model', 'tokenModel');
                        if ($this->tokenModel->addToken($tokenData)) {
                            echo json_encode([
                                'status' => true,
                                'code' => 200,
                                'data' => [
                                    'token' => $token,
                                    'user' => $userData,
                                    'time' => $time
                                ]
                            ]);
                        } else {
                            echo json_encode([
                                'status' => false,
                                'code' => 3,
                                'error' => 'Can\'t get token from host, wait for a minute!'
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'status' => false,
                            'code' => 2,
                            'error' => 'Username or Password is not right!'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => false,
                        'code' => '1',
                        'error' => 'Can\'t find user!'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'code'  => 5,
                    'error' => 'Your content-type should be application/x-www-form-urlencoded!'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'code' => 4,
                'error' => 'You should use post method!'
            ]);
        }
    }

    public function checkAuth() {
        $token = $this->input->post('token');
        $userId = $this->input->post('user_id');
        $this->load->model('Token_model', 'tokenModel');
        if ($this->tokenModel->is_owner($token, $userId) && $this->tokenModel->is_valid($token)) {
            echo json_encode([
                'status' => true,
                'code' => 200,
                'data' => [
                    'is_valid' => true
                ]
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'code' => 200,
                'data' => [
                    'is_valid' => false
                ]
            ]);
        }
    }

    public function register() {
        if (IS_POST()) {
            $user = [];
            $user['username_type'] = $this->input->post('username_type');
            $user['password'] = $this->input->post('password');
//            $password =

            if ($user['username_type'] === 'email') {
                $user['username'] = $this->input->post('email');
                $user['email'] = $this->input->post('email');
            } else if ($user['username_type'] === 'phone') {
                $user['username'] = $this->input->post('phone');
                $user['phone'] = $this->input->post('phone');
            } else {
                $user['username'] = $this->input->post('customer');
                $user['customer'] = $this->input->post('customer');
            }

            $this->load->model('User_model', 'userModel');
            if ($this->userModel->checkoutUsername($user['username'])) {
                $user['nickname'] = '';
                $this->load->model('UserSocial_model', 'usModel');
                $user['user_social'] = $this->usModel->createRow();
                $this->load->model('UserPrivilege_model', 'upModel');
                $user['user_privilege'] = $this->upModel->createRow();
                $user['is_active'] = 1;
                $user['active_date'] = time();

                if ($user_id = $this->userModel->addUser($user)) {
                    echo json_encode([
                        'status' => true,
                        'code' => 200,
                        'data' => [
                            'user_id' => $user_id
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'code' => 2,
                        'error' => '注册失败!'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'code' => 1,
                    'error' => '用户名已存在!'
                ]);
            }
        }
    }

    public function setpass() {
        if (IS_POST()) {
            $password = $this->input->post('password');
            $user_id = $this->input->post('user_id');
            $this->load->model('User_model', 'userModel');
            if ($this->userModel->setPassword($password, $user_id)) {
                echo json_encode([
                    'status' => true,
                    'code' => 200,
                    'data' => null
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'code' => 402,
                    'data' => null
                ]);
            }
        }
    }

    public function setinfo() {
        if (IS_POST()) {
            $user_id = $this->input->post('user_id');
            $nickname = $this->input->post('nickname');
            $city_code = $this->input->post('city_code');
            $school_code = $this->input->post('school_code');
            $this->load->model('User_model', 'userModel');
            if ($this->userModel->setInfo([
                'nickname' => $nickname,
                'city_code' => $city_code,
                'school_code' => $school_code
            ], $user_id)) {
                echo json_encode([
                    'status' => true,
                    'code' => 200,
                    'data' => null
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'code' => 402,
                    'error' => 'Can\'t set info to user!'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'code' => 401,
                'error' => 'Invalid API!'
            ]);
        }
    }

    public function ntunnel() {
        $username = 'kml3ool15w';
        $password = '0i3wmk25kk4z2k2xj545klh4zy02z551lxjl2w02';
        if ($_POST["login"] == $username && $_POST["password"] == $password) {
            $hs = SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT;
            $conn = mysql_connect($hs, SAE_MYSQL_USER, SAE_MYSQL_PASS);
            $errno_c = mysql_errno();
            if(($errno_c <= 0)) {
                $res = mysql_select_db(SAE_MYSQL_DB, $conn);
                $errno_c = mysql_errno();
            }
        } else {
            echo 'Authentication failed';
            exit();
        }
    }
}