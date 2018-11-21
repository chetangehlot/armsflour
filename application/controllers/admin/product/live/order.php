<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
        $this->load->library('pagination');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index($id = null) {

// pagination code
        $pagination_url = base_url('admin/order');
        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;
        if ($this->input->get('page')) {
            $filter['page'] = (int) $this->input->get('page');
            $url .= 'page=' . $filter['page'] . '&';
        } else {
            $filter['page'] = '';
        }
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " order_id =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
        }
        if (!empty($this->input->get('filter_location'))) {
            $data['filter_location'] = $this->input->get('filter_location');
            $url .= 'filter_location=' . $data['filter_location'] . '&';

            $condition1 .= " location_id = " . $data['filter_location'] . " AND ";
        } else {
            $data['filter_location'] = '';
        }
        if (!empty($this->input->get('filter_status'))) {
            $data['filter_status'] = $this->input->get('filter_status');
            $url .= 'filter_status=' . $data['filter_status'] . '&';
            $condition1 .= " status_id = " . $data['filter_status'] . " AND ";
        } else {
            $data['filter_status'] = '';
        }
        if (!empty($this->input->get('filter_payment'))) {
            $data['filter_payment'] = strtolower($this->input->get('filter_payment'));
            $url .= 'filter_payment=' . $data['filter_payment'] . '&';
            $condition1 .= " payment_type = '" . $data['filter_payment'] . "' AND ";
        } else {
            $data['filter_payment'] = '';
        }
        if (!empty($this->input->get('filter_type'))) {
            $data['filter_type'] = $this->input->get('filter_type');
            $url .= 'filter_type=' . $data['filter_type'] . '&';
//$condition1 .= " order_type = '" . $data['filter_type'] . "' AND ";
            $condition1 .= " pre_order = '" . $data['filter_type'] . "' AND ";
        } else {
            $data['filter_type'] = '';
        }
        if (!empty($this->input->get('from_date'))) {
            $data['from_date'] = $this->input->get('from_date');
            $url .= 'from_date=' . $data['from_date'] . '&';
            $condition1 .= " order_date >= '" . date('Y-m-d ', strtotime($data['from_date'])) . "' AND ";
        } else {
            $data['from_date'] = '';
        }
        if (!empty($this->input->get('to_date'))) {
            $data['to_date'] = $this->input->get('to_date');
            $url .= 'to_date=' . $data['to_date'] . '&';
            $condition1 .= " order_date <= '" . date('Y-m-d ', strtotime($data['to_date'])) . "' AND ";
        } else {
            $data['to_date'] = '';
        }
        $condition1 .= " (( payment_type = 'ONLINE' AND status_id != '5') OR (( payment != 'RECEIVED') AND payment_type = 'cod')) ";
        $total_row1 = $this->common->getRow('orders', 'count(order_id) as total', $condition1);
        $statuscond = array('status_for' => 'order');
        $data['status'] = $this->common->getRows('order_statuses', 'status_id,status_name', $statuscond, array('column' => 'status_id', 'orderby' => 'ASC'));
        $loccondition = " t2.location_id != '0' AND((t2.payment_type = 'online' AND t2.status_id != '5') OR(t2.status_id != '5' AND t2.payment_type = 'cod')) ";
        $data['location'] = $this->common->getTwoJoins('t1.id,t1.name', 'locations as t1', 'orders as t2', 't2.location_id=t1.id', '', $loccondition, array('column' => 't1.name', 'orderby' => 'ASC'), '', 't1.name');
        if (!empty($total_row1)) {
            $total_row = $total_row1->total;

            if ($this->session->userdata('order_new_total') < $total_row) {
                $data['show_popup'] = 1;
                $this->session->set_userdata('order_new_total', $total_row);
            }
        } else {
            $total_row = 0;
        }
        $linkurl = $pagination_url;
        $showpage = 20;
        $data ['links'] = getPagination($total_row, $linkurl);
        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');
        $data['actionurl'] = '';
        if (!empty($filter['page']) AND $filter ['page'] !== 0) {
            $filter['page'] = $filter['page'];
        } else {
            $filter['page'] = 0;
        }
        $data ['order'] = $this->common->getRows('orders as t1', 't1.order_id,t1.name as customer_name,t1.telephone,t1.address,t1.address2,t1.location_id,t1.status_id,t1.payment_type,t1.order_type,t1.order_total,t1.sub_total_amount,t1.cancel_reason,t1.order_time,t1.order_date,t1.date_added,t1.pre_order', $condition1, $order_by, '', array('limit' => $showpage, 'start' => $filter['page']));
        $this->maintheme('list', $data);
    }

    public function completed($id = null) {

// pagination code
        $pagination_url = base_url('admin/order/completed');
        $data['actionurl'] = 'completed';
        $condition1 = "";
        $showpage = 20;
        $url = '?';
        if ($this->input->get('page')) {
            $filter['page'] = (int) $this->input->get('page');
            $url .= 'page=' . $filter['page'] . '&';
        } else {
            $filter['page'] = '';
        }

        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            $condition1 .= " name LIKE '%" . $data['filter_search'] . "%' AND ";
        } else {
            $data['filter_search'] = '';
        }
        if (!empty($this->input->get('filter_location'))) {
            $data['filter_location'] = $this->input->get('filter_location');
            $url .= 'filter_location=' . $data['filter_location'] . '&';

            $condition1 .= " location_id = " . $data['filter_location'] . " AND ";
        } else {
            $data['filter_location'] = '';
        }
        if (!empty($this->input->get('filter_status'))) {

            $data['filter_status'] = $this->input->get('filter_status');
            $url .= 'filter_status=' . $data['filter_status'] . '&';
            $condition1 .= " status_id = " . $data['filter_status'] . " AND ";
        } else {
            $data['filter_status'] = '';
        }
        if (!empty($this->input->get('filter_payment'))) {
            $data['filter_payment'] = strtolower($this->input->get('filter_payment'));
            $url .= 'filter_payment=' . $data['filter_payment'] . '&';
            $condition1 .= " payment_type = '" . $data['filter_payment'] . "' AND ";
        } else {
            $data['filter_payment'] = '';
        }
        if (!empty($this->input->get('filter_type'))) {
            $data['filter_type'] = $this->input->get('filter_type');
            $url .= 'filter_type=' . $data['filter_type'] . '&';
//$condition1 .= " order_type = '" . $data['filter_type'] . "' AND ";
            $condition1 .= " pre_order = '" . $data['filter_type'] . "' AND ";
        } else {
            $data['filter_type'] = '';
        }
        if (!empty($this->input->get('from_date'))) {
            $data['from_date'] = $this->input->get('from_date');
            $url .= 'from_date=' . $data['from_date'] . '&';
            $condition1 .= " order_date >= '" . date('Y-m-d ', strtotime($data['from_date'])) . "' AND ";
        } else {
            $data['from_date'] = '';
        }
        if (!empty($this->input->get('to_date'))) {
            $data['to_date'] = $this->input->get('to_date');
            $url .= 'to_date=' . $data['to_date'] . '&';
            $condition1 .= " order_date <= '" . date('Y-m-d ', strtotime($data['to_date'])) . "' AND ";
        } else {
            $data['to_date'] = '';
        }

        $condition1 .= " payment = 'RECEIVED' AND status_id = 5 ";
        $total_row1 = $this->common->getRow('orders', 'count(order_id) as total', $condition1);
        if (!empty($total_row1)) {
            $total_row = $total_row1->total;
        } else {
            $total_row = 0;
        }

        $data['location'] = $this->common->getTwoJoins('t1.id,t1.name', 'locations as t1', 'orders as t2', 't2.location_id=t1.id', '', array('t2.location_id !=' => '0', 't2.payment=' => 'RECEIVED', 't2.status_id' => 5), array('column' => 't1.name', 'orderby' => 'ASC'), '', 't1.name');

        $data ['links'] = getPagination($total_row, $pagination_url);

        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');

        $data ['order'] = $this->common->getRows('orders as t1', 't1.order_id,t1.name as customer_name,t1.telephone,t1.address,t1.address2,t1.location_id,t1.status_id,t1.payment_type,t1.order_type,t1.order_total,t1.sub_total_amount,t1.order_time,t1.order_date,t1.date_added,t1.pre_order', $condition1, $order_by, '', array('limit' => $showpage, 'start' => $filter['page']));
        $this->maintheme('list', $data);
    }

    public function edit($id = null) {
        $this->load->model('admin/OrderModel');
        if ($id) {
            $ids = base64_decode($id);
            $data['orderdetails'] = $this->common->getRow('orders', '*', array('order_id' => $ids));
            $data['order_product'] = $this->common->getTwoJoins('t1.order_product_id,t1.product_id,t1.name,t1.quantity,t1.price,t1.subtotal,t1.is_nonveg,t1.attr_id,t1.attr_name,t2.description', 'order_products as t1', 'product as t2', 't1.product_id=t2.pid', '', array('t1.order_id' => $ids));
            $data['order_status'] = $this->common->getRows('order_statuses', 'status_id,status_name,status_comment');
            $data['staff'] = $this->common->getRows('staff', 'name,id', array('is_active' => '1'), array('column' => 'created_date', 'orderby' => 'DESC'));
            $data['status_history'] = $this->OrderModel->get_order_history($ids);
            $data['coupons_history'] = $this->common->getRow('coupons_history', 'code,amount,date_used', array('order_id' => $ids));

            if ($this->input->post()) {

                $customerid = $data['orderdetails']->customer_id;
                $orderstatus = $this->input->post('order_status');
                $status_notify = $this->input->post('status_notify');
                $status_comment = $this->input->post('status_comment');
                $staffassign = $this->input->post('staffassign') != 0 ? $this->input->post('staffassign') : 0;
                $orderid = base64_decode($this->input->post('hdnoi'));
                $totalpayment = '';
                $totalbycustomer = 0;
                $wallet_update = 0;
                $walletAmount = 0;
                $paymentstatus = 0;
                $ordercompleted = 0;
                $fcmid = '';
                if ($ids === $orderid) {

// Calculation for the Amount Payment by the Customer if COD type
                    if ($this->input->post('payment_cod')) {
                        $totalpayment = base64_decode($this->input->post('totalamount'));
                        $totalbycustomer = $this->input->post('payment_cod');
                        if (($totalbycustomer >= $totalpayment)) {
                            $paymentstatus = 1;
                            $walletAmount = $totalbycustomer - $totalpayment;
                        } else {
                            $this->session->set_flashdata('error', 'The Customer Amount should be Equal or greater then from ' . $totalpayment);
                            redirect('admin/order/edit/' . $id);
                        }
                    }

                    $date = date('Y-m-d H:i:s');
                    $array = array(
                        'order_id' => $orderid,
                        'admin_id' => $this->session->userdata('admin_id'),
                        'status_id' => $orderstatus,
                        'sttaf_assignee_id' => $staffassign,
                        'notify' => $status_notify,
                        'comment' => $status_comment,
                        'date_added' => $date
                    );

                    $orderhistory = $this->common->insert('order_status_history', $array);

                    if ($orderhistory) {
                        $custmerdetails = $this->common->getRow('customers', 'fcm_token,device_type', array('id' => $customerid));
                        if (!empty($custmerdetails)) {
                            $fcmid = $custmerdetails->fcm_token;
                        }

                        $orderstatus123 = $this->common->getRow('order_statuses', 'status_name,status_comment', array('status_id' => $orderstatus));
                        if (!empty($orderstatus123)) {
                            if ($orderstatus123->status_name == 'Completed' && $orderstatus123->status_comment == $status_comment) {

                                if ($paymentstatus == 1) {
                                    $ordercompleted = 1;
                                    $msg = 'Payment has been updated';
                                    $arrayorders = array(
                                        'status_id' => $orderstatus,
                                        'sttaf_assignee_id' => $staffassign,
                                        'invoice_no' => '1',
                                        'payment' => 'RECEIVED',
                                        'payment_method' => 'CASH',
                                        'date_modified' => $date
                                    );
                                } else {
                                    $msg = 'Order completed!';
                                    if ($data['orderdetails']->wallet_amount == $data['orderdetails']->order_total) {
                                        $ordercompleted = 1;
                                        $arrayorders = array(
                                            'status_id' => $orderstatus,
                                            'sttaf_assignee_id' => $staffassign,
                                            'invoice_no' => '1',
                                            'payment' => 'RECEIVED',
                                            'payment_method' => 'Paid Via Wallet',
                                            'date_modified' => $date
                                        );
                                    } else {

                                        $arrayorders = array(
                                            'status_id' => $orderstatus,
                                            'sttaf_assignee_id' => $staffassign,
                                            'invoice_no' => '1',
                                            'date_modified' => $date
                                        );
                                    }
                                }
                            } elseif ($orderstatus123->status_name == 'Canceled' && $orderstatus123->status_comment == $status_comment) {

                                $msg = 'Order Canceled!';
                                $wallet_update = 1;
                                $arrayorders = array(
                                    'status_id' => $orderstatus,
                                    'sttaf_assignee_id' => $staffassign,
                                    'invoice_no' => '0',
                                    'payment' => 'CANCELED',
                                    'wallet_amount' => 0,
                                    'date_modified' => $date
                                );
                            } else {
                                $msg = 'Order updated!';
                                $arrayorders = array(
                                    'status_id' => $orderstatus,
                                    'sttaf_assignee_id' => $staffassign,
                                    'invoice_no' => '0',
                                    'date_modified' => $date
                                );
                            }
                        } else {
                            $msg = 'Order updated!';
                            $arrayorders = array(
                                'status_id' => $orderstatus,
                                'sttaf_assignee_id' => $staffassign,
                                'invoice_no' => '0',
                                'date_modified' => $date
                            );
                        }
                        $lastupdate = $this->common->update('orders', $arrayorders, array('order_id' => $orderid));

                        if ($lastupdate) {
                            $wupdate = 0;
                            if ($paymentstatus == 1 && $walletAmount != 0) {

                                $msgwls = 'Due to extra pay by customer.';
                                getUpdateWallet($walletAmount, $data['orderdetails']->order_id, $data['orderdetails']->telephone, $customerid, $msgwls, $type = 'payment', $fcmid);
                            }
// IF THE ORDER WILL CANCELED BY THE ADMIN OR KITHCHEN THEN WALLET AMOUNT RESET ON HIS/HER ACCOUNT
                            if ($wallet_update == 1 && $data['orderdetails']->wallet_amount > 0) {
                                $msgwls = 'Due to order cancelled and the wallet amount has been refunded.';
                                getUpdateWallet($data['orderdetails']->wallet_amount, $data['orderdetails']->order_id, $data['orderdetails']->telephone, $customerid, $msgwls, '', $fcmid);
                            }
                            $this->session->set_flashdata('success', $msg);
                            if ($status_notify == 1) {
                                $msgs = $status_comment . ' Thanks for the order.';
                                $mobile = $data ['orderdetails']->telephone;
                                if (!empty($mobile)) {
                                    send_otp($mobile, $msgs);
                                }
                            }
                            if (($data['orderdetails']->sttaf_assignee_id != $staffassign) && $staffassign > 0) {

                                $this->setAssignOrderToStaff($data['orderdetails']->order_id, $staffassign);
                            }

                            if ($ordercompleted == 1) {
                                $loyality = $this->common->getRow('loyalty', 'point,point_rate,min_amount', array('is_active' => 1));
                                if (!empty($loyality)) {

                                    if (($loyality->min_amount <= $data['orderdetails']->order_total) || ($loyality->min_amount == 0)) {
                                        $loyaltypoint = setLoyaltyPoint($data['orderdetails']->order_total, $orderid, $data['orderdetails']->telephone, $customerid, $loyality, 'web', '', $fcmid);
                                    }
                                }
                            }
                        } else {
                            $this->session->set_flashdata('error', ' Order Not Updated!');
                        }
                        redirect('admin/order/edit/' . $id);
                    }
                }
            }
            $data['return_url'] = base_url('admin/order');
            $this->maintheme('add', $data);
        }
    }

    function setAssignOrderToStaff($orderid = null, $getstaffid = null) {
        if (!empty($orderid) && !empty($getstaffid)) {
            $staff = $this->common->getRow('staff', 'contact_no,name,fcm_token', array('id' => $getstaffid));
            if (!empty($staff)) {
                if ($staff->contact_no) {
                    $msgs = 'You have assign new order, the order id is ' . $orderid;
                    send_otp($staff->contact_no, $msgs);
                }
                if ($staff->fcm_token) {
                    $msgs = 'You have assign new order, the order id is ' . $orderid;
                    //pushNotify($staff->fcm_token, 'FOC24 Order assign', $msgs);
                }
            }
        }
    }

    public function invoice($orderid = null) {

        $this->load->model('admin/OrderModel');

        if ($orderid) {

            $ids = base64_decode($orderid);
            $data ['orderdetails'] = $this->common->getRow('orders', 'order_id,order_type,address,location_id,city,name,telephone,email,order_date,order_time,payment_type,payment,dicount_amount,cart,order_total,sub_total_amount,wallet_amount,order_from', array('order_id' => $ids));
//    $data  ['order_product'] =   $this->common->getRows('order_products', '*', array('order_id' => $ids));
            $data ['order_product'] = $this->common->getTwoJoins('t1.order_product_id,t1.product_id,t1.name,t1.quantity,t1.price,t1.subtotal,t1.is_nonveg,t1.attr_id,t1.attr_name,t2.description', 'order_products as t1', 'product as t2', 't1.product_id=t2.pid', '', array('t1.order_id' => $ids));
            $data['order_status'] = $this->common->getRows('order_statuses', 'status_id,status_name,status_comment');
            $data ['status_history'] = $this->OrderModel->get_order_history($ids);
            $data['coupons_history'] = $this->common->getRow('coupons_history', 'code,amount,date_used', array('order_id' => $ids));

            $this->maintheme('invoice', $data);
        }
    }

    function createpdf($orderid = null) {

        $this->load->model('admin/OrderModel');

        if ($orderid) {

            $ids = base64_decode($orderid);
            $data['orderdetails'] = $this->common->getRow('orders', '*', array('order_id' => $ids));

//$data['order_product'] = $this->common->getRows('order_products', '*', array('order_id' => $ids));

            $data['order_product'] = $this->common->getTwoJoins('t1.product_id,t1.name,t1.quantity,t1.price,t1.subtotal,t1.option_values,t2.description', 'order_products as t1', 'product as t2', 't1.product_id=t2.pid', '', array('t1.order_id' => $ids));

            $data['order_status'] = $this->common->getRows('order_statuses', 'status_id,status_name,status_comment');
            $data['status_history'] = $this->OrderModel->get_order_history($ids);
            $data['coupons_history'] = $this->common->getRow('coupons_history', 'code,amount,date_used', array('order_id' => $ids));


            $datetime = date('dmyh-i-s');
            $filename = time() . "invoice_order_" . $datetime . ".pdf";

            $html = $this->load->view('admin/order/invoice', $data, true);

            $this->load->library('M_pdf');
            $this->m_pdf->pdf->WriteHTML($html);
            $path_file = FCPATH . 'assets/uploads/' . $filename;
// $path_file = base_url() . 'assets/uploads/' . $filename;
            $this->m_pdf->pdf->Output($path_file, "F");
        }
    }

// this function is use for the get theme function
    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/order/' . $page, $data);
        }
        $this->load->view('admin/include/footer', $data);
    }

    public function get_status_message() {

        if ($this->input->post('statusorder')) {
            $comments = $this->common->getRow('order_statuses', 'status_comment', array('status_id' => $this->input->post('statusorder')));
            if (!empty($comments)) {
                echo $comments->status_comment;
            } else {
                echo FALSE;
            }
        }
    }

    public function get_status_cancel() {

        if ($this->input->post()) {
            $orderid = $this->input->post('orderid');
            $order_status = $this->input->post('order_status');
            $comment_reason = $this->input->post('comment_reason');
            $statusid = $this->input->post('statusid');
            $status_comment = $this->input->post('status_comment');
            $checkstatus = $this->common->getTwoJoins('t1.status_id,t1.telephone', 'orders as t1', 'order_statuses as t2', 't1.status_id=t2.status_id', '', array('t1.order_id' => $orderid), '', 1);
            if (!empty($checkstatus)) {
                $curent_status = $checkstatus->status_id;
                $mobile = $checkstatus->telephone;

                if ($orderid && $comment_reason && $statusid && $statusid == 6) {
                    $datarry = array(
                        'status_id' => $statusid,
                        'cancel_reason' => $comment_reason
                    );
                    $updateorder = $this->common->update('orders', $datarry, array('order_id' => $orderid));
                    if ($updateorder) {
                        $date = date('Y-m-d H:i:s');
                        $array = array(
                            'order_id' => $orderid,
                            'admin_id' => $this->session->userdata('admin_id'),
                            'status_id' => $statusid,
                            'notify' => 1,
                            'comment' => $status_comment,
                            'date_added' => $date
                        );
                        $orderhistory = $this->common->insert('order_status_history', $array);

                        $msgs = $comment_reason . ' Thanks for the order.';
                        if (!empty($mobile)) {
                            send_otp($mobile, $msgs);
                        }
                        echo true;
                    }
                } else {
                    echo FALSE;
                }
            } else {
                echo FALSE;
            }
        }
    }

    public function set_status_message() {

        if ($this->input->post('orderid')) {
            $orderid = $this->input->post('orderid');
            $status_update_message = $this->common->getTwoJoins('t1.status_id,t1.customer_id,t2.status_comment,t1.telephone', 'orders as t1', 'order_statuses as t2', 't1.status_id=t2.status_id', '', array('t1.order_id' => $orderid), '', 1);
            $statuscomment = '';
            if (!empty($status_update_message)) {
                $mobile = $status_update_message->telephone;
                $stutusid = $status_update_message->status_id;
                if ($stutusid == 1) {
                    $stutusid = 3;
                    $comments = $this->common->getRow('order_statuses', 'status_comment', array('status_id' => $stutusid));

                    if (!empty($comments)) {
                        $statuscomment = $comments->status_comment;
                        $updateorder = $this->common->update('orders', array('status_id' => $stutusid), array('order_id' => $orderid));

                        if ($updateorder) {
                            $date = date('Y-m-d H:i:s');
                            $array = array(
                                'order_id' => $orderid,
                                'admin_id' => $this->session->userdata('admin_id'),
                                'status_id' => $stutusid,
                                'notify' => 1,
                                'comment' => $statuscomment,
                                'date_added' => $date
                            );
                            $orderhistory = $this->common->insert('order_status_history', $array);
                        }
                        $msgs = $statuscomment . ' Thanks for the order.';
                        if (!empty($mobile)) {
                            send_otp($mobile, $msgs);
                        }
                        echo $statuscomment;
                    }
                }
            } else {
                echo FALSE;
            }
        } else {
            echo false;
        }
    }

}

?>