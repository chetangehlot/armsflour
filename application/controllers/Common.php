<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('frontend/homeModel');
    }

    public function getLocations() {

        $this->load->model('frontend/homeModel');

        if ($this->input->post()) {
            $string = '';
            $name = $this->input->post('query');
            $cityid = $this->input->post('cityid');
//$city_id = $this->input->post('city_id');
            if ((!empty($name)) && (!empty($cityid))) {
                $condiont = array('city_id' => $cityid);
                $resulst = $this->homeModel->getLocations('locations', $name, $condiont);
                if (!empty($resulst)) {
                    foreach ($resulst as $res) {
                        $string[] = ucfirst($res['name']);
                    }
                    echo json_encode($string);
                } else {
                    echo json_encode($string);
                }
            } else {
                echo json_encode($string);
            }
        }
    }

    public function getkitchens() {

        if ($this->input->post()) {
            $name = $this->input->post('query');
            $cityid = $this->input->post('cityid');
            $string = '';
            if (!empty($cityid)) {
                $this->load->model('frontend/homeModel');
                $resulst = $this->homeModel->getLocations('kitchen', $name, array('city_id' => $cityid));

                if (!empty($resulst)) {
                    foreach ($resulst as $res) {
                        $string[] = $res['name'];
                    }
                    echo json_encode($string);
                } else {
//$string = 'No Records Found';
                    echo json_encode($string);
                }
            } else {
// $string = 'No Records Found';
                echo json_encode($string);
            }
        }
    }

    public function GetCouponinfo() {

        $this->load->model('frontend/homeModel');

        if ($this->input->post()) {
            $msg = 'This coupon code is invalid or has expired.';
            $this->session->unset_userdata(array('coupon_discount', 'coupon_code', 'coupon_id', 'shipping_charegsajax', 'after_discount_amount', 'after_dicount_total_amount_with_shipping_charges'));

            $string = FALSE;
            $discount_available = 0;
            $total_discount_coupon = 0;
            $html = 0;
            $couponcode = $this->input->post('couponcode');
            $removitem = $this->input->post('rm');
            $sumtotal = base64_decode($this->input->post('hdntata'));
            if ((!empty($couponcode)) && (!empty($sumtotal))) {
                $sessiontotalamount = $_SESSION['cartdata']['total_amount']['subtotal'];
                if ($sessiontotalamount == $sumtotal) {
                    if ($removitem != 1) {
                        $condiont = array('coupon_code' => $couponcode);
                        $resulst = $this->homeModel->getRow('coupon', '*', $condiont);
                        if (!empty($resulst)) {
                            $applytype = $resulst->apply_for;
                            $couponid = $resulst->id;

                            switch ($applytype) {
                                case "PRODUCT":
                                    $prodcond = array('coupon_id' => $couponid);
                                    $resulstpro = $this->homeModel->getRows('coupon_of_customer_product', 'product_id', $prodcond);
                                    if (!empty($resulstpro)) {
                                        $cartproduct = $_SESSION['cartdata']['cart'];
                                        foreach ($resulstpro as $pro) {
                                            if ($pro['product_id']) {
                                                if (array_search($pro['product_id'], array_column($cartproduct, 'item_id')) !== False) {
                                                    $discount_available = 1;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case "CUSTOMER":
                                    $current_customerid = $this->session->userdata('cust_id');
                                    $condcust = array('coupon_id' => $couponid, 'customer_id' => $current_customerid);
                                    $resulstcut = $this->homeModel->getRows('coupon_of_customer_product', 'customer_id', $condcust);

                                    if (!empty($resulstcut)) {
                                        $discount_available = 1;
                                    }
                                    break;
                                case "ALL":
                                    $discount_available = 1;

                                    break;
                            }

                            if ($discount_available != 0) {

                                if ($resulst->minimum_price <= $sumtotal) {
                                    if ($resulst->discount_type == 1) {
                                        $total_discount_coupon = ($sumtotal / 100) * $resulst->discount;
                                        if ($total_discount_coupon > $resulst->max_discount) {
                                            $total_discount_coupon = $resulst->max_discount;
                                        }
                                    } elseif ($resulst->discount_type == 2) {
                                        $total_discount_coupon = $resulst->discount;
                                    }
                                } else {
                                    $msg = 'Inavlid Coupon code! Total Amount should be greater then ' . $resulst->minimum_price;
                                }
                            } else {
                                $html = 1;
                            }
                        }
                        if (!empty($total_discount_coupon)) {
                            $this->session->set_userdata('coupon_discount', $total_discount_coupon);
                            $this->session->set_userdata('coupon_code', $couponcode);
                            $this->session->set_userdata('coupon_id', $couponid);
                            $msg = strtoupper($couponcode) . ' has been applied';
                            $html = $this->createCarttotaldiscount($total_discount_coupon, $msg);
                        } else {
                            $html = 1;
                        }
                    } else {
                        $html = 1;
                        $msg = 'Coupon has been removed!';
                    }
                } else {
                    $html = 1;
                }
            } else {
                $html = 1;
            }
        } else {
            $html = 1;
        }

        if (is_string($html)) {
            echo $html;
        } else {

            $html = $this->getOrignalcart($msg);
            echo $html;
        }
    }

    public function getCoupon() {

        if ($this->input->post() && $this->session->userdata('cust_id')) {

            $msg = 'This coupon code is invalid or has expired.';
            $this->session->unset_userdata(array('coupon_discount', 'coupon_code', 'coupon_id', 'shipping_charegsajax', 'after_discount_amount', 'after_dicount_total_amount_with_shipping_charges'));
            $string = FALSE;
            $discount_available = 0;
            $total_discount_coupon = 0;
            $discount_coupon_price = 0;
            $discount_max_discount = 0;
            $total_discount_coupon = 0;
            $discount_type = 0;
            $html = 0;
            $flag = FALSE;
            $redumstion_continue = TRUE;
            $couponcode = $this->input->post('couponcode');
            $removitem = $this->input->post('rm');
            $redem_by_user = 0;
            $sumtotal = base64_decode($this->input->post('hdntata'));

            if ((!empty($couponcode)) && (!empty($sumtotal))) {

                $sessiontotalamount = $_SESSION['cartdata']['total_amount']['subtotal'];

                if ($sessiontotalamount == $sumtotal) {

                    if ($removitem != 1) {

                        $condiont = array('coupon_code' => $couponcode);
                        $resulst = $this->homeModel->getRow('coupon', '*', $condiont);

                        if (!empty($resulst)) {

                            $applytype = $resulst->apply_for;
                            $couponid = $resulst->id;
                            $redemptions = $resulst->redemptions;
                            $discount_coupon_price = $resulst->discount;
                            $discount_max_discount = $resulst->max_discount;
                            $discount_type = $resulst->discount_type;
                            $start_date = date('Y-m-d', strtotime($resulst->start_date));
                            $expire_date = date('Y-m-d', strtotime($resulst->expire_date));
                            $current_date = date('Y-m-d');


                            if ($current_date >= $start_date) {

                                if ($current_date <= $expire_date) {

                                    if ($redemptions > 0) {

                                        $usercouponhistory = $this->homeModel->getRow('coupons_history', 'count(coupon_history_id) as total', array('coupon_id' => $couponid, 'customer_id' => $this->session->userdata('cust_id')));

                                        if (!empty($usercouponhistory)) {

                                            $redem_by_user = $usercouponhistory->total;

                                            if ($redemptions > $redem_by_user) {
                                                $redumstion_continue = TRUE;
                                            } else {
                                                $redumstion_continue = FALSE;
                                                $msg = 'Already used this coupon! User can use only ' . $redemptions . ' time!';
                                            }
                                        }
                                    }

                                    if ($redumstion_continue) {
                                        switch ($applytype) {
                                            case "PRODUCT":
                                                $prodcond = array('coupon_id' => $couponid);
                                                $resulstpro = $this->homeModel->getRows('coupon_of_customer_product', 'product_id', $prodcond);

                                                if (!empty($resulstpro)) {
                                                    $cartproduct = $_SESSION['cartdata']['cart'];
                                                    foreach ($resulstpro as $pro) {
                                                        if ($pro['product_id']) {
                                                            if (array_search($pro['product_id'], array_column($cartproduct, 'item_id')) !== False) {
                                                                $discount_available = 1;
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $msg = 'Invalid coupon! This coupon is specific product only!';
                                                }
                                                if ($discount_available == 0) {
                                                    $msg = 'Invalid coupon! This coupon is specific product only!';
                                                }
                                                break;
                                            case "CUSTOMER":

                                                $current_customerid = $this->session->userdata('cust_id');
                                                $condcust = array('coupon_id' => $couponid, 'customer_id' => $current_customerid);
                                                $resulstcut = $this->homeModel->getRows('coupon_of_customer_product', 'customer_id', $condcust);

                                                if (!empty($resulstcut)) {
                                                    $discount_available = 1;
                                                } else {
                                                    $msg = 'Invalid coupon! This coupon is specific user only';
                                                }
                                                break;
                                            case "ALL":
                                                $discount_available = 1;
                                                break;
                                        }
                                        if ($discount_available != 0) {
                                            if ($resulst->minimum_price <= $sumtotal) {
                                                if ($resulst->discount_type == 1) {
                                                    $total_discount_coupon = ($sumtotal / 100) * $resulst->discount;
                                                    if ($total_discount_coupon > $resulst->max_discount) {
                                                        $total_discount_coupon = $resulst->max_discount;
                                                    }
                                                } elseif ($resulst->discount_type == 2) {
                                                    $total_discount_coupon = $resulst->discount;
                                                }
                                            } else {
                                                $msg = 'Inavlid Coupon code! Total Amount should be greater then ' . $resulst->minimum_price;
                                                $flag = FALSE;
                                            }
                                        } else {
                                            $flag = FALSE;
                                        }
                                    } else {
                                        $flag = FALSE;
                                    }
                                } else {
                                    $flag = FALSE;
                                    $msg = 'This coupon code has been expired!';
                                }
                            } else {
                                $flag = FALSE;
                                $msg = 'This coupon code is comming soon!';
                            }
                        } else {
                            $flag = FALSE;
                            $msg = 'This coupon code is invalid';
                        }

                        if (!empty($total_discount_coupon)) {
                            $this->session->set_userdata('coupon_discount', $total_discount_coupon);
                            $this->session->set_userdata('coupon_code', $couponcode);
                            $this->session->set_userdata('coupon_id', $couponid);
                            if ($discount_type == 1) {
                                $msg = 'Voila! ' . strtoupper($couponcode) . ' has been applied successfully. Proceed to pay now to get ' . $discount_coupon_price . '% cashback upto Rs. ' . $discount_max_discount;
                            } else {
                                $msg = 'Voila! ' . strtoupper($couponcode) . ' has been applied successfully. Proceed to pay now to get cashback Rs. ' . $total_discount_coupon;
                            }

                            $flag = TRUE;
// $html = $this->createCarttotaldiscount($total_discount_coupon, $msg);
                        }
                    } else {
// for the remove coupon code functionality
                        $msg = 'Coupon has been removed!';
                    }
                } else {
                    $flag = FALSE;
                    $msg = 'Amount incorrect!';
                }
            } else {
                $flag = FALSE;
                $msg = 'This coupon code is invalid or has expired!';
            }
        } else {
            $flag = FALSE;
            $msg = 'No Coupon code!';
        }
        $result = array('flag' => $flag, 'msg' => $msg);
        echo json_encode($result);
    }

    public function GetOtpForUser() {
        $result = 0;
        $this->load->model('frontend/homeModel');
        if ($this->input->post()) {
            $mobile = $this->input->post('number123');
            if (preg_match('/(7|8|9)\d{9}/', $mobile)) {
                $string = rand(1000, 9999);
                $_REQUEST['uid'] = '7987050092';
                $_REQUEST['pwd'] = 'firoj123';
                $_REQUEST['phone'] = $mobile;
                $_REQUEST['msg'] = 'Welcome to O2 Cafe. Use OTP ' . $string . ' to the order food ';
                $res = send_otp($mobile, $_REQUEST['msg']);
                if ($res) {
                    $getExist = $this->homeModel->getRow('customers', 'id', array('mobile' => $mobile));
                    if (!empty($getExist)) {
                        $dataarray = array('otp' => $string);
                        $this->homeModel->update('customers', $dataarray, array('mobile' => $mobile));
                    } else {
                        $createddate = date('y-m-d H:i:s');
                        $dataarray = array('mobile' => $mobile, 'otp' => $string, 'created_date' => $createddate);
                        $last_id = $this->homeModel->insert('customers', $dataarray);
                        if ($last_id) {
// user_log 
// insert log for all activity
                            $lourl = base_url() . 'frontend/ajax/common//GetOtpForUser';
                            $ip = $_SERVER['REMOTE_ADDR'];
                            $logdata = array(
                                'url' => $lourl,
                                'subject' => 'Register',
                                'method' => 'New Customer',
                                'ip' => $ip,
                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                'userid' => $last_id,
                                'username' => '',
                                'type' => 'web',
                                'created_date' => $createddate
                            );
                            $this->homeModel->insert('customer_log', $logdata);
// end user activity for the front end
                        }
                    }
                    $result = 1;
                } else {
                    $result = 0;
                }
            } else {
                $result = 0;
            }
            echo $result;
        }
    }

    public function createCarttotaldiscount($total_discount_coupon = null, $msg = null) {

        if ($this->session->userdata('cartdata') != '' && (!empty($total_discount_coupon))) {
            $texttaxtax = '';
            $taxamount = 0;
            $shipping_charges = 0;
            $shippingtext = '<b>Free</b> shipping';
            $shippingtime = '60 Minut';
            $shippingfreemsg = '';
            $shipping_time = '';
            $cartdata = $_SESSION['cartdata']['cart'];
            $carttax = $_SESSION['cartdata']['tax'];
            $cartshipping = $_SESSION['cartdata']['shipping'];
            $subtotal = $_SESSION['cartdata']['total_amount']['subtotal'];
            $after_discount_amount = $subtotal - $total_discount_coupon;
// Tax functionality

            if (!empty($carttax)) {

                $texttax = '<div class="col-xs-8 clearfix table-col" style="padding:6px;">GST <br>';
                $i = 1;
                $comm = ',';
                $sum = 0;
                $texttax .= '(';
                foreach ($carttax as $taxes) {
                    $percent = 0;
                    $percent = ($after_discount_amount / 100) * $taxes['percent'];
                    $sum += number_format($percent, 2);
                    $texttax .= ($i != 1) ? $comm : '';
                    $texttax .= strtoupper($taxes['tax_name']) . '=' . number_format($percent, 2);
                    $i++;
                }
                $texttax .= ')';

                $texttax .= '<span style="font-size:11px;" </span> </div>';

                $texttax .= '<div class="col-xs-4 clearfix text_red table-col"  style="padding:6px;text-align:right;">';
                $texttax .= '<i class="fa fa-inr text_red"></i> ';

                if (!empty($sum) && !empty($after_discount_amount)) {
                    $taxamount = $sum;
                }
                $texttax .= number_format($taxamount, 2);

                $texttax .= '</div>';
            }
// Amount with gst
            $amountpay_with_gst = $after_discount_amount + $taxamount;

// shipping functionality
            if (!empty($cartshipping)) {
                $shipping_min_charges = $cartshipping->min_price;

                $shipping_time = $cartshipping->shipping_time;

                if ($amountpay_with_gst < $shipping_min_charges) {
                    $shipping_charges = $cartshipping->charge;
                    $shippingtext = '<i class="fa fa-inr text_red"></i> ' . $shipping_charges;
                    $shippingfreemsg = 'if purchase more then 120 shipping charge will be 0';
                } else {
                    $shippingtext = '<b>Free</b> shipping';
                    $shippingfreemsg = '';
                }

                if (!empty($shipping_time)) {
                    $shippingtime = $shipping_time . ' Minut';
                }
            }

            $total_amount_with_shipping_charges = $amountpay_with_gst + $shipping_charges;


            $this->session->set_userdata('after_discount_amount', $after_discount_amount);
            $this->session->set_userdata('shipping_charegsajax', $shipping_charges);
            $this->session->set_userdata('after_dicount_total_amount_with_shipping_charges', $total_amount_with_shipping_charges);


            $html = <<<HERE

              <div class = "cart-total clearfix samcolorcart">
              <h3 class = "cart-total-title" style = "padding:6px;">Your Order</h3>
            <div id='error-alert' style="display: none;" class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                       ×</button>
                   <span id="errormsgcoupon">Coupon should not be an empty</span>
               </div>
               <div id='error-update-alert' style="display: none;" class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                       ×</button>
                   <span id="errormsgcoupon">This coupon code is invalid or has expired.</span>
               </div>
              <div class = "alert alert-success"> <button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">
              ×</button>
              <span id = "errormsgcoupon"></span>{$msg}
              </div>
              <div class = "col-xs-8 clearfix table-col" style = "padding:8px;">
              Item Net Amount
              </div>
              <div class = "col-xs-4 clearfix text_red table-col" style = "padding:6px;text-align:right;">
              <i class = "fa fa-inr text_red"></i> {$subtotal}
              </div>
              <div class = "col-xs-8 clearfix table-col" style = "padding: 8px;color: green;font-weight: 700;">
              Coupon Discount    <label id='removeitem' style="color:red;cursor:pointer;" class="btncoupan"><i class="fa fa-trash-o"></i></label>
              </div>
              <div class = "col-xs-4 clearfix text_red table-col" style = "padding:6px;text-align:right;color: green;">
               
              <i class = "fa fa-inr text_red"></i> - {$total_discount_coupon}
              </div>
              <div class = "col-xs-8 clearfix table-col" style = "padding:8px;">
              Amount After Discount
              </div>
              <div class = "col-xs-4 clearfix text_red table-col" style = "padding:6px;text-align:right;">
              <i class = "fa fa-inr text_red"></i> {$after_discount_amount}
              </div>
              {$texttax}

              <div id="netamountgst">
              <div class="col-xs-8 clearfix table-col" style="padding:6px;">
              Net Amount <span style="font-size:11px;"> (Incl. of GST)</span>
              </div>
              <div class="col-xs-4 clearfix text_red table-col"  style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i>
              {$amountpay_with_gst}
              </div>
              </div>

              <div class="cart-divider"></div>
              <span class="clearfix"></span>
              <!-- <div class="col-xs-8 clearfix table-col" id="divroundoff" style="padding:6px;">
              Wallet Amount
              </div>
              <div class="col-xs-4 clearfix text_red table-col"  id="divroundoffamount" style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i> (-) 0.00
              </div> -->

              <hr />
              <span class="clearfix"></span>
              <div class="shippingid">
              <div class="col-xs-6 clearfix table-col" style="padding:6px;">
              Shipping charge
              </div>
              <div class="col-xs-6 clearfix table-col" style="padding:6px;text-align:right;">
              <span class="text_red" id="spanshiiping">
              {$shippingtext}
              </span>
              </div>
              </div>
              <div id="totaldueamount">
              <div class="col-xs-8 clearfix table-col" id="divroundoff" style="padding:6px;">
              Total Due Amount
              </div>
              <div class="col-xs-4 clearfix text_red table-col fs-20"  id="divroundoffamount" style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i> {$total_amount_with_shipping_charges}
              </div>
              </div>
              <span class="clearfix"></span>
              <hr/>
              <div class="col-xs-6 clearfix table-col" style="padding:6px;">
              Shipping Time
              </div>
              <div class="col-xs-6 clearfix table-col"  style="padding:6px;text-align:right;">
              <span class="text_red"></span>
              {$shippingtime}
              </div>
              <span class="clearfix"></span>
              <hr>
              <div class="col-xs-12 ">
              <label>{$shippingfreemsg}</label>
              </div>
              <?php }
              ?>
              </div>
HERE;
            return $html;
        }
    }

    public function getOrignalcart($msg = null) {

        if ($this->session->userdata('cartdata') != '') {
            $texttaxtax = '';
            $taxamount = 0;
            $shipping_charges = 0;
            $shippingtext = '<b>Free</b> shipping';
            $shippingtime = '60 Minut';
            $shippingfreemsg = '';
            $carttax = $_SESSION['cartdata']['tax'];
            $cartshipping = $_SESSION['cartdata']['shipping'];
            $subtotal = $_SESSION['cartdata']['total_amount']['subtotal'];

            $after_discount_amount = $subtotal;
// Tax functionality
            if (!empty($carttax)) {

                $texttax = '<div class="col-xs-8 clearfix table-col" style="padding:6px;">GST <br>';
                $i = 1;
                $comm = ',';
                $sum = 0;
                $texttax .= '(';
                foreach ($carttax as $taxes) {
                    $percent = 0;
                    $percent = ($after_discount_amount / 100) * $taxes['percent'];
                    $sum += number_format($percent, 2);
                    $texttax .= ($i != 1) ? $comm : '';
                    $texttax .= strtoupper($taxes['tax_name']) . '=' . number_format($percent, 2);
                    $i++;
                }
                $texttax .= ')';

                $texttax .= '<span style="font-size:11px;" </span> </div>';

                $texttax .= '<div class="col-xs-4 clearfix text_red table-col"  style="padding:6px;text-align:right;">';
                $texttax .= '<i class="fa fa-inr text_red"></i> ';

                if (!empty($sum) && !empty($after_discount_amount)) {
                    $taxamount = $sum;
                }
                $texttax .= number_format($taxamount, 2);

                $texttax .= '</div>';
            }
// Amount with gst
            $amountpay_with_gst = $after_discount_amount + $taxamount;

// shipping functionality
            if (!empty($cartshipping)) {
                $shipping_min_charges = $cartshipping->min_price;
                $shipping_time = $cartshipping->shipping_time;

                if ($amountpay_with_gst < $shipping_min_charges) {
                    $shipping_charges = $cartshipping->charge;
                    $shippingtext = '<i class="fa fa-inr text_red"></i> ' . $shipping_charges;
                    $shippingfreemsg = 'if purchase more then 120 shipping charge will be 0';
                } else {
                    $shippingtext = '<b>Free</b> shipping';
                    $shippingfreemsg = '';
                }

                if (!empty($shipping_time)) {
                    $shippingtime = $shipping_time . ' Minut';
                }
            }

            $total_amount_with_shipping_charges = $amountpay_with_gst + $shipping_charges;
            $this->session->set_userdata('shipping_charegsajax', $shipping_charges);

            $html = <<<HERE

              <div class = "cart-total clearfix samcolorcart">
              <h3 class = "cart-total-title" style = "padding:6px;">Your Order</h3>
            <div id='error-alert' style="display: none;" class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                       ×</button>
                   <span id="errormsgcoupon">Coupon should not be an empty</span>
               </div>
               <div id='error-update-alert' style="display: none;" class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                       ×</button>
                   <span id="errormsgcoupon">This coupon code is invalid or has expired.</span>
               </div>
                    <div id='error-alert-empty' style="display: none;" class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                ×</button>
                            <span id="errormsgcoupon">Cart should not be an empty</span>
                        </div>
              <div class = "alert alert-success"> <button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">
              ×</button>
              <span id = "errormsgcoupon"></span>{$msg}
              </div>
              <div class = "col-xs-8 clearfix table-col" style = "padding:8px;">
              Item Net Amount
              </div>
              <div class = "col-xs-4 clearfix text_red table-col" style = "padding:6px;text-align:right;">
              <i class = "fa fa-inr text_red"></i> {$subtotal}
              </div>
             
              {$texttax}

              <div id="netamountgst">
              <div class="col-xs-8 clearfix table-col" style="padding:6px;">
              Net Amount <span style="font-size:11px;"> (Incl. of GST)</span>
              </div>
              <div class="col-xs-4 clearfix text_red table-col"  style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i>
              {$amountpay_with_gst}
              </div>
              </div>

              <div class="cart-divider"></div>
              <span class="clearfix"></span>
              <!-- <div class="col-xs-8 clearfix table-col" id="divroundoff" style="padding:6px;">
              Wallet Amount
              </div>
              <div class="col-xs-4 clearfix text_red table-col"  id="divroundoffamount" style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i> (-) 0.00
              </div> -->

              <hr />
              <span class="clearfix"></span>
              <div class="shippingid">
              <div class="col-xs-6 clearfix table-col" style="padding:6px;">
              Shipping charge
              </div>
              <div class="col-xs-6 clearfix table-col" style="padding:6px;text-align:right;">
              <span class="text_red" id="spanshiiping">
              {$shippingtext}
              </span>
              </div>
              </div>
              <div id="totaldueamount">
              <div class="col-xs-8 clearfix table-col" id="divroundoff" style="padding:6px;">
              Total Due Amount
              </div>
              <div class="col-xs-4 clearfix text_red table-col fs-20"  id="divroundoffamount" style="padding:6px;text-align:right;">
              <i class="fa fa-inr text_red"></i> {$total_amount_with_shipping_charges}
              </div>
              </div>
              <span class="clearfix"></span>
              <hr/>
              <div class="col-xs-6 clearfix table-col" style="padding:6px;">
              Shipping Time
              </div>
              <div class="col-xs-6 clearfix table-col"  style="padding:6px;text-align:right;">
              <span class="text_red"></span>
              {$shippingtime}
              </div>
              <span class="clearfix"></span>
              <hr>
              <div class="col-xs-12 ">
              <label>{$shippingfreemsg}</label>
              </div>
              <?php }
              ?>
              </div>
HERE;

            return $html;
        }
    }

    public function addressAdd() {

        $customerid = $this->session->userdata('cust_id');
        $html = '';
        $city = $this->homeModel->getRows('cities', 'city_id,city_name');
        if ($this->input->post("userid")) {
            $sentcustomer = $this->input->post('userid');
            if ($customerid === $sentcustomer) {
                $html .= '<form action="' . base_url() . 'addressBook" class="form-horizontal form-label-left" id="servicesid" method="post" accept-charset="utf-8">
                    <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Address Line1 <span class="required">*</span></label>
                             <div class="col-md-6 col-sm-6 col-xs-12"><input name="address_1" class="form-control" required=" placeholder="Enter Address Line1">
                            </div></div>';
                $html .= '<div class = "form-group">
                            <label class = "control-label col-md-3 col-sm-3 col-xs-12">Address Line2 <span class = "required">*</span>
                            </label>
                            <div class = "col-md-6 col-sm-6 col-xs-12">';
                $html .= '<input name="address_2" class="form-control" required=" placeholder="Enter Address Line2"></div>
                </div>
                <div class = "form-group">
                <label class = "control-label col-md-3 col-sm-3 col-xs-12">City <span class = "required">*</span></label>
                <div class = "col-md-6 col-sm-6 col-xs-12">
                <select required = "" class = "form-control" name = "city" id = "city">
                <option value = "">Choose City</option>';

                if (!empty($city)) {
                    foreach ($city as $citys) {
                        $html .= '<option value="' . $citys['city_id'] . '">' . ucfirst($citys['city_name']) . '</option>';
                    }
                }

                $html .= '</select>
                        </div>
                        </div>
                        <div class = "form-group">
                        <label class = "control-label col-md-3 col-sm-3 col-xs-12">Post Code
                        </label>
                        <div class = "col-md-6 col-sm-6 col-xs-12">
                <input name="postcode" class="form-control" required=" placeholder="Enter Postal code">';
                $html .= '</div>
                </div>
                <div class = "ln_solid"></div>
                <div class = "form-group">
                <div class = "col-md-6 col-sm-6 col-xs-12 col-md-offset-3">';
                $html .= '<input type="button" id="addaddressfkm" value="Add Address" class="btn btn-success"></div></div></form>

                ';
            }
        }
        echo $html;
    }

    public function addressChange() {
        $customerid = $this->session->userdata('cust_id');
        $html = '';

        if ($this->input->post("userid")) {
            $sentcustomer = $this->input->post('userid');
            $address = $this->homeModel->getTwoJoins('t1.address_id,t1.address_1,t1.address_2,t1.postcode,t2.city_name', 'addresses as t1', 'cities as t2', 't1.city_id=t2.city_id', '', array('t1.customer_id' => $this->session->userdata('cust_id')));
            if ($customerid === $sentcustomer) {
                $html .= '<form action="" class="form-horizontal form-label-left" id="servicesid" method="post" accept-charset="utf-8">
                <div id="alrtids" style="display:none;" class="alert alert-danger " role="alert"> <strong id="errorfk"></strong></div>
                <div class = "form-group">
                <label class = "control-label col-md-3 col-sm-3 col-xs-12">Address <span class = "required">*</span></label>
                <div class = "col-md-6 col-sm-6 col-xs-12">
                <select required = "" class = "form-control" name = "ckaddress" id = "ckaddress">
                <option value = "">Choose Address</option>';

                if (!empty($address)) {
                    foreach ($address as $add) {
                        $adl = '';
                        $adl = $add['address_1'] . ', ' . $add['address_2'] . ', ' . $add['city_name'] . ', ' . $add['postcode'];
                        $html .= '<option value="' . $adl . '">' . $adl . '</option>';
                    }
                }

                $html .= '</select>
                        </div>
                        </div><div class = "ln_solid"></div>
                <div class = "form-group">
                <div class = "col-md-6 col-sm-6 col-xs-12 col-md-offset-3"><input type="button" id="chkaddfkm" value="Change Address" class="btn btn-success"></div></div></form>';
            }
        }
        echo $html;
    }

    public function getAddress() {
        $string = [];
        if ($this->input->post()) {
            $userid = $this->input->post('userid');
            $address_type = $this->input->post('address_type');
            $useridsess = $this->session->userdata('cust_id');
            if (!empty($address_type)) {
                if ($userid == $useridsess) {
                    $condiont = array('address_type' => $address_type, 'customer_id' => $useridsess);
                    $resulst = $this->homeModel->getRow('addresses', 'address_1,address_2,alternate_mobile', $condiont);
                    if (!empty($resulst)) {
                        $add1 = ucfirst($resulst->address_1);
                        $add2 = ucfirst($resulst->address_2);
                        $altmobile = ucfirst($resulst->alternate_mobile);
                        $string = array('add1' => $add1, 'add2' => $add2, 'alt_mobile' => $altmobile);
                    }
                }
            } else {
                $string['msg'] = 'Invalid post method';
            }
            echo json_encode($string);
        }
    }

    public function getKitchenp() {

        if ($this->input->post()) {

            $cityid = $this->input->post('city_id');
            $html = '<option value="">Please Select Kitchen</option>';
            if (!empty($cityid)) {
                $this->load->model('frontend/homeModel');
                $resulst = $this->homeModel->getRows('kitchen', 'name', array('city_id' => $cityid));
                if (!empty($resulst)) {
                    foreach ($resulst as $res) {
                        $html .= '<option value="' . $res['name'] . '">' . $res['name'] . '</option>';
                    }
                    echo $html;
                } else {

                    echo $html;
                }
            } else {
                echo '';
            }
        }
    }

    public function getTimeSlots() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        if ($this->input->post()) {

            $datepick = $this->input->post('datepick');
            if (!empty($datepick)) {
                $inputdate = date('Y-m-d', strtotime($datepick));
                $currentdate = date('Y-m-d');
                $html = '<option value="">Please Time Slots</option>';
                if ($currentdate <= $inputdate) {
                    $condtion = array('is_active' => 1);
                    $time_slots = $this->homeModel->getRows('time_slots', 'id,time_slots_from,time_slots_to', $condtion);
                    if (!empty($time_slots)) {
                        $current_time = date('H:i', strtotime('+2 hours'));
                        foreach ($time_slots as $time) {
                            if (($current_time < $time['time_slots_from'] && $currentdate == $inputdate) || ($currentdate < $inputdate)) {
                                $timerange = date('h:i A', strtotime($time['time_slots_from'])) . ' to ' . date('h:i A', strtotime($time['time_slots_to']));
                                $html .= '<option value="' . $time['id'] . '">' . $timerange . '</option>';
                            }
                        }
                    }
                    echo $html;
                } else {
                    echo 3;
                }
            } else {
                echo 2;
            }
        } else {
            echo 1;
        }
    }

}
