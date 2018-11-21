<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct(); //  calls the constructor
        $this->load->model('frontend/HomeModel');
        $this->load->model('common');
        date_default_timezone_set('Asia/Kolkata');
    }

    // calling for the index function of the main page....

    public function index() {

        $stateid = $this->common->getRow('states', 'id', array('name' => 'MADHYA PRADESH'));
        $condtionark = array('is_active' => 1, 'apply_for' => 'ALL');

        if (!empty($coupondetails)) {
            $data['popup'] = $coupondetails;
        }
        $data['time_slots_for_preorder'] = $this->common->getRow('time_slots', 'time_slots_from,time_slots_to', array('is_active' => $this->session->userdata('cust_id')));
        if ($this->session->userdata('cust_id')) {
            $data['wallet'] = $this->common->getRow('wallet', 'amount', array('customer_id' => $this->session->userdata('cust_id')));
        }
        if (!empty($stateid)) {
            $statids = $stateid->id;
        } else {
            $statids = 46;
        }
        $order_by = array('column' => 'city_name', 'orderby' => 'ASC');
        $coditon = array('city_state_id' => $statids);
        $data['citylist'] = $this->common->getRows('cities', 'city_id,city_name', $coditon, $order_by);
        $data['kitchen'] = $this->common->getTwoJoins('t1.name,t1.address', 'kitchen as t1', 'cities as t2', 't1.city_id=t2.city_id', '', array('t2.city_name' => 'indore'));
        $data['address_cafe'] = $this->common->getRow('service_details', 'address');
        if ($this->input->post()) {
            if ($this->input->post('city-address') || $this->input->post('home_address')) {
                $location = '';
                if ($this->input->post('city-address')) {
                    if (!empty($this->input->post('stat_city')) && !empty($this->input->post('location'))) {
                        $condition = array('city_id' => $this->input->post('stat_city'));
                        $getcity = $this->common->getRow('cities', 'city_name', $condition);
                        $lat = $this->input->post('lat');
                        $long = $this->input->post('long');
                        if (!empty($getcity)) {

                            if ($this->input->post('address_new_id')) {
                                $getaddress = $this->common->getMoreJoin('t1.latitude,t1.longitude,t1.address_type,t1.location,t2.name', 'addresses as t1', array(array('table1' => 'locations as t2', 'join1' => 't1.location=t2.id')), array('t1.address_id' => $this->input->post('address_new_id')), '', '', '', '', 'single');
                                if ($getaddress) {
                                    $lat = $getaddress->latitude;
                                    $long = $getaddress->longitude;
                                    $lklocationid = $getaddress->location;
                                    $location = $this->input->post('location');
                                    $address_type = $getaddress->address_type;
                                    $this->session->set_userdata('address_type_selected', $address_type);
                                    $this->session->set_userdata('add_location_id', $lklocationid);
                                }
                                $this->session->set_userdata('customer_address_id', $this->input->post('address_new_id'));
                            } else {
                                $this->session->unset_userdata('customer_address_id');
                                $this->session->unset_userdata('address_type_selected');
                                $this->session->unset_userdata('add_location_id');
                                $location = $this->input->post('location');
                            }
                            $getcity_name = $getcity->city_name;

                            $pre_order_date = $this->input->post('preorder_date');
                            $pre_order_timeslots = $this->input->post('pre_ordertime');

                            if (!empty($pre_order_date) && !empty($pre_order_timeslots)) {
                                $this->session->set_userdata(array('pre_order_date' => $pre_order_date));
                                $this->session->set_userdata(array('pre_order_timeslots' => $pre_order_timeslots));
                            } else {
                                $this->session->unset_userdata(array('pre_order_timeslots', 'pre_order_date'));
                            }

                            $this->session->set_userdata(array('stat_city' => $getcity_name, 'location' => $location, 'latf' => $lat, 'longf' => $long));
                            $this->session->set_userdata(array('order_type' => 'home delivery'));
                            $unsetsessiondata = array('pickup_area', 'stat_city_pickup', 'kitchenid', 'kitchen_address');
                            $this->session->unset_userdata($unsetsessiondata);
                            redirect('main');
                        }
                    } else {
                        $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
                        $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
                        if ($this->form_validation->run() == FALSE) {
                            $this->maintheme('login');
                        }
                    }
                } else {
                    // stat_city_pickup,pickup_area,home_address
                    if (!empty($this->input->post('stat_city_pickup')) && !empty($this->input->post('pickup_area'))) {

                        //get kitchen address

                        $condition1 = array('name' => $this->input->post('pickup_area'));
                        $getkitchenaddress = $this->common->getRow('kitchen', 'id,address', $condition1);

                        $condition = array('city_id' => $this->input->post('stat_city_pickup'));
                        $getcity = $this->common->getRow('cities', 'city_name', $condition);
                        if ((!empty($getcity)) && (!empty($getkitchenaddress))) {
                            $getcity_name = $getcity->city_name;
                            $kitchenid = $getkitchenaddress->id;
                            $kitchenaddress = $getkitchenaddress->address;
                            $this->session->set_userdata(array('stat_city_pickup' => $getcity_name, 'pickup_area' => $this->input->post('pickup_area'), 'kitchen_address' => $kitchenaddress, 'kitchenid' => $kitchenid));
                            $this->session->set_userdata(array('order_type' => 'pickup'));

                            $unsetsessiondata1 = array('stat_city', 'stat_city', 'latf', 'longf', 'location', 'customer_address_id', 'address_type_selected', 'add_location_id');
                            $this->session->unset_userdata($unsetsessiondata1);
                            redirect('main');
                        } else {
                            $this->form_validation->set_rules('stat_city_pickup', 'Choose option the pickup or delivery ', 'required');
                            $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
                            if ($this->form_validation->run() == FALSE) {
                                $this->maintheme('login');
                            }
                        }
                    } else {
                        $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
                        $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
                        if ($this->form_validation->run() == FALSE) {
                            $this->maintheme('login');
                        }
                    }
                    $this->session->set_userdata(array('address' => $this->input->post('location')));
                    $this->session->set_userdata(array('order_type' => 'pickup'));

                    $this->session->unset_userdata(array('stat_city', 'location'));
                    redirect('main');
                }
            } else {
                $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
                if ($this->form_validation->run() == FALSE) {
                    $this->maintheme('login');
                }
            }
        }
        $this->maintheme('home1', $data);
    }

    public function home() {

        if ($this->session->userdata('kitchen_address') || $this->session->userdata('location')) {
            
        } else {
            redirect('');
        }

        // $this->get_authorize();
        $this->load->model('admin/SliderList');
        //$data['sliderlist'] = $this->SliderList->getlist();
        $data['category'] = $this->common->getRows('category', 'id,name,description,image', array('is_active', '1', 'is_special' => 0), array('column' => 'priority_no', 'orderby' => 'ASC'));
        $data['special_category'] = $this->common->getRows('category', 'id,name,description,image,banner_image', array('is_active', '1', 'is_special' => 1), array('column' => 'priority_no', 'orderby' => 'ASC'));

        $stateid = $this->common->getRow('states', 'id', array('name' => 'MADHYA PRADESH'));
        $condtionark = array('is_active' => 1, 'apply_for' => 'ALL');
        $coupondetails = $this->common->getRow('coupon', 'coupon_code,image,header_text,footer_text,body_text', $condtionark);
        $condslid = array('is_active' => 1);
        $couponlist = $this->common->getRows('coupon', 'coupon_code,image,header_text,footer_text,body_text,banner_image', $condslid);
        $data['couponlist'] = $couponlist;
        if (!empty($coupondetails)) {
            $data['popup'] = $coupondetails;
        }
        if ($this->session->userdata('cust_id')) {
            $data['wallet'] = $this->common->getRow('wallet', 'amount', array('customer_id' => $this->session->userdata('cust_id')));
        }
        if (!empty($stateid)) {
            $statids = $stateid->id;
        } else {
            $statids = 46;
        }
        /* if ($this->input->post()) {

          if ($this->input->post('city-address') || $this->input->post('home_address')) {
          $location = '';
          if ($this->input->post('city-address')) {
          if (!empty($this->input->post('stat_city')) && !empty($this->input->post('location'))) {
          $condition = array('city_id' => $this->input->post('stat_city'));
          $getcity = $this->common->getRow('cities', 'city_name', $condition);
          $lat = $this->input->post('lat');
          $long = $this->input->post('long');
          if (!empty($getcity)) {

          if ($this->input->post('address_new_id')) {
          $getaddress = $this->common->getMoreJoin('t1.latitude,t1.longitude,t1.address_type,t1.location,t2.name', 'addresses as t1', array(array('table1' => 'locations as t2', 'join1' => 't1.location=t2.id')), array('t1.address_id' => $this->input->post('address_new_id')), '', '', '', '', 'single');
          if ($getaddress) {
          $lat = $getaddress->latitude;
          $long = $getaddress->longitude;
          $lklocationid = $getaddress->location;
          $location = $this->input->post('location');
          $address_type = $getaddress->address_type;
          $this->session->set_userdata('address_type_selected', $address_type);
          $this->session->set_userdata('add_location_id', $lklocationid);
          }

          $this->session->set_userdata('customer_address_id', $this->input->post('address_new_id'));
          } else {
          $this->session->unset_userdata('customer_address_id');
          $this->session->unset_userdata('address_type_selected');
          $this->session->unset_userdata('add_location_id');
          $location = $this->input->post('location');
          }
          $getcity_name = $getcity->city_name;
          $this->session->set_userdata(array('stat_city' => $getcity_name, 'location' => $location, 'latf' => $lat, 'longf' => $long));
          $this->session->set_userdata(array('order_type' => 'home delivery'));
          $unsetsessiondata = array('pickup_area', 'stat_city_pickup', 'kitchenid', 'kitchen_address');
          $this->session->unset_userdata($unsetsessiondata);
          redirect('product');
          }
          } else {
          $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
          $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
          if ($this->form_validation->run() == FALSE) {
          $this->maintheme('login');
          }
          }
          } else {
          // stat_city_pickup,pickup_area,home_address
          if (!empty($this->input->post('stat_city_pickup')) && !empty($this->input->post('pickup_area'))) {

          //get kitchen address

          $condition1 = array('name' => $this->input->post('pickup_area'));
          $getkitchenaddress = $this->common->getRow('kitchen', 'id,address', $condition1);

          $condition = array('city_id' => $this->input->post('stat_city_pickup'));
          $getcity = $this->common->getRow('cities', 'city_name', $condition);
          if ((!empty($getcity)) && (!empty($getkitchenaddress))) {
          $getcity_name = $getcity->city_name;
          $kitchenid = $getkitchenaddress->id;
          $kitchenaddress = $getkitchenaddress->address;
          $this->session->set_userdata(array('stat_city_pickup' => $getcity_name, 'pickup_area' => $this->input->post('pickup_area'), 'kitchen_address' => $kitchenaddress, 'kitchenid' => $kitchenid));
          $this->session->set_userdata(array('order_type' => 'pickup'));

          $unsetsessiondata1 = array('stat_city', 'stat_city', 'latf', 'longf', 'location', 'customer_address_id', 'address_type_selected', 'add_location_id');
          $this->session->unset_userdata($unsetsessiondata1);
          redirect('product');
          } else {
          $this->form_validation->set_rules('stat_city_pickup', 'Choose option the pickup or delivery ', 'required');
          $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
          if ($this->form_validation->run() == FALSE) {
          $this->maintheme('login');
          }
          }
          } else {
          $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
          $this->form_validation->set_rules('stat_city', 'stat_city', 'required');
          if ($this->form_validation->run() == FALSE) {
          $this->maintheme('login');
          }
          }
          $this->session->set_userdata(array('address' => $this->input->post('location')));
          $this->session->set_userdata(array('order_type' => 'pickup'));

          $this->session->unset_userdata(array('stat_city', 'location'));
          redirect('product');
          }
          } else {
          $this->form_validation->set_rules('location', 'Choose option the pickup or delivery ', 'required');
          if ($this->form_validation->run() == FALSE) {
          $this->maintheme('login');
          }
          }
          } */
        $this->maintheme('home', $data);
    }

    public function about() {
        $this->maintheme('about');
    }

    public function commingsoon() {
        $this->load->view('frontend/commingsoon');
    }

    public function partyorder() {


        if ($this->input->post()) {

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('dateofevent', 'Date of event', 'required');
            $this->form_validation->set_rules('no_of_people', 'Date of event', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $datetme = date("Y-m-d H:i:s");
                $data['name'] = $this->input->post('name');
                $data['mobile'] = $this->input->post('mobile');
                $data['email'] = $this->input->post('email');
                $data['party_date'] = $this->input->post('dateofevent');
                $data['no_of_people'] = $this->input->post('no_of_people');
//                $data['company_name'] = $this->input->post('company_name');
                $data['eventname'] = $this->input->post('eventname');

                $dataarray = array(
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'email' => $data['email'],
                    'date_of_event' => date('Y-m-d', strtotime($this->input->post('dateofevent'))),
                    'no_of_people' => $data['no_of_people'],
//                    'company_name' => $data['company_name'],
                    'event_name' => $data['eventname'],
                    'created_date' => $datetme
                );
                $lastid = $this->common->insert('party_order', $dataarray);
                if ($lastid) {
                    // insert log for all activity
                    $lourl = base_url() . 'Home/partyorder/' . $lastid;
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $logdata = array(
                        'url' => $lourl,
                        'subject' => 'Party Order',
                        'type' => 'WEB',
                        'ip' => $ip,
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'username' => 'Guest',
                        'created_date' => $datetme
                    );
                    $this->common->insert('customer_log', $logdata);
                    $this->session->set_flashdata('success', ' Thanks for the party order, We will get back to you soon!');
                    if ($this->input->post('email')) {
                        $admin_email = $this->common->getRow('adminlogin', 'name,email,telephone', array('username' => 'admin'));
                        if (!empty($admin_email)) {
                            $htmlContent = $this->load->view('email/partyorder.php', $data, TRUE);
                            $subject = 'Party Order';

                            $msg = 'Hi ' . $admin_email->name . ', Customer want to party order. The customer details is: name ' . $data['name'] . ', Mobile . ' . $data['mobile'] . ', Email ' . $data['email'] . ', Party Date ' . $data['party_date'] . ', Event Name ' . $data['eventname'] . ', and No of persons ' . $data['no_of_people'];
                            if ($admin_email->telephone) {
                                send_otp($admin_email->telephone, $msg);
                            }
                            if ($data['mobile']) {
                                $msgcust = 'Hi ' . $data['name'] . ', FOC24 Team will get back to you soon, Your details is ' . $data['name'] . ', Mobile . ' . $data['mobile'] . ', Email ' . $data['email'] . ', Party Date ' . $data['party_date'] . ',Event Name ' . $data['eventname'] . ' and No of persons ' . $data['no_of_people'];
                                send_otp($data['mobile'], $msgcust);
                            }
                            try {
                                $sendmsg = cMail($admin_email->email, $this->input->post('email'), $subject, $htmlContent);
                            } catch (Exception $e) {
                                log_message('sent email', $e->getMessage());
                            }
                        }
                    }
                    redirect('partyorder');
                }
            }
        }

        $this->maintheme('partyorder');
    }

    public function contact() {

        if ($this->input->post()) {
            $admin_email = $this->common->getRow('adminlogin', 'email', array('username' => 'admin'));
            $to = $admin_email->email;
            $from_email = $this->input->post('email');
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $msg = $this->input->post('message');
            $subject = 'Contact Information';
            //Email content
            $htmlContent = '<h1>Customer name is ' . $name . '</h1>';
            $htmlContent .= '<p>' . $msg . '</p>';
            $htmlContent .= '<p> Please Contact on this no. =  <strong> ' . $phone . ' </strong> and email id. = <strong>' . $from_email . '</strong></p>';
            $sendmsg = cMail($to, $from_email, $subject, $htmlContent);
            if ($sendmsg == 1) {
                $msg = 'Thank you for contacting us - we will get back to you soon!';
                $this->session->set_flashdata('success', $msg);
            } else {
                $msg = $sendmsg;
                $this->session->set_flashdata('error', $msg);
            }
            redirect('contact');
        }

        $this->maintheme('contact');
    }

    public function careers() {
        $this->maintheme('careers');
    }

    public function FAQ() {
        $this->maintheme('faq');
    }

    public function download($data = null) {
        $this->load->helper('download');
        $direpath = '';
        if (!empty($data) && $data == 'userapp') {

            $filepath = FCPATH . '/apk/' . $data . '/foc24_user_app.apk';
        } elseif ($data == 'deliveryapp') {
            $filepath = FCPATH . '/apk/' . $data . '/foc24_delivery_app.apk';
    }


        // force_download($filepath, NULL);
        $file = $filepath;
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.android.package-archive');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }

    public function offers() {
        $data['offers'] = $this->common->getRows('coupon', 'coupon_code,image', array('is_active' => 1, 'image !=' => ''));
        $this->maintheme('offers', $data);
    }

    public function login() {

        $this->load->library('form_validation');
        $cust_otp = $cust_pass = $cust_name = $cust_address = $cust_locationn = '';
        $opt = 0;
        $get_otp = $get_pass = $cust_otp = $cust_pass = '';
        $condtionark = array('is_active' => 1, 'apply_for' => 'ALL');
        $coupondetails = $this->common->getRow('coupon', 'coupon_code,image,header_text,footer_text,body_text', $condtionark);
        if (!empty($coupondetails)) {
            $data['popup'] = $coupondetails;
        }
        if ($this->input->post('mobile') && ($this->input->post('otp') || $this->input->post('password'))) {
            $this->session->unset_userdata('optpp');
            $getExist = $this->common->getRow('customers', 'otp,password,address,name,location,id', array('mobile' => $this->input->post('mobile')));
            if (!empty($getExist)) {
                $cust_otp = $getExist->otp;
                $cust_pass = $getExist->password;
                $cust_name = $getExist->name;
                $cust_address = $getExist->address;
                $cust_location = $getExist->location;
                $cust_id = $getExist->id;
            }
            $get_otp = $this->input->post('otp');
            $get_pass = $this->input->post('password');
            $result = '';
            if (!empty($get_otp)) {
                $result = $this->matchcredetial($get_otp, $cust_otp);
                $opt = 1;
            } else {
                $opt = 2;
                if ($get_pass != '' && $cust_pass != '') {
                    $get_pass = md5($get_pass);
                    $result = $this->matchcredetial($cust_pass, $get_pass);
                } else {
                    $opt = 2;
                }
            }
            if ($result) {
                $address = $cafe_id = '';

                $get_cafe = $this->common->getRow('service_details', 'id,address,contact_no', array('is_active' => '1'));
                if (!empty($get_cafe)) {
                    $address = $get_cafe->address;
                    $cafe_id = $get_cafe->id;
                    $cafe_contact = $get_cafe->contact_no;
                }
                $session = array('is_front_login' => 1, 'cust_id' => $cust_id, 'mobile_no' => $this->input->post('mobile'), 'cust_name' => $cust_name, 'cust_address' => $cust_address, 'cust_locationn' => $cust_location, 'cafe_address' => $address, 'cafe_id' => $cafe_id, 'cafe_contact' => $cafe_contact);
                $this->session->set_userdata($session);
                $this->common->update('customers', array('otp' => 0), array('id' => $cust_id));
                // user log when user login
                $lourl = base_url() . 'frontend/home/login';
                $ip = $_SERVER['REMOTE_ADDR'];
                $logdata = array(
                    'url' => $lourl,
                    'subject' => 'Login',
                    'method' => 'post login by customer id = ' . $cust_id,
                    'ip' => $ip,
                    'type' => 'web',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'userid' => $cust_id,
                    'username' => $cust_name,
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->common->insert('customer_log', $logdata);
                // end user activity for the front end

                if ($this->session->userdata('checkafterlogin')) {

                    $checkout = $this->session->userdata('checkafterlogin');
                    if ($checkout == 'checkout') {
                        $this->session->set_userdata('logincheck', '1');
                        redirect('checkout');
                    }
                }
                redirect('/home');
            } else {
                if ($opt === 1) {
                    $this->form_validation->set_rules('mobile', 'Invalid opt please inter valid OTP', 'required');
                    $this->form_validation->set_rules('password', 'Invalid opt please inter valid OTP', 'required');
                } elseif ($opt === 2) {
                    //this->form_validation->set_rules('password', 'Invalid password please enter valid password', 'required');
                    $this->form_validation->set_rules('mobile', 'Invalid User Please enter valid credential', 'required');
                    $this->form_validation->set_rules('password', 'Invalid User Please enter valid credential', 'required');
                }
                $this->session->set_userdata('optpp', $opt);
                if ($this->form_validation->run() == FALSE) {
                    $this->maintheme('login');
                } else {
                    $this->session->set_flashdata('error', 'Invalid User Please enter valid credential');
                    $this->maintheme('login');
                }
            }
        } else {

            $this->form_validation->set_rules('mobile', 'Invalid User Please enter valid credential', 'required');
            $this->form_validation->set_rules('password', 'Invalid User Please enter valid credential', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->maintheme('login');
            }
        }
        //$this->maintheme('login');
    }

    public function checkout() {

        if ($this->session->userdata('logincheck')) {
            
        } else {
            $this->session->unset_userdata('checkoutdata');
            $this->session->unset_userdata('checkafterlogin');
            $this->session->set_userdata('checkoutdata', $this->input->post());
        }

        $this->get_authorize('checkout');
        $product = '';
        $sum = 0;
        $current_time = date('H:i');
        $customer_details = $this->common->getRow('customers', 'id, name,email,mobile', array('id' => $this->session->userdata('cust_id')));
        $product['address_type'] = $this->common->getRows('address_type', 'id, name', array('is_active' => 1));

        if ($customer_details) {

            $product['name'] = $customer_details->name;
            $product['email'] = $customer_details->email;
            $product['mobile'] = $customer_details->mobile;

            // $address = $this->common->getRow('addresses', 'address_id,address_1,address_2,postcode,', array('customer_id' => $this->session->userdata('cust_id')), array('column' => 'address_id', 'orderby' => 'DESC'));
            // below is use before the address type.
            //$address = $this->common->getTwoJoins('t1.address_id,t1.address_1,t1.address_2,t1.postcode,t2.city_name', 'addresses as t1', 'cities as t2', 't1.city_id=t2.city_id', '', array('t1.customer_id' => $this->session->userdata('cust_id')));
            $tablesjoin = array(array('table1' => 'cities as t2', 'join1' => 't2.city_id=t1.city_id'), array('table1' => 'address_type as t3', 'join1' => 't3.id=t1.address_type'));
            $group_by = 't1.address_type';
            $order_by = array('column' => 't1.modify_date', 'orderby' => 'DESC');
            if ($this->session->userdata('address_type_selected') != '' && $this->session->userdata('customer_address_id') != '') {
                $addcondtion = array('t1.customer_id' => $this->session->userdata('cust_id'), 't1.address_id' => $this->session->userdata('customer_address_id'));
            } else {
                $addcondtion = array('t1.customer_id' => $this->session->userdata('cust_id'));
            }

            $address = $this->common->getMoreJoin('t1.address_id,t1.address_1,t1.address_2,t1.alternate_mobile,t2.city_name,t1.address_type', 'addresses as t1', $tablesjoin, $addcondtion, $order_by, '', $group_by, 'LEFT', 'single');

            if (!empty($address)) {
                $product['address'] = ucfirst($address->address_1);
                $product['address1'] = ucfirst($address->address_2);
                $product['alternate_mobile'] = ucfirst($address->alternate_mobile);
                $product['address_type_select'] = $address->address_type;
            } else {
                $product['address'] = '';
                $product['address_type_select'] = '';
            }
        }

        if ($this->input->post() || $this->session->userdata('checkoutdata')) {
            if (!empty($this->session->userdata('checkoutdata'))) {
                $_POST = $this->session->userdata('checkoutdata');
            }

            $this->session->unset_userdata(array('cartdata', 'coupon_discount', 'coupon_code', 'coupon_id', 'after_discount_amount', 'after_dicount_total_amount_with_shipping_charges'));
            $conditon = array('is_active' => '1');
            $product['tax'] = $this->common->getRows('tax', 'tax_name,percent', $conditon);
            $product['shipping'] = $this->common->getRow('shipping', 'min_price,charge,shipping_time', $conditon);

            $count = count($_POST['quantity']);
            for ($i = 0; $i < $count; $i++) {
                $conditon12 = '';
                $flage = 1;
                if (is_numeric($_POST['shipping'][$i])) {

                    $product_id = $_POST['shipping'][$i];
                    $type_product = ''; //strtolower($_POST['is_item_type'][$i]);
                    $price = $_POST['amount'][$i];
                    $conditon12 = array('pid' => $product_id);
                    $productdetails = $this->common->getRow('product', 'pid,start_time,end_time,price,half_price,discount,discount_price,discount_half_price', $conditon12);

                    if (!empty($productdetails)) {
                        $fullPrice = $halfprice = $is_discount = 0;
                        $fullPrice = $productdetails->price;
//                        if ($productdetails->discount) {
//                            $is_discount = 1;
//                            $fullPrice = $productdetails->discount_price;
//                            $halfprice = $productdetails->discount_half_price > 0 ? $productdetails->discount_half_price : $productdetails->half_price;
//                        } else {
//                            $is_discount = 0;
//                            $fullPrice = $productdetails->price;
//                            $halfprice = $productdetails->half_price;
//                        }

                        $checkproduct_price = $this->checkProductPrice($fullPrice, $halfprice, $_POST['amount'][$i]);
                        if ($checkproduct_price) {

                            $start_time = $productdetails->start_time != '00:00:00' ? date('H:i', strtotime($productdetails->start_time)) : '';
                            $end_time = $productdetails->end_time != '00:00:00' ? date('H:i', strtotime($productdetails->end_time)) : '';
                            if ($start_time != '') {
                                if ($current_time >= $start_time && $current_time <= $end_time) {
                                    $flage = 1;
                                } else {
                                    $flage = 0;
                                }
                            }

                            $condion = '';
                            if (!empty($_POST['shipping'][$i])) {
                                $condion = array('pavalue' => $_POST['amount'][$i], 'product_id' => $_POST['shipping'][$i]);
                                $attribute_id = $this->common->getRow('product_attribute', 'id', $condion);
                                if (!empty($attribute_id)) {
                                    $attid = $attribute_id->id;
                                } else {
                                    $attid = '';
                                }
                            }
                            if ($flage) {
                                $sum += ($_POST['amount'][$i] * $_POST['quantity'][$i]);
                                if ($attid != '') {
                                    $product['cart'][] = array('quanity' => $_POST['quantity'][$i], 'product_name' => $_POST['item_name'][$i], 'product_type' => $_POST['is_item_type'][$i], 'price' => $_POST['amount'][$i], 'item_id' => $_POST['shipping'][$i], 'attid' => $attid);
                                } else {
                                    $product['cart'][] = array('quanity' => $_POST['quantity'][$i], 'product_name' => $_POST['item_name'][$i], 'product_type' => $_POST['is_item_type'][$i], 'price' => $_POST['amount'][$i], 'item_id' => $_POST['shipping'][$i]);
                                }
                            } else {
                                $std = date('h:i a', strtotime($productdetails->start_time));
                                $end = date('h:i a', strtotime($productdetails->end_time));
                                $product['error'][] = $_POST['item_name'][$i] . ' product available on  ' . $std . ' to ' . $end . '. This product has been exclude from cart!';
                            }
                        } else {
                            $product['error'][] = $_POST['item_name'][$i] . ' product price has been changed. This product has been exclude from cart!';
                            // $product['error'][] = $_POST['item_name'][$i] . ' product ' . strtolower($_POST['is_item_type'][$i]) . ' price has been changed. This product has been exclude from cart!';
                        }
                    } else {
                        $product['error'][] = 'No Product exists ' . $_POST['item_name'][$i];
                    }
                } else {
                    $product['error'][] = 'No Product exists ' . $_POST['item_name'][$i];
                }
            }
            $product['total_amount'] = array('subtotal' => $sum, 'currency_code' => $_POST['currency_code']);
            $this->session->set_userdata('cartdata', $product);
            $this->session->unset_userdata('checkoutdata');
            $this->session->unset_userdata('checkafterlogin');

            $this->session->unset_userdata('logincheck');
            $this->maintheme('checkout', $product);
        } else {
            redirect('/product');
        }
    }

    public function help() {
        $this->maintheme('help');
    }

    public function termsCond() {
        $this->maintheme('termscond');
    }

    public function privacyPolicy() {
        $this->maintheme('privacypolicy');
    }

    public function deliveryPolicy() {
        $this->maintheme('deliverypolicy');
    }

    public function returnPolicy() {
        $this->maintheme('returnpolicy');
    }

    public function service() {
        $this->get_authorize();
        $order_by = array('column' => 'order_no', 'orderby' => 'ASC');
        $conditon = array('is_active' => '1');
        $data['serverces'] = $this->common->getRows('services', 'id,name', $conditon, $order_by);
        $this->maintheme('services', $data);
    }

    public function track() {

        $customer = $this->session->userdata('cust_id');
        if ($this->input->post()) {
            if ($this->input->post('trackid') && $customer) {
                $status = $this->common->getTwoJoins('t2.status_comment', 'orders as t1', 'order_statuses as t2', 't1.status_id=t2.status_id', '', array('t1.order_id' => $this->input->post('trackid'), 't1.customer_id' => $customer));
                if (!empty($status)) {
                    $mainstatus = $status[0]['status_comment'];
                } else {
                    $mainstatus = 'No order found';
                }
                $this->session->set_flashdata('success', $mainstatus);
                redirect('/track');
            } else {
                $this->session->set_flashdata('error', 'User Must Login!');
                redirect('/track');
            }
        }

        $this->maintheme('track');
    }

    public function product($id = null) {

        $current_time = date('H:i');
        if ($this->session->userdata('kitchen_address') || $this->session->userdata('location')) {
            
        } else {
            redirect('');
        }
        // get cat namel
        if (!empty($this->input->get('cat'))) {
            $data['catname'] = $this->input->get('cat');
        }

        // new functionality with addons and serves
        $data['category'] = $this->common->getRows('category', 'id,name', array('is_active', '1'), array('column' => 'priority_no', 'orderby' => 'ASC'));
        $cafe_id = '';
        $condtion = array('is_active' => 1);
        $itmefilter = $this->common->getRows('filter_time', 'time_from,time_to', array('is_active' => 1));
        $data['addons'] = $this->common->getRows('addons', 'id,name,price', array('is_active' => 1), array('column' => 'name', 'orderby' => 'ASC'));
        if (!empty($itmefilter)) {
            $data['time_filter'] = $itmefilter;
        }
        $cityid = strtolower(@$this->session->userdata('stat_city_pickup') ? $this->session->userdata('stat_city_pickup') : $this->session->userdata('stat_city'));
        $order_by = array('column' => 'priority_no', 'orderby' => 'ASC');
        $category = $this->common->getRows('category', 'id,name,description', $condtion, $order_by);
        $get_cafe = $this->common->getTwoJoins('t1.id as cafeid', 'service_details as t1', 'cities as t2', 't1.city_id=t2.city_id', '', array('t2.city_name' => $cityid), '', 1);
        if (!empty($get_cafe)) {
            $cafe_id = $get_cafe->cafeid;
        }
        if (!empty($category)) {
            foreach ($category as $cat) {
                $isnonveg = '';
                $data1 = '';

                $servesArray = '';
                if ($cat['id']) {
                    $join = 't1.pid = t2.product_id';
                    $where = array('t2.category_id' => $cat['id'], 't1.service_id' => $cafe_id);
                    $order_by = array('column' => 't1.created_date', 'orderby' => 'DESC');
                    $product = $this->common->getTwoJoins('t1.pid,t1.pname,t1.price,t1.image,t1.start_time,t1.end_time,t1.is_nonveg,t1.description,t1.is_addons', 'product as t1', 'product_category as t2', $join, '', $where, $order_by);
                    if (!empty($product)) {
                        foreach ($product as $pro) {
                            $servesArray = '';
                            $price = 0;
                            $start_time = $pro['start_time'] != '00:00:00' ? date('H:i', strtotime($pro['start_time'])) : '';
                            $end_time = $pro['end_time'] != '00:00:00' ? date('H:i', strtotime($pro['end_time'])) : '';
                            if ($start_time != '') {
                                if ($current_time >= $start_time && $current_time <= $end_time) {
                                    $is_available = 1;
                                } else {
                                    $is_available = 0;
                                }
                            }
                            $swhere = array('product_id' => $pro['pid']);
                            $sorder_by = array('column' => 'id', 'orderby' => 'ASC');
                            $serves = $this->common->getRows('product_attribute', 'id,TRIM(paname) as paname,pavalue,description as serve_des', $swhere, $sorder_by);


                            if (!empty($serves)) {
                                $price = $serves[0]['pavalue'];
                                //$servescount = count($serves);
                                //if ($servescount > 1) {
                                //    
                                //}
                                $servesArray = json_encode($serves);
                            }
                            $data1['product'][] = array(
                                'pid' => $pro['pid'],
                                'pname' => ucfirst($pro['pname']),
                                'is_nonveg' => $pro['is_nonveg'],
                                'is_addons' => $pro['is_addons'],
                                'description' => ucfirst($pro['description']),
                                'price' => $price,
                                'servesArray' => $servesArray,
                                'is_available' => $is_available,
                                'start_time' => $start_time != '' ? date('h:i a', strtotime($pro['start_time'])) . ' : ' : '',
                                'end_time' => $end_time != '' ? date('h:i a', strtotime($pro['end_time'])) : '',
                                'image_url' => $pro['image']
                            );
                        }
                        $data1['category_id'] = $cat['id'];
                        $data1['category_name'] = strtoupper($cat['name']);
                        $data1['cat_description'] = ucfirst($cat['description']);
                        $data3[] = $data1;
                    }
                }
            }
        }

        // Tranding products
        $order_by_trand = array('column' => 'tranding', 'orderby' => 'DESC');
        $groupby = array('product_id');
        $tranding = $this->common->getRows('order_products', 'order_product_id, product_id, count("product_id") as tranding', '', $order_by_trand, $groupby);

        if (!empty($tranding)) {
            foreach ($tranding as $trand) {
                $where12 = array('pid' => $trand['product_id']);
                $product12 = $this->common->getRow('product', 'pid,pname,image,price,start_time,end_time,is_nonveg,description,is_addons', $where12);

                if (!empty($product12)) {
                    $price = 0;
                    $is_available = '';
                    $start_time = $product12->start_time != '00:00:00' ? date('H:i', strtotime($product12->start_time)) : '';
                    $end_time = $product12->end_time != '00:00:00' ? date('H:i', strtotime($product12->end_time)) : '';
                    if ($start_time != '') {
                        if ($current_time >= $start_time && $current_time <= $end_time) {
                            $is_available = 1;
                        } else {
                            $is_available = 0;
                        }
                    }
                    $servesArray = '';
                    $swhere = array('product_id' => $product12->pid);
                    $sorder_by = array('column' => 'id', 'orderby' => 'ASC');
                    $serves = $this->common->getRows('product_attribute', 'id,TRIM(paname) as paname,pavalue,description as serve_des', $swhere, $sorder_by);
                    if (!empty($serves)) {
                        $servescount = count($serves);
                        $price = $serves[0]['pavalue'];
                        
                            $servesArray = json_encode($serves);
                        
                    }
                    $data12['product'][] = array(
                        'pid' => $product12->pid,
                        'pname' => $product12->pname,
                        'is_nonveg' => $product12->is_nonveg,
                        'is_addons' => $product12->is_addons,
                        'description' => $product12->description,
                        'price' => $price,
                        'servesArray' => $servesArray,
                        'is_available' => $is_available,
                        'start_time' => $start_time != '' ? date('h:i a', strtotime($product12->start_time)) . ' : ' : '',
                        'end_time' => $end_time != '' ? date('h:i a', strtotime($product12->end_time)) : '',
                        'image_url' => $product12->image
                    );
                }
            }
        } else {
            $data12['product'] = array();
        }
        $data12['category_id'] = 'Trending';
        $data12['category_name'] = 'Trending';


        $data3[] = $data12;

        $data['product'] = $data3;

        // Old functionality 2 sept by fkm
        // service details
        //$this->get_authorize();
        /* $total = '';
          $cafeid = '1';
          $cityid = strtolower(@$this->session->userdata('stat_city_pickup') ? $this->session->userdata('stat_city_pickup') : $this->session->userdata('stat_city'));
          $get_cafe = $this->common->getTwoJoins('t1.id as cafeid', 'service_details as t1', 'cities as t2', 't1.city_id=t2.city_id', '', array('t2.city_name' => $cityid));
          $itmefilter = $this->common->getRows('filter_time', 'time_from,time_to', array('is_active' => 1));
          if (!empty($itmefilter)) {
          $data['time_filter'] = $itmefilter;
          }

          if (!empty($get_cafe)) {
          $cafeid = $get_cafe[0]['cafeid'];
          }

          // pagination code

          if ($this->input->get('page')) {
          $filter['page'] = (int) $this->input->get('page');
          } else {
          $filter['page'] = '';
          }
          $pagination_url = base_url() . "product";

          $showpage = 10;


          $data['category'] = $this->common->getRows('category', 'id,name', array('is_active', '1'), array('column' => 'name', 'orderby' => 'ASC'));

          if ($this->input->post('search')) {

          if (!empty($this->input->post('hdnca'))) {

          $category = $this->input->post('hdnca');
          $data['categoryfilter'] = $category;
          $likecondition = array('t1.pname' => $this->input->post('search'));
          $data['product'] = $this->HomeModel->get_category_product($category, $cafeid, $likecondition, array('limit' => $showpage, 'start' => $filter['page']));
          } else {

          $condition = array('is_active' => '1', 'service_id' => $cafeid);
          $order_by = array('column' => 'created_date', 'orderby' => 'DESC');
          $likecondition = array('pname' => $this->input->post('search'));
          $data['product'] = $this->HomeModel->get_search_product('product', '*', $condition, $order_by, $likecondition, array('limit' => $showpage, 'start' => $filter['page']));
          $total = $this->HomeModel->get_search_product('product', 'count(pid) as totals', $condition, $order_by, $likecondition);
          if (!empty($total)) {
          $total = $total[0]['totals'];
          }
          }
          $data['searchfilter'] = $this->input->post('search');
          $data['search'] = 1;
          } elseif ($this->input->post('category')) {

          $likecondition = '';
          if (!empty($this->input->post('hdnse'))) {
          $like = $this->input->post('hdnse');
          $data['searchfilter'] = $like;
          $likecondition = array('t1.pname' => $like);
          }
          $category = $this->input->post('category');
          $data['product'] = $this->HomeModel->get_category_product($category, $cafeid, $likecondition, array('limit' => $showpage, 'start' => $filter['page']));
          $total = $this->HomeModel->get_category_product($category, $cafeid, $likecondition);
          if (!empty($total)) {
          $total = count($total);
          }

          $data['categoryfilter'] = $category;
          } else {
          $condition = array('is_active' => '1', 'service_id' => $cafeid);
          $order_by = array('column' => 'created_date', 'orderby' => 'DESC');
          $data['product'] = $this->common->getRows('product', '*', $condition, $order_by, '', array('limit' => $showpage, 'start' => $filter['page']));
          $total = $this->common->getRow('product', ' count(pid)as total', $condition, $order_by);
          if (!empty($total)) {
          $total = $total->total;
          }
          }

          // pagination
          if ($total > 0) {
          $total_row = $total;
          } else {
          $total_row = 0;
          }

          $data['links'] = getPagination($total_row, $pagination_url, '', '', $showpage);

         */

        $this->maintheme('product', $data);
    }

    public function matchcredetial($val1, $val2) {

        if ($val1 === $val2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function test() {
        // $this->load->view('frontend/test');
        $this->maintheme('test');
    }

    public function order() {

        $this->get_authorize('order');
        if ($this->input->post()) {
            $this->form_validation->set_rules('mobile', 'Mobile', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            if ($this->session->userdata('order_type') != 'pickup') {
                $this->form_validation->set_rules('location', 'Location', 'required');
            }
            if ($this->form_validation->run() == FALSE || empty($_SESSION['cartdata']['cart'])) {
                $this->session->set_userdata('logincheck', '1');
                redirect('checkout');
            } else {

                //variable define
                $is_wallet = 0;
                $wallet_id = '';
                $remain_wallet = 0;
                $trackpayment = 0;
                $paidwallet = 0;
                $sum = 0;
                $gstdata = '';
                $location_id = '';
                $location = '';
                $shippige_charges = 0;
                $payment_method = '';
                $payment_status = 'PENDING';
                // input data
                $postsmobile = $this->input->post('mobile');
                $postname = $this->input->post('name');
                $postemail = $this->input->post('email');
                $postaddress = $this->input->post('address');
                $postaddress1 = $this->input->post('address1');
                $address_type = $this->input->post('address_type');
                $postLocation = $this->input->post('location');
                $postalCity = $this->input->post('City');
                $postaltno = $this->input->post('altno');
                $codorwallet = $this->input->post('optionsRadios');
                $comments = $this->input->post('comment');
                // session data
                $cart = json_encode($this->session->userdata('cartdata'));
                $cart_count = count($_SESSION['cartdata']['cart']);
                $discount_total = $this->session->userdata('coupon_discount');
                $customerid = $this->session->userdata('cust_id');
                $order_type = $this->session->userdata('order_type');
                $coupon_id = $this->session->userdata('coupon_id');
                $subtotal = $_SESSION['cartdata']['total_amount']['subtotal'];

                // gst
                $cartdata['tax'] = json_decode(json_encode($_SESSION['cartdata']['tax']));
                $cartdata['shipping'] = $_SESSION['cartdata']['shipping'];
                $tax_gst_shipping = get_gst_shippig_coupon_charge((object) $cartdata, $subtotal);
                $html['tax_gst_shipping'] = $tax_gst_shipping;
                if (!empty($tax_gst_shipping)) {
                    $html['tax_gst_shipping'] = $tax_gst_shipping;
                    $order_total = $subtotal + $tax_gst_shipping['gst_amount'] + $tax_gst_shipping['shipping_chaerges'];
                    $shippige_charges = $tax_gst_shipping['shipping_chaerges'];
                } else {
                    $order_total = $subtotal;
                }

                if ($this->input->post('walletamount')) {

                    $wallet = $this->common->getRow('wallet', 'id,amount', array('customer_id' => $customerid, 'is_enable' => 1));
                    if (!empty($wallet)) {
                        $is_wallet = 1;
                        $wallet_id = $wallet->id;
                        if ($order_total <= $wallet->amount) {
                            $remain_wallet = $wallet->amount - $order_total;
                            $paidwallet = $order_total;
                            $payment_status = $codorwallet == 'ONLINE' ? 'RECEIVED' : $payment_status;
                            $payment_method = 'Paid Via Wallet';
                            $trackpayment = 0;
                        } else {
                            $remain_wallet = 0;
                            $trackpayment = $order_total - $wallet->amount;
                            $paidwallet = $wallet->amount;
                        }
                    }
                } else {

                    $trackpayment = $order_total;
                }
                $fulladdress1 = $postaddress;
                $fulladdress = $postaddress1 != "" ? ($fulladdress1 . ', ' . $postaddress1) : $fulladdress1;
                $session_locations = $this->session->userdata('location') != '' ? $this->session->userdata('location') : '';

                // save the address of the customer and set location
                if ($this->session->userdata('add_location_id')) {
                    $location_id = $this->session->userdata('add_location_id');
                }
                if (!empty($address_type)) {
                    $location_id = saveAddress($address_type, $customerid, $postaddress, $postaddress1, $postaltno, $location_id);
                }

                if ($order_type != 'pickup' && $session_locations != '') {

                    if ($this->session->userdata('latf') && $this->session->userdata('longf')) {
                        $getLat = $this->session->userdata('latf');
                        $getLong = $this->session->userdata('longf');
                        $sqlquery = 'SELECT ( 3959 * acos( cos( radians(' . $getLat . ') ) * cos( radians( `latitude`) ) * cos( radians( `longitude` ) - radians(' . $getLong . ') ) + sin( radians(' . $getLat . ') ) * sin( radians( `latitude` ) ) ) ) AS distance,id,name FROM kitchen ORDER BY distance';
                        $findnearst = $this->common->getNearestKitchen($sqlquery, 'single');
                        $location = $this->session->userdata('location');
                        if (!empty($findnearst)) {
                            $kichenid = $findnearst->id;
                        } else {
                            $condsr = array('is_active' => 1);
                            $kitrchen = $this->common->getRow('kitchen', 'id', $condsr);
                            if (!empty($kitrchen)) {
                                $kichenid = $kitrchen->id;
                            } else {
                                log_message('error', 'Kitchen not there in the database, Please check');
                            }
                        }
                    } else {
                        $condsr1 = array('is_active' => 1);
                        $kitrchen = $this->common->getRow('kitchen', 'id', $condsr1);
                        if (!empty($kitrchen)) {
                            $kichenid = $kitrchen->id;
                        }
                    }
                    $postalCity = $this->session->userdata('stat_city');

                    // end the google map functionality for the geting nearest kithcen

                    /* This is use before google map, a location based when select location then uncomment it.
                     *   $condition = array('name' => $session_locations);
                      $location_id = $this->common->getRow('locations', 'id', $condition);
                      if (!empty($location_id)) {
                      $location_id = $location_id->id;

                      $kicond = array('location_id' => $location_id);
                      $kitchendata = $this->common->getRow('kitchen_location', 'kitchen_id', $kicond);

                      if (!empty($kitchendata)) {
                      $kichenid = $kitchendata->kitchen_id;
                      } else {
                      $kitchendata1 = $this->common->getRow('kitchen_location', 'kitchen_id');
                      if (!empty($kitchendata1)) {
                      $kichenid = $kitchendata1->kitchen_id;
                      }
                      }
                      } */
                } else {
                    $location = '';
                    $kichenid = $this->session->userdata('kitchenid');
                    $postalCity = $this->session->userdata('stat_city_pickup');
                }
                $time = date('H:i:s');
                if (($postsmobile == $this->session->userdata('mobile_no'))) {
                    $date = date('Y-m-d H:i:s');
                    $mainarray = array(
                        'customer_id' => $customerid,
                        'name' => $postname,
                        'email' => $postemail,
                        'kitchen_id' => $kichenid,
                        'telephone' => $postsmobile,
                        'alternate_telephone' => $postaltno,
                        'location_id' => $location_id, // its use for the before google map, using the select location.
                        'location' => $location,
                        'address' => $fulladdress,
                        'city' => $postalCity,
                        'cart' => $cart,
                        'payment_type' => $codorwallet,
                        'total_items' => $cart_count,
                        'order_type' => $order_type,
                        'payment' => $payment_status,
                        'payment_method' => $payment_method,
                        'comment' => $comments,
                        'date_added' => $date,
                        'order_time' => $time,
                        'order_date' => $date,
                        'order_total' => $order_total,
                        'dicount_amount' => $discount_total,
                        'wallet_amount' => $paidwallet,
                        'shipping_charges' => $shippige_charges,
                        'status_id' => '1',
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    );
                    $last_id = $this->common->insert('orders', $mainarray);
                    if ($last_id) {
                        $this->common->insert('order_status_history', array('order_id' => $last_id, 'status_id' => 1, 'notify' => 1, 'date_added' => $date));
                        // user_log 
                        // insert log for all activity
                        $lourl = base_url() . 'frontend/home/order';
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $logdata = array(
                            'url' => $lourl,
                            'subject' => 'Place order by customor',
                            'method' => 'POST AND ORDERID =' . $last_id,
                            'ip' => $ip,
                            'type' => 'web',
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'userid' => $this->session->userdata('cust_id'),
                            'username' => $this->session->userdata('cust_name'),
                            'created_date' => $date
                        );
                        $this->common->insert('customer_log', $logdata);
                        // end user activity for the front end

                        if ($this->session->userdata('coupon_id')) {
                            $coupons_order_history = array(
                                'order_id' => $last_id,
                                'coupon_id' => $coupon_id,
                                'customer_id' => $customerid,
                                'code' => $this->session->userdata('coupon_code'),
                                'min_total' => $subtotal,
                                'amount' => $discount_total,
                                'date_used' => $date,
                                'status' => '1'
                            );
                            $this->common->insert('coupons_history', $coupons_order_history);
                        }
                        $cartdata = $_SESSION['cartdata']['cart'];
                        if (!empty($cartdata)) {
                            $productorderhtml = '';
                            foreach ($cartdata as $cart) {
                                $subtotolacrt = $attribut = '';
                                $attribut = isset($cart['attid']) ? $cart['attid'] : '0';
                                $subtotolacrt = $cart['quanity'] * $cart['price'];
                                $sum += $subtotolacrt;
                                $productorderhtml .= '<tr>
                                    <td style="font-size:12px;border:1px solid #dddddd;text-align:left;">' . ucfirst($cart['product_name']) . ' (' . $cart['product_type'] . ') ' . '</td>
                                    <td style="font-size:12px;border:1px solid #dddddd;text-align:right;">' . $cart['quanity'] . '</td>
                                    <td style="font-size:12px;border:1px solid #dddddd;text-align:right;"><i class="fa fa-inr"></i>' . $cart['price'] . '</td>
                                    <td style="font-size:12px;border:1px solid #dddddd;text-align:right;">' . $subtotolacrt . '</td>
                                </tr>';
                                $order_products = array(
                                    'order_id' => $last_id,
                                    'product_id' => $cart['item_id'],
                                    'name' => $cart['product_name'],
                                    'quantity' => $cart['quanity'],
                                    'type' => strtolower($cart['product_type']),
                                    'price' => $cart['price'],
                                    'subtotal' => $subtotolacrt,
                                    'option_values' => $attribut
                                );
                                $this->common->insert('order_products', $order_products);
                            }
                        }
                        // cart should be empty when order successfull
                        $this->session->set_userdata('cartempty', '1');

                        // destroy session after taking order from customor  
                        $daata['cartdata'] = $_SESSION['cartdata']['cart'];
                        $daata['tax'] = $_SESSION['cartdata']['tax'];
                        $daata['order_details'] = array('orderid' => $last_id, 'subtotal' => $subtotal, 'order_total' => $order_total, 'order_type' => $order_type, 'payment_method' => $codorwallet);
                        $daata['coupon_tails'] = array('discount_amount' => $this->session->userdata('coupon_discount'), 'coupon_code' => $this->session->userdata('coupon_code'));
                        $daata['customer_details'] = array('name' => $postname,
                            'email' => $postemail,
                            'telephone' => $postsmobile,
                            'location_id' => $session_locations,
                            'address' => $fulladdress,
                            'city' => $postalCity,);
                        $daata['wallet_amount'] = $paidwallet;
                        $daata['shipping'] = $this->common->getRow('shipping', 'min_price,charge,shipping_time', array("is_active" => 1));
                        $admin_mobile = $this->common->getRow('adminlogin', 'telephone,email', array("username" => 'admin'));

                        // Create wallet functionality
                        if (($is_wallet == 1) && (!empty($wallet_id))) {
                            $wallethistory = array(
                                'order_id' => $last_id,
                                'wallet_id' => $wallet_id,
                                'withdrawal' => $paidwallet,
                                'comments' => 'Amount paid via wallet by the customer!',
                                'created_date' => $date
                            );
                            $walletehistory = $this->common->insert('wallet_history', $wallethistory);

                            $test = $this->common->update('wallet', array('amount' => $remain_wallet), array('customer_id' => $customerid, 'id' => $wallet_id));

                            if (!empty($test)) {
                                $wallet_deductmsg = $paidwallet . ' amount using wallet on order number ' . $last_id . ' Availabel wallet balance is ' . $remain_wallet;
                                $message = send_otp($postsmobile, $wallet_deductmsg);
                            }
                        }
                        // Wallet functionality end

                        if ($codorwallet === 'ONLINE' && $trackpayment > 0) {
                            // Paytm payment intergration
                            header("Pragma: no-cache");
                            header("Cache-Control: no-cache");
                            header("Expires: 0");
                            require_once(APPPATH . "libraries/lib/config_paytm.php");
                            require_once(APPPATH . "libraries/lib/encdec_paytm.php");

                            // integration paytm for checksum


                            $p['MID'] = PAYTM_MERCHANT_MID;
                            $p['ORDER_ID'] = $last_id;
                            $p['CUST_ID'] = $customerid;
                            $p['INDUSTRY_TYPE_ID'] = PAYTM_INDUSTRY_ID;
                            $p['CHANNEL_ID'] = PAYTM_CHANNEL_ID;
                            $p['TXN_AMOUNT'] = 2; //$trackpayment;
                            $p['WEBSITE'] = PAYTM_MERCHANT_WEBSITE;
                            $p['CALLBACK_URL'] = base_url('success_payment');

                            // check the envirment is live or test
                            $data['checksum'] = getChecksumFromArray($p, PAYTM_MERCHANT_KEY);
                            $data['p'] = $p;
                            $data['PAYTM_TXN_URL'] = PAYTM_TXN_URL;
                            $this->maintheme('online', $data);
                        } else {
                            if (!empty($admin_mobile)) {
                                $msgadmin = $last_id . ' New Order is Received with total amount Rs. ' . number_format($order_total, 2) . ' check order list on administrator panel';
                                $message = send_otp($admin_mobile->telephone, $msgadmin);
                                if ($admin_mobile->email) {
                                    $to = $admin_mobile->email;
                                    $from_email = '';
                                    $subject = $last_id . ' New Order ';
                                    $htmlContent = '<h1>Order From ' . $postname . '</h1>';
                                    $htmlContent .= '<p> New Order is received the order no is <strong>' . $last_id . '</strong> and total amount is <strong>' . $trackpayment . '</strong> </p>';

                                    if ($postemail) {
                                        $from_email = $postemail;
                                    }
                                    $sendmsg = cMail($to, $from_email, $subject, $htmlContent);
                                }
                            }
                            if ($kichenid) {
                                $kitchen = $this->common->getRow('kitchen', 'name,contact_no,email_id', array('id' => $kichenid));

                                if (!empty($kitchen)) {

                                    $msgadmin = $last_id . ' New Order is Received with total amount Rs. ' . number_format($order_total, 2) . ' check order list on kitchen panel';
                                    $message = send_otp($kitchen->contact_no, $msgadmin);
                                    $to = $kitchen->email_id;
                                    $name = $kitchen->name;
                                    $from_email = '';

                                    $subject = $last_id . ' New Order on the kitchen ';
                                    $htmlContent = '<h1>Hi ' . $name . ',</h1>';
                                    $htmlContent .= '<h4>Order From ' . $postname . '</h4>';
                                    $htmlContent .= '<p> New Order is received the order no is <strong>' . $last_id . '</strong> and total amount is <strong>' . $trackpayment . '</strong> </p>';

                                    if ($postemail) {
                                        $from_email = $postemail;
                                    }
                                    $sendmsg = cMail($to, $from_email, $subject, $htmlContent);
                                }
                            }

                            if ($postemail) {
                                $orderid = base64_encode($last_id);
                                $c_name = $postname != '' ? $postname : 'Guest';
                                $shipcharge = $shippige_charges > 0 ? $shippige_charges : 'Free';
                                $html['paidwallet'] = $paidwallet;
                                $html['shipcharge'] = $shipcharge;
                                $html['sum'] = $sum;
                                $html['fulladdress'] = $fulladdress;
                                $html['postsmobile'] = $postsmobile;
                                $html['postemail'] = $postemail;
                                $html['c_name'] = $c_name;
                                $html['codorwallet'] = $codorwallet;
                                $html['date'] = $date;
                                $html['last_id'] = $last_id;
                                $html['orderid'] = $orderid;
                                $html['productorderhtml'] = $productorderhtml;


                                $from_email = '';

                                $subject = $last_id . ' Cafe Order # ' . $last_id;

                                //Remove the email content we use the email/customer.php view for the sending email for the customer.
                                /* $htmlContent = '<html><body>';
                                  $htmlContent .= '<p><a href="' . base_url() . '"><img src="' . base_url('assets/images/o2logo.jpg') . '"></a></p>';
                                  $htmlContent .= '<h1 style="text-align:left">Hi ' . $c_name . ',</h1>';
                                  $htmlContent .= '<p>Thank you for your interest in Order delivery. Your order has been received and will be delivered ASAP </p>';
                                  $htmlContent .= '<p>To view your order click on the link below: <br>';
                                  $htmlContent .= '<a href="' . base_url('profile/orderdetails/') . $orderid . '"> Click Order Details</a></p>';
                                  $htmlContent .= '<table rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px;" cellpadding="10">';
                                  $htmlContent .= "<tr ><td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222' colspan='2'><b>Order Details</b></td></tr>";
                                  $htmlContent .= "<tr><td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'><b>Order Id: </b>" . $last_id . "  <br> <b>Date Added:</b> " . date('d-m-Y h:i:s', strtotime($date)) . " <br> <b>Payment Method:</b> " . ucfirst($codorwallet) . " <br> <b>Payment Status:</b> Pending </td>";
                                  $htmlContent .= "<td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'><b>Email:</b>" . $postemail . "  <br> <b>Mobile:</b> " . $postsmobile . " <br> <b>Order Status:</b> Received </td></tr>";
                                  $htmlContent .= "</table>";
                                  $htmlContent .= '<table rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px" cellpadding="10">';
                                  $htmlContent .= "<tr><td style='font-size:12px;border:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b> Sender Details </b></td><td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b> Delivery Address </b></td></tr>";
                                  $htmlContent .= "<tr><td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'><b>Name: </b>" . $c_name . "  <br> <b>Email: </b>" . $postemail . "  <br> <b>Mobile:</b> " . $postsmobile . "</td>";
                                  $htmlContent .= "<td style='border-collapse:collapse;width:100%;border:1px solid #dddddd;margin-bottom:20px'>" . $fulladdress . "</td></tr>";
                                  $htmlContent .= "</table>";

                                  $htmlContent .= '<table rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px" cellpadding="10">';
                                  $htmlContent .= "<tr><td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b>Product </b></td><td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b>Quantity </b></td>";
                                  $htmlContent .= "<td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b>Price </b></td><td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'><b>Total </b></td></tr>";
                                  $productorderhtml .= '<tr style="border-top:2px solid #cacaca;">
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Sub-Total:</b></td>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $sum . '</td>
                                  </tr><tr>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Shipping:</b></td>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $shipcharge . '</td>
                                  </tr>';
                                  if ($paidwallet > 0) {
                                  $productorderhtml .= '<tr>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Wallet:</b></td>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">-' . $paidwallet . '</td>
                                  </tr>';
                                  }
                                  if (isset($tax_gst_shipping) && ($tax_gst_shipping['gst_amount'] > 0)) {
                                  $productorderhtml .= '<tr>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>' . $tax_gst_shipping['sgst_cgst'] . ':</b></td>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $tax_gst_shipping['gst_amount'] . '</td>
                                  </tr>';
                                  }

                                  $productorderhtml .= '<tr style="border-top:2px solid #cacaca;">

                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Total:</b></td>
                                  <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $trackpayment . '</td>
                                  </tr>';
                                  $htmlContent .= $productorderhtml;
                                  $htmlContent .= "</table>";
                                  $htmlContent .= "<p>Please reply to this e-mail if you have any questions.</p>";

                                  $htmlContent .= "</body></html>";
                                 */
                                $htmlemaildata = $this->load->view('email/customer.php', $html, TRUE);
                                $sendmsg = cMail($postemail, '', $subject, $htmlemaildata);
                                if ($sendmsg) {
                                    $this->common->update('orders', array('send_email' => 1), array('order_id' => $last_id));
                                }
                            }

                            $ordermsg = 'Your order has been received and is being processed. It will be deliver within ' . $daata['shipping']->shipping_time . ' minut. Your order no. ' . $last_id . ' Total amount Rs. ' . number_format($order_total, 2);
                            $message = send_otp($postsmobile, $ordermsg);
                            $this->maintheme('ordercomplete', $daata);
                        }
                        $this->session->unset_userdata(array('cartdata', 'coupon_discount', 'coupon_code', 'coupon_id', 'shipping_charegsajax', 'after_discount_amount', 'after_dicount_total_amount_with_shipping_charges'));
                    } else {
                        $this->session->set_flashdata('error', 'Your Order not placed. Somthing went wrong plz try again');
                        $this->maintheme('product');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Your Order not placed. Please try valid information');
                    $this->maintheme('product');
                }
            }
        } else {
            redirect('home');
        }
    }

    public function logout() {
        $this->get_authorize();
        // user log when user login
        $lourl = base_url() . 'frontend/home/logout';
        $ip = $_SERVER['REMOTE_ADDR'];
        $cust_id = $this->session->userdata('cust_id');
        $cust_name = $this->session->userdata('cust_name');
        $logdata = array(
            'url' => $lourl,
            'subject' => 'Logout',
            'method' => 'Logout',
            'ip' => $ip,
            'type' => 'web',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'userid' => $cust_id,
            'username' => $cust_name,
            'created_date' => date('Y-m-d H:i:s')
        );
        $this->common->insert('customer_log', $logdata);
        // end user activity for the front end
        $session = array('is_front_login', 'cust_id', 'mobile_no', 'cartdata', 'cust_name', 'cust_address', 'cust_locationn', 'address', 'location', 'stat_city', 'cafe_address', 'cafe_id', 'cafe_contact', 'order_type', 'latf', 'longf', 'location', 'kitchen_address');
        $this->session->unset_userdata($session);
        redirect('home');
    }

    // THIS IS USE FOR THE PAYTM PAYMENT GATWAY

    public function checksum($data) {

        $this->load->helper('encdec_paytm_app');
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $checkSum = "";
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramList = array();

        $paramList["MID"] = '';
        $paramList["ORDER_ID"] = '';
        $paramList["CUST_ID"] = '';
        $paramList["INDUSTRY_TYPE_ID"] = '';
        $paramList["CHANNEL_ID"] = '';
        $paramList["TXN_AMOUNT"] = '';
        $paramList["WEBSITE"] = '';

        foreach ($data as $key => $value) {

            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos === false || $pospipe === false) {
                $paramList[$key] = $value;
            }
        }

        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
        return $checkSum;
    }

    // THIS IS USE FOR THE PAYUMONEY PAYMENT GETWAY.
    public function hashCalculate($salt, $input) {
        /* Columns used for hash calculation, Donot add or remove values from $hash_columns array payumoney */
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        /* explode in the array before hashing */
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach ($hashVarsSeq as $hash_var) {
            $hash_string .= isset($input[$hash_var]) ? $input[$hash_var] : '';
            $hash_string .= '|';
        }
        $hash_string .= $salt;
        $hash = strtolower(hash('sha512', $hash_string));
        return $hash;
    }

    public function success_payment() {

        //payment_datetime,payment_method,response_code,response_message,transaction_id,order_id,amount
        // Paytm payment intergration
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        require_once(APPPATH . "libraries/lib/config_paytm.php");
        require_once(APPPATH . "libraries/lib/encdec_paytm.php");

        if (!empty($_POST)) {

            $paytmChecksum = "";
            $paramList = array();
            $isValidChecksum = "FALSE";
            $paramList = $_POST;
            $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
            $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);

            $yes = 0;
            $walletaMount = 0;
            $coupon_discount_amount = 0;


            if ($isValidChecksum) {
                echo "<pre>";
                print_r($_POST);
                die('Under testing');
                $resarray = array(
                    'order_id' => $_POST['ORDERID'],
                    'transaction_id' => $_POST['TXNID'],
                    'amount' => $_POST['TXNAMOUNT'],
                    'payment_datetime' => $_POST['TXNDATE'],
                    'payment_method' => $_POST['PAYMENTMODE'],
                    'response_code' => $_POST['RESPCODE'],
                    'response_message' => $_POST['RESPMSG'],
                    'gateway_name' => $_POST['GATEWAYNAME'],
                    'bank_tnx_id' => $_POST['BANKTXNID'],
                    'bank_name' => $_POST['BANKNAME'],
                    'created_date' => date('Y-m-d H:i:s')
                );
                $lastid = $this->common->insert('payment', $resarray);

                if ($lastid) {
                    if ($_POST['STATUS'] == 'TXN_SUCCESS') {
                        $paymenttype = 'RECEIVED';
                    } else {
                        $paymenttype = 'FAILED';
                        $yes = after_order_update_wallet_remove($_POST['ORDERID'], $this->session->userdata('cust_id'));
                    }
                    $customerde = $this->common->getRow('customers', 'name,mobile,email,fcm_token,device_type', array('id' => $this->session->userdata('cust_id')));
                    $_POST['email'] = $customerde->email;
                    $_POST['phone'] = $customerde->mobile;
                    if (!empty($customerde->mobile)) {

                        if ($_POST['TXNID']) {
                            $ordermsg = ' Your order has been received and your payement status is ' . $paymenttype . ' Payment transtation id ' . $_POST['TXNID'] . '. Your order no. ' . $_POST['ORDERID'] . ' Total amount Rs. ' . number_format($_POST['TXNAMOUNT'], 2);
                        } else {
                            $ordermsg = ' Your order has been ' . $paymenttype . ' but your payement status is ' . $paymenttype . ' Your order no. ' . $_POST['ORDERID'] . ' Total amount Rs. ' . number_format($_POST['TXNAMOUNT'], 2);
                        }

                        $message = send_otp($customerde->mobile, $ordermsg);
                    }

                    if ($yes) {
                        $arrayorders = array(
                            'payment' => $paymenttype,
                            'payment_method' => $_POST['PAYMENTMODE'],
                            'transaction_id' => $_POST['TXNID'],
                            'wallet_amount' => 0,
                            'response_message' => $_POST['RESPMSG']
                        );

                        $walletmsg = ' Your wallet has been added ' . $yes . ' the amount due to ' . $paymenttype;
                        send_otp($_REQUEST['phone'], $walletmsg);
                    } else {
                        $arrayorders = array(
                            'payment' => $paymenttype,
                            'payment_method' => $_POST['PAYMENTMODE'],
                            'transaction_id' => $_POST['TXNID'],
                            'response_message' => $_POST['RESPMSG']
                        );
                    }

                    $this->common->update('orders', $arrayorders, array('order_id' => $_POST['ORDERID']));

                    // user_log 
                    // insert log for all activity
                    $lourl = base_url() . 'frontend/home/order';
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $logdata = array(
                        'url' => $lourl,
                        'subject' => 'Payment by online',
                        'comment' => $_POST['RESPMSG'] . ' # method = ' . $_POST['PAYMENTMODE'] . ' transid = ' . $_POST['TXNID'],
                        'method' => 'Payment of the ORDERID =' . $_POST['ORDERID'],
                        'ip' => $ip,
                        'type' => 'web',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'userid' => $this->session->userdata('cust_id'),
                        'username' => $this->session->userdata('cust_name'),
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $this->common->insert('customer_log', $logdata);
                    // end user activity for the front end
                }
                $address = $this->common->getRow('orders', 'address', array('order_id' => $_POST['ORDERID']));
                if (!empty($address)) {
                    $_POST['address_line_1'] = $address->address;
                }
                $_POST['wallet_amount'] = $walletaMount;
                $data['orderdata'] = $_POST;
                $this->maintheme('completedonline', $data);
            } else {
                $this->maintheme('completedonline');
            }
        } else {
            $this->maintheme('completedonline');
        }
    }

    // If online transation will faild then call this url
    public function failure() {

        if ($_REQUEST) {
            $yes = 0;
            $walletaMount = 0;
            $coupon_discount_amount = 0;
            $status = $_REQUEST["status"];
            $firstname = $_REQUEST["firstname"];
            $amount = $_REQUEST["amount"];
            $txnid = $_REQUEST["txnid"];
            $posted_hash = $_REQUEST["hash"];
            $key = $_REQUEST["key"];
            $productinfo = $_REQUEST["productinfo"];
            $email = $_REQUEST["email"];

            if (isset($_POST["additionalCharges"])) {
                $additionalCharges = $_POST["additionalCharges"];
                $retHashSeq = $additionalCharges . '|' . SALT . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            } else {
                $retHashSeq = SALT . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            }
            $hash = hash("sha512", $retHashSeq);

            if ($hash == $posted_hash) {

                $resarray = array(
                    'order_id' => $_POST['ORDERID'],
                    'transaction_id' => $_POST['TXNID'],
                    'amount' => $_POST['TXNAMOUNT'],
                    'payment_datetime' => $_REQUEST['addedon'],
                    'payment_method' => $_POST['PAYMENTMODE'],
                    'response_code' => $_REQUEST['mihpayid'],
                    'response_message' => $_POST['RESPMSG'],
                    'created_date' => date('Y-m-d H:i:s')
                );
                $lastid = $this->common->insert('payment', $resarray);

                if ($lastid) {
                    if ($_REQUEST['status'] == 'success') {
                        $paymenttype = 'RECEIVED';
                    } else {
                        $paymenttype = 'FAILED';
                        $yes = after_order_update_wallet_remove($_POST['ORDERID'], $this->session->userdata('cust_id'));
                    }

                    if (!empty($_REQUEST['phone'])) {

                        if ($_POST['TXNID']) {
                            $ordermsg = ' Your order has been received and your payement status is ' . $paymenttype . ' Payment transtation id ' . $_POST['TXNID'] . '. Your order no. ' . $_POST['ORDERID'] . ' Total amount Rs. ' . number_format($_POST['TXNAMOUNT'], 2);
                        } else {
                            $ordermsg = ' Your order has been ' . $paymenttype . ' but your payement status is ' . $paymenttype . ' Your order no. ' . $_POST['ORDERID'] . ' Total amount Rs. ' . number_format($_POST['TXNAMOUNT'], 2);
                        }

                        $message = send_otp($_REQUEST['phone'], $ordermsg);
                    }

                    if ($yes) {
                        $arrayorders = array(
                            'payment' => $paymenttype,
                            'payment_method' => $_POST['PAYMENTMODE'],
                            'transaction_id' => $_POST['TXNID'],
                            'wallet_amount' => 0,
                            'response_message' => $_POST['RESPMSG']
                        );

                        $walletmsg = ' Your wallet has been added ' . $yes . ' the amount due to ' . $paymenttype;
                        send_otp($_REQUEST['phone'], $walletmsg);
                    } else {
                        $arrayorders = array(
                            'payment' => $paymenttype,
                            'payment_method' => $_POST['PAYMENTMODE'],
                            'transaction_id' => $_POST['TXNID'],
                            'response_message' => $_POST['RESPMSG']
                        );
                    }

                    $this->common->update('orders', $arrayorders, array('order_id' => $_POST['ORDERID']));

                    // user_log 
                    // insert log for all activity
                    $lourl = base_url() . 'frontend/home/order';
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $logdata = array(
                        'url' => $lourl,
                        'subject' => 'Payment by online',
                        'comment' => $_POST['RESPMSG'] . ' # method = ' . $_POST['PAYMENTMODE'] . ' transid = ' . $_POST['TXNID'],
                        'method' => 'Payment of the ORDERID =' . $_POST['ORDERID'],
                        'ip' => $ip,
                        'type' => 'web',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'userid' => $this->session->userdata('cust_id'),
                        'username' => $this->session->userdata('cust_name'),
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $this->common->insert('customer_log', $logdata);
                    // end user activity for the front end
                }
                $address = $this->common->getRow('orders', 'address', array('order_id' => $_POST['ORDERID']));
                if (!empty($address)) {
                    $_REQUEST['address_line_1'] = $address->address;
                }
                $_REQUEST['wallet_amount'] = $walletaMount;
                $data['orderdata'] = $_REQUEST;

                $this->maintheme('completedonline', $data);
            } else {
                $this->maintheme('completedonline');
            }
        } else {
            $this->maintheme('completedonline');
        }
    }

    // this function is use for the get theme function

    public function maintheme($page = null, $data = null) {

        $this->load->view('frontend/include/header', $data);
        if (!empty($page)) {
            $this->load->view('frontend/' . $page, $data);
        } else {
            $this->load->view('frontend/home', $data);
        }
        $this->load->view('frontend/include/footer');
    }

    public function checkProductPrice($full, $half = null, $checkPrice) {
        $flag = FALSE;
        //if (!empty($half) && !empty($full) && !empty($checkPrice)) {
        if (!empty($full) && !empty($checkPrice)) {
            if ($checkPrice == $full)
                $flag = true;
//            elseif ($checkPrice == $half)
//                $flag = true;
            else
                $flag = FALSE;
        }
        return $flag;
    }

    public function get_authorize($data = null) {
        if ($this->session->userdata('is_front_login')) {
            return true;
        } else {
            if (!empty($data)) {
                $this->session->set_userdata('checkafterlogin', $data);
            }
            return redirect('login');
        }
    }

}

?>