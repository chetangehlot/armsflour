<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/DashboardModel');
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
        $this->load->model('admin/DashboardModel');

    }

    public function index() {
        $date = date('Y-m-d');
        $data['totalproduct'] = $this->DashboardModel->countValue('product', 'pid');
        $data['action_url'] = base_url('admin');
        $this->maintheme('dashboard', $data);
    }

    public function profile() {

        $this->load->helper('url');
        $username = $this->session->username;
        $this->load->model('admin/DashboardModel');
        $user_data['user'] = $this->common->getRow('adminlogin', '*', array('username' => $username));
        $this->maintheme('profile', $user_data);
        //$this->load->view('admin/profile', $user_data);
    }

    public function setting() {

        $this->load->helper('url');
        $this->maintheme('setting');
    }

    //update admin here
    public function update_admin() {

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('mobile', 'Mobile No.', 'required|regex_match[/^[0-9]{10}$/]');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            //Setting values for tabel columns
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $id = $this->input->post('id');
            $data = array(
                'name' => $name,
                'telephone' => $mobile,
            );
            $update = $this->common->update('adminlogin', $data, array('id' => $id));
            if ($update) {
                $this->session->set_flashdata('success', ' Updated');
                redirect('admin/dashboard/profile');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong');
                redirect('admin/dashboard/profile');
            }
        }

        redirect('admin/dashboard/profile');
    }

    //udapte password here
    public function update_passwrod() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        //Validating Name Field
        $this->form_validation->set_rules('cpassword', 'cpassword', 'required|min_length[5]|max_length[15]');

        //Validating Name Field
        $this->form_validation->set_rules('npassword1', 'npassword1', 'required|min_length[5]|max_length[15]');

        //Validating Name Field
        $this->form_validation->set_rules('npassword2', 'npassword2', 'required|min_length[5]|max_length[15]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Password length must be more than 5 charater and less than 15 character');
            $this->load->view('admin/setting');
        } else {
            //Setting values for tabel columns
            $cpassword = $this->input->post('cpassword');
            $npassword1 = $this->input->post('npassword1');
            $npassword2 = $this->input->post('npassword2');
            $username = $this->session->username;
            $this->load->model('admin/DashboardModel');
            $user_data = $this->common->getRow('adminlogin', 'password', array('username' => $username));
            if ($user_data->password == md5($cpassword)) {
                if ($npassword1 == $npassword2) {
                    $update = $this->common->update('adminlogin', array('password' => md5($npassword1)), array('username' => $username));
                    $this->session->set_flashdata('success', ' Password updated');
                } else {
                    $this->session->set_flashdata('error', 'new entered password not matched');
                }
            } else {
                $this->session->set_flashdata('error', 'incorrect current password');
            }
        }

        redirect('admin/dashboard/setting');
    }

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/' . $page, $data);
        }
        $this->load->view('admin/include/footer', $data);
    }

    public function countOrder() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $res = getNewOrder();
            if ($res)
                echo $res;
            else
                echo 0;
        }
    }

}

?>
