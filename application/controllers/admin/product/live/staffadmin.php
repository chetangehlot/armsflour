<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StaffAdmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
    }

    public function index() {

        $order_by = array('column' => 't1.created_date', 'orderby' => 'DESC');
        $joins = 't1.service_details_id=t2.id';
        $data['stafflist'] = $this->common->getTwoJoins('t1.*,t2.title as cafename', 'staff as t1', 'service_details as t2', $joins, '', '', $order_by);
        $data['action_url'] = base_url('admin');
        $this->maintheme('list', $data);
    }

    public function add($ids1 = null) {
        $data['action_url'] = base_url('admin');
        $return_url = $_SERVER['HTTP_REFERER'];
        $data['servicelist'] = '';
        $data['page_title'] = 'Add';
        $ids = '';
        $staffid = '';
        $filename = '';
        $condition = array('is_active' => '1');
        $data['services'] = $this->common->getRows('service_details', 'id,title', $condition);
        $data['city'] = $this->common->getRows('cities', 'city_id,city_name');
        $data['return_url'] = $return_url;


        if (!empty($ids1)) {
            $data['page_title'] = 'Edit';
            $ids = base64_decode($ids1);
            $cond = array('id' => $ids);
            $data['staff'] = $this->common->getRow('staff', '*', $cond);
        }
        if ($this->input->post()) {

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('service_id', 'Service Details', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');


            if (empty($ids)) {
                $this->form_validation->set_rules('password', 'Password', 'required');
                $this->form_validation->set_rules('contact_no', 'Contact Number', 'required|numeric|is_unique[staff.contact_no]');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[staff.email_id]');
            } else {
                $this->form_validation->set_rules('contact_no', 'Contact Number', 'required|numeric');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            }

            $staffid = $this->input->post('staffid') ? base64_decode($this->input->post('staffid')) : '';

            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $datetme = date("Y-m-d H:i:s");
                $name = $this->input->post('name');
                $serviceids = $this->input->post('service_id');
                $contact_no = $this->input->post('contact_no');
                $amount_by_kitchen = $this->input->post('amount_by_kitchen');
                $address = $this->input->post('address');
                $city = $this->input->post('city');
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $return_url1 = $this->input->post('return_url');

                if (!empty($staffid)) {

                    if (!empty($password)) {
                        $dataarray = array(
                            'name' => $name,
                            'service_details_id' => $serviceids,
                            'contact_no' => $contact_no,
                            'amount_by_kitchen' => $amount_by_kitchen,
                            'address' => $address,
                            'password' => md5($password),
                            'city_id' => $city,
                            'modified_date' => $datetme,
                            'is_active' => $this->input->post('status')
                        );
                    } else {
                        $dataarray = array(
                            'name' => $name,
                            'service_details_id' => $serviceids,
                            'contact_no' => $contact_no,
                            'amount_by_kitchen' => $amount_by_kitchen,
                            'address' => $address,
                            'city_id' => $city,
                            'modified_date' => $datetme,
                            'is_active' => $this->input->post('status')
                        );
                    }
                    $data['page_title'] = 'Edit';
                    $where = array('id' => $staffid);

                    $lastid = $this->common->update('staff', $dataarray, $where);

                    if ($lastid) {

                        // insert log for all activity admin
                        $lourl = base_url() . 'admin/staff/add/' . $staffid;
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $logdata = array(
                            'url' => $lourl,
                            'subject' => 'Edit staff',
                            'type' => 'admin',
                            'ip' => $ip,
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'userid' => $this->session->userdata('admin_id'),
                            'username' => $this->session->userdata('username'),
                            'created_date' => $datetme
                        );
                        $this->common->insert('user_log', $logdata);

                        $this->session->set_flashdata('success', ' Recored has been ' . $data['page_title'] . '!');

                        // addmin can add wallet amount to staff

                        if ($amount_by_kitchen > 0) {
                            // insert log for all activity admin
                            $logdata = array(
                                'credit' => $amount_by_kitchen,
                                'staff_id' => $staffid,
                                'amount_assignby' => $this->session->userdata('admin_id'),
                                'created_date' => $datetme
                            );
                            $this->common->insert('delivery_staff_wallet_history', $logdata);
                        }
                    } else {
                        $this->session->set_flashdata('success', 'No record update');
                    }
                    if ($return_url1 != '') {
                        redirect($return_url1);
                    } else {
                        redirect('admin/staff/add/' . base64_encode($staffid));
                    }
                } else {
                    $data['page_title'] = 'Add';
                    $dataarray = array(
                        'name' => $name,
                        'service_details_id' => $serviceids,
                        'contact_no' => $contact_no,
                        'amount_by_kitchen' => $amount_by_kitchen,
                        'address' => $address,
                        'email_id' => $email,
                        'password' => md5($password),
                        'city_id' => $city,
                        'created_date' => $datetme,
                    );
                    $lastid = $this->common->insert('staff', $dataarray);

                    if ($lastid) {
                        // insert log for all activity admin
                        $lourl = base_url() . 'admin/staff/add';
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $logdata = array(
                            'url' => $lourl,
                            'subject' => 'Create staff',
                            'type' => 'admin',
                            'ip' => $ip,
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'userid' => $this->session->userdata('admin_id'),
                            'username' => $this->session->userdata('username'),
                            'created_date' => $datetme
                        );
                        $this->common->insert('user_log', $logdata);

                        $msg = 'Hi ' . $name . ',%0a Your delivery staff panel has been created. Your login credential is %0a User name:  ' . $contact_no . ' And Password is: ' . $password . ' %0a You can check panel on this link ' . base_url('staff');
                        $send = send_otp($contact_no, $msg);
                        // $sendemail = sendemail($dataarray,' Staff information','You can find the Staff inforation with order dashboard.');
                        $this->session->set_flashdata('success', ' Staff has been ' . $data['page_title'] . '!');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                    }

                    if ($return_url1 != '') {
                        redirect($return_url1);
                    } else {
                        redirect('admin/staff');
                    }
                }
            }
        }

        $this->maintheme('add', $data);
    }

    public function del($id = NULL) {
        $id = base64_decode($id);
        if ($id != '' && $id != NULL) {
            $value = $this->common->del('staff', $id);

            if ($value) {
                // insert log for all activity admin
                $lourl = base_url() . 'admin/staff/del/' . $id;
                $ip = $_SERVER['REMOTE_ADDR'];
                $logdata = array(
                    'url' => $lourl,
                    'subject' => 'Delet staff',
                    'type' => 'admin',
                    'ip' => $ip,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'userid' => $this->session->userdata('admin_id'),
                    'username' => $this->session->userdata('username'),
                    'created_date' => date("Y-m-d H:i:s")
                );
                $this->common->insert('user_log', $logdata);

                $this->session->set_flashdata('success', ' Staff has been deleted!');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong!');
            }
        }
        redirect('admin/staff');
    }

    // this function is use for the get theme function

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/staff_admin/' . $page, $data);
        }
        $this->load->view('admin/include/footer');
    }

    public function get_location() {
        if ($this->input->post('cityid')) {
            $condtion = array('city_id' => $this->input->post('cityid'), 'is_active' => 1);
            $orderby = array('column' => 'name', 'orderby' => 'ASC');
            $data = $this->common->getRows('locations', 'id,name', $condtion, $orderby);
            $html = '';
            if (!empty($data)) {

                foreach ($data as $location) {
                    $html .= '<option value="' . $location['id'] . '">' . ucfirst($location['name']) . '</option>';
                }
            } else {
                $html = '';
            }
            echo $html;
        } else {
            echo FALSE;
        }
    }

}

?>