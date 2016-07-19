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
}