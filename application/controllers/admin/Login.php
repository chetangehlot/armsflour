<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->session->userdata('is_logged_in')) {
            redirect('admin/dashboard');
        }
        $this->load->view('admin/login');
        $this->load->model('admin/LoginModel');
    }

    public function admin_login() {

        if ($this->session->userdata('is_logged_in')) {
            redirect('admin/dashboard');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');

        //start validation process
        if ($this->form_validation->run() == true) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            //echo 65654;

            $loadd = $this->load->model('admin/LoginModel');
            $valid = $this->LoginModel->login_valid($username, $password);

            if ($valid) {
                $login = array(
                    'is_logged_in' => true,
                    'username' => $valid->username,
                    'admin_id' => $valid->id
                );

                $this->session->set_userdata($login);
                redirect('admin/dashboard');
                //$this->load->view('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username or password incorrect');
                redirect('admin/login');
            }
        } else {
            $this->load->helper('url');
            $this->load->view('admin/login');
        }
    }

    public function logout() {
        $this->session->unset_userdata('is_logged_in');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('admin_id');
        redirect('admin/login');
    }

}

?>