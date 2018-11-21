<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct(); //  calls the constructor

        $this->get_authorize();
        $this->load->model('frontend/HomeModel');
        $this->load->model('common');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index($valu = null) {

        if ($this->session->userdata('cust_id')) {

            // Order list
            $userid = $this->session->userdata('cust_id');
            $ordercond = array('customer_id' => $userid);
            $data['orderlist'] = $this->common->getRows('orders', 'order_id,order_date,order_type,order_total,dicount_amount,wallet_amount,shipping_charges,status_id', $ordercond, array('column' => 'order_id', 'orderby' => 'DESC'));
            $condition = array('id' => $userid);
            $data['profile'] = $this->common->getRow('customers', 'id,name,email,mobile', $condition);

            // customer address list
//            $join = 't2.city_id = t1.city_id';
//            $condition12 = array('t1.customer_id' => $userid);
//            $data['address'] = $this->common->getTwoJoins('t1.address_id,t1.address_1,t1.address_2,t1.postcode,t2.city_name as city_name', 'addresses as t1', 'cities as t2', $join, '', $condition12, array('column' => 'address_id', 'orderby' => 'DESC'));

            $cond = array('t1.customer_id' => $userid);
            $joins = array(array('table1' => 'address_type as t2', 'join1' => 't1.address_type=t2.id'), array('table1' => 'cities as t3', 'join1' => 't1.city_id=t3.city_id'));
            $data['address'] = $this->common->getMoreJoin('t1.address_id,t1.address_1,t1.address_2,t1.city_id,t1.postcode,t2.name,t3.city_name as city_name', 'addresses as t1', $joins, $cond);


            // wallet list with history
            $data['wallet'] = $this->common->getRow('wallet', 'amount', array('customer_id' => $userid));
            $join = 't2.id = t1.wallet_id';
            $condition12 = array('t2.customer_id' => $userid);
            $orderby = array('column' => 't1.created_date', 'orderby' => 'DESC');
            $data['wallet_history'] = $this->common->getTwoJoins('t1.order_id,t1.id as wallet_historyid,t1.deposit,t1.withdrawal,t1.comments,t1.created_date', 'wallet_history as t1', 'wallet as t2', $join, '', $condition12, $orderby);

            // loyalty with history
            $data['loyalty'] = $this->common->getRow('customer_loyalty', 'loyalty_point', array('customer_id' => $userid));
            $data['loyalty_conversion'] = $this->common->getRow('loyalty', 'point_rate');
            $join = 't2.id = t1.customer_loyalty_id';
            $orderby = array('column' => 't1.created_date', 'orderby' => 'DESC');
            $data['loyalty_history'] = $this->common->getTwoJoins('t1.order_id,t1.id,t1.deposit,t1.withdrawal,t1.comments,t1.created_date', 'customer_loyalty_history as t1', 'customer_loyalty as t2', $join, '', array('t2.customer_id' => $userid), $orderby);


            $data['typeoftab'] = $valu;
            $this->maintheme('profile', $data);
        }
    }

    public function addressBook($ids = null) {
        $userid = $this->session->userdata('cust_id');
        $data['city'] = $this->common->getRows('cities', 'city_id,city_name', array('city_state_id' => 46));
        $data['page_title'] = 'Add';
        if (!empty($ids)) {
            $data['page_title'] = 'Edit';
            $ids12 = base64_decode($ids);
            $cond = array('address_id' => $ids12, 'customer_id' => $userid);
            $data['address'] = $this->common->getRow('addresses', 'address_id,address_1,address_2,city_id,postcode,address_type', $cond);
            $data['address_type'] = $this->common->getRows('address_type', 'id,name', array('is_active' => 1));
            //$cond = array('t1.address_id' => $ids12, 't1.customer_id' => $userid);
            // $data['address'] = $this->common->getTwoJoins('t1.address_id,t1.address_1,t1.address_2,t1.city_id,t1.postcode,t2.name', 'addresses as t1', 'address_type as t2', 't1.address_type=t2.id', '', $cond);
        }

        if ($this->input->post()) {

            $this->form_validation->set_rules('address_1', 'Address Line 1', 'required');
            $this->form_validation->set_rules('address_2', 'Address Line 2', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $add1 = $this->input->post('address_1');
                $add2 = $this->input->post('address_2');
                $city = $this->input->post('city');
                $postcode = $this->input->post('postcode');
                $addressid = base64_decode($this->input->post('addressid'));
                $createddate = date('Y-m-d H:i:s');
                if ($addressid) {
                    $ararray = array(
                        'address_1' => $add1,
                        'address_2' => $add2,
                        'postcode' => $postcode,
                        'city_id' => $city,
                        'modify_date' => $createddate
                    );
                    $condiion12 = array('customer_id' => $userid, 'address_id' => $ids12);
                    $resposne = $this->common->update('addresses', $ararray, $condiion12);
                    if ($resposne) {
                        // user_log 
                        // Address add
                        $lourl = base_url() . 'frontend/profile/address';
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $logdata = array(
                            'url' => $lourl,
                            'subject' => 'New Address',
                            'method' => 'New Address by the user',
                            'ip' => $ip,
                            'type' => 'web',
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'userid' => $userid,
                            'username' => $this->session->userdata('cust_name'),
                            'created_date' => $createddate
                        );
                        $this->common->insert('customer_log', $logdata);
                        // end user activity for the front end

                        $this->session->set_flashdata('success', 'Address has been ' . $data['page_title'] . '!');
                        redirect('profile/address');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                        redirect('addressBook/' . base64_encode($addressid));
                    }
                } else {
                    $data['page_title'] = 'Add';
                    $ararray1 = array(
                        'customer_id' => $userid,
                        'address_1' => $add1,
                        'address_2' => $add2,
                        'postcode' => $postcode,
                        'city_id' => $city,
                        'created_date' => $createddate
                    );
                    $lastid = $this->common->insert('addresses', $ararray1);
                    if ($lastid) {
                        $this->session->set_flashdata('success', 'Address has been ' . $data['page_title'] . '!');
                        redirect('profile/address/');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                    }
                }
            }
        }
        $this->maintheme('address', $data);
    }

    public function orderdetails($ids = null) {

        $userid = $this->session->userdata('cust_id');
        $orderid = base64_decode($ids);
        if (!empty($orderid)) {
            $data['orderdetails'] = $this->common->getRow('orders', '*', array('order_id' => $orderid, 'customer_id' => $userid));

            $data['order_product'] = $this->common->getRows('order_products', '*', array('order_id' => $orderid));
        }
        $this->maintheme('orderdetails', $data);
    }

    public function profileEdit() {

        $userid = $this->session->userdata('cust_id');
        $cond = array('id' => $userid);
        $data['customer'] = $this->common->getRow('customers', 'name,email,mobile', $cond);

        if ($this->input->post()) {

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $name = $this->input->post('name');
                $email = $this->input->post('email');

                if ($userid) {
                    $ararray1 = array(
                        'name' => $name,
                        'email' => $email,
                        'modify_date' => date('Y-m-d H:i:s')
                    );
                    $cond = array('id' => $userid);
                    $resposne = $this->common->update('customers', $ararray1, $cond);
                    if ($resposne) {
                        $this->session->set_flashdata('success', 'Profile has been updated!');
                        redirect('profile');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                        redirect('profileEdit/');
                    }
                }
            }
        }
        $this->maintheme('profileedit', $data);
    }

    public function del($id = NULL) {

        $id = base64_decode($id);
        $userid = $this->session->userdata('cust_id');

        if ($id != '' && $id != NULL) {
            $cond = array('customer_id' => $userid, 'address_id' => $id);
            $value = $this->common->deladdress('addresses', $cond);
            if ($value) {
                $this->session->set_flashdata('success', ' Address has been deleted!');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong!');
            }
        }
        redirect('profile/address');
    }

// this function is use for the get theme function

    public function maintheme($page = null, $data = null) {

        $this->load->view('frontend/include/header', $data);
        if (!empty($page)) {
            $this->load->view('frontend/' . $page, $data);
        } else {
            $this->load->view('frontend/profile', $data);
        }
        $this->load->view('frontend/include/footer', $data);
    }

    public function get_authorize() {

        if ($this->session->userdata('is_front_login')) {
            return true;
        } else {
            return redirect('login');
        }
    }

}

?>