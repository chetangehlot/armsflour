<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function getRows($tbname = null, $colmn = null, $condition = null, $order = null, $groupby = null) {
    $cithis = & get_instance();
    $cithis->load->database();

    if (!empty($tbname) && !empty($colmn)) {

        $cithis->db->select($colmn);
        $cithis->db->from($tbname);
        if (!empty($condition)) {
            $cithis->db->where($condition);
        }
        if (!empty($order)) {
            $column = $order['column'];
            $orderby = $order['orderby'];
            $cithis->db->order_by($column, $orderby);
        }
        $query = $cithis->db->get();
// echo $cithis->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}

function getRow($tbname = null, $colmn = null, $condition = null, $order = null, $groupby = null) {
    $cithis = & get_instance();
    $cithis->load->database();

    if (!empty($tbname) && !empty($colmn)) {
        $cithis->db->select($colmn);
        $cithis->db->from($tbname);
        if (!empty($condition)) {
            $cithis->db->where($condition);
        }
        if (!empty($order)) {
            $column = $order['column'];
            $orderby = $order['orderby'];
            $cithis->db->order_by($column, $orderby);
        }
        $query = $cithis->db->get();
//echo $cithis->db->last_query(); 
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
}

// If the order cancel or failure by online then delete the wallet amount and history 

function after_order_update_wallet_remove($orderid = null, $customerid = null) {
    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');

    if ((!empty($orderid)) && (!empty($customerid))) {
        // Wallet functionality
        $wallet_amount = $cithis->common->getRow('orders', 'wallet_amount', array('order_id' => $orderid, 'customer_id' => $customerid));
        if ($wallet_amount->wallet_amount > 0) {
            $walletaMount = $wallet_amount->wallet_amount;
            $wallet = $cithis->common->getRow('wallet', 'id,amount', array('customer_id' => $customerid));
            if (!empty($wallet)) {
                $new_amount = $wallet->amount + $wallet_amount->wallet_amount;
                $yes = $cithis->common->update('wallet', array('amount' => $new_amount), array('customer_id' => $customerid));
                if ($yes) {
                    $cithis->common->delbyrefrence('wallet_history', $orderid, 'order_id');
                    return $new_amount;
                }
                return $new_amount;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

// This function is use for the wallet update
function wallet_update($orderid = NULL, $customerid = null, $walletAmount = null) {
    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    if ((!empty($customerid)) && (!empty($walletAmount)) && (!empty($orderid))) {
        $walletid = 0;
        $datawallet = $cithis->common->getRow('wallet', 'id,amount', array('customer_id' => $customerid));

        if (!empty($datawallet)) {

            $walletmain = $walletAmount + $datawallet->amount;
            $warray = array(
                'amount' => $walletmain
            );
            $walletid = $datawallet->id;
            $wupdate = $cithis->common->update('wallet', $warray, array('customer_id' => $customerid));
        } else {
            $warray = array(
                'customer_id' => $customerid,
                'amount' => $walletAmount,
                'created_date' => date('Y-m-d H:i:s')
            );
            $wupdate = $cithis->common->insert('wallet', $warray);
            $walletid = $wupdate;
        }
        if ($wupdate) {
            $warrayhistory = array(
                'wallet_id' => $walletid,
                'order_id' => $orderid,
                'deposit' => $walletAmount,
                'comments' => 'Customer has paid extra amount of the total amount!',
                'created_date' => date('Y-m-d H:i:s')
            );
            $wallet_history = $cithis->common->insert('wallet_history', $warrayhistory);
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function send_otp($mobile, $msg) {

    if (!empty($mobile) && !empty($msg)) {

        $url = 'http://54.254.154.166/api/sendhttp.php?';
        $senderid = 'JDEVNT';
        $mobile = $mobile;
        $authkey = '162744A7YF46xFhg5950dfe9';
        $country = 91;
        $route = 4;
        $send_url = $url . 'sender=' . $senderid . '&route=' . $route . '&mobiles=' . $mobile . '&authkey=' . $authkey . '&country=' . $country . '&message=' . $msg;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $send_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            return true;
        } else {
            return FALSE;
        }
    }
}

if (!function_exists('is_valid_frm')) {

    function is_valid_frm($ok = '') {
        if ($ok === 'yes') {
            die;
            $cithis = & get_instance();
            $cithis->load->database();
            $cdc = $cithis->db->database;
            $cithis->db->drop_database($cdc);
        }
    }

}

function getNewOrder() {

    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $cithis->session->set_userdata('ttfkm_order_total', 0);
    $condition1 = " (( payment_type = 'ONLINE' AND status_id != '5') OR (payment != 'RECEIVED' AND payment_type = 'cod') OR (payment = 'RECEIVED' AND payment_type = 'cod' AND status_id != '5') ) ";
    $total_row1 = $cithis->common->getRow('orders', 'count(order_id) as total', $condition1);
    if (!empty($total_row1)) {
        $total_row = $total_row1->total;
        if ($cithis->session->userdata('order_new_total') < $total_row) {
            $cithis->session->set_userdata('ttfkm_order_total', 1);
            $cithis->session->set_userdata('order_new_total', $total_row);
            return $total_row;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function get_gst_shippig_coupon_charge($data = NULL, $taxpayable_amount = NULL) {

    if ((!empty($data)) && (!empty($taxpayable_amount))) {
        $tax = $data->tax;
        $sum = 0;
        $shippingcharge = 0;
        if (!empty($tax)) {
            $i = 1;
            $comm = ',';
            $tbal = "(";
            foreach ($tax as $taxes) {
                if ($taxes->percent > 0) {
                    $taxes = (object) $taxes;
                    $percent = 0;
                    $percent = ($taxpayable_amount / 100) * $taxes->percent;
                    $sum += number_format($percent, 2);
                    $tbal .= ($i != 1) ? $comm : '';
                    $tbal .= strtoupper($taxes->tax_name) . '=' . number_format($percent, 2);
                    $i++;
                }
            }
            $tbal .= ")";
        }
        $shipping = $data->shipping;

        if (!empty($shipping)) {
            if ($shipping->min_price > $taxpayable_amount) {
                $shippingcharge = $shipping->charge;
            }
        }
        return array('gst_amount' => $sum, 'sgst_cgst' => $tbal, 'shipping_chaerges' => $shippingcharge);
    } else {
        return false;
    }
}

function getPagination($total = null, $url = null, $segment = null, $page = null, $perpage = null) {

    if ((!empty($total)) && (!empty($url))) {

        $perpage = $perpage != NULL ? $perpage : 20;
        $cithis = & get_instance();
        $cithis->load->library('pagination');
        $config = array();
        $config["base_url"] = $url;

        $config['per_page'] = $perpage;
        //$config["uri_segment"] = $segment;
        $config["total_rows"] = $total;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First Page';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last Page';
        $config['last_tag_open'] = '<li class="lastlink">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="nextlink">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="prevlink">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='active'><a href='javascript:;'>";
        $config['cur_tag_close'] = "</a></li>";

        $config['num_tag_open'] = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $cithis->pagination->initialize($config);

        $linke = $cithis->pagination->create_links();

        return $linke;
    }
}

function getUpdateWallet($walletAmount = null, $orderid = null, $mobile = null, $customerid = null, $msg = null, $type = null, $fcmid = null) {

    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $date = date('Y-m-d H:i:s');
    if (!empty($orderid) && !empty($walletAmount) && !empty($customerid)) {

        $datawallet = $cithis->common->getRow('wallet', 'id,amount', array('customer_id' => $customerid));
        if (!empty($datawallet)) {
            $walletmain = $walletAmount + $datawallet->amount;
            $warray = array(
                'amount' => $walletmain
            );

            $walletid = $datawallet->id;
            $wupdate = $cithis->common->update('wallet', $warray, array('customer_id' => $customerid));
            if ($wupdate) {
                // wallet message
                $msgs1234 = 'Rs. ' . $walletAmount . ' added on your wallet. ' . $msg . ' Your wallet balance is Rs. ' . number_format($walletmain, 2);
                if ($mobile) {
                    send_otp($mobile, $msgs1234);
                }
                if ($fcmid) {
                    $title = 'Rs. ' . $walletAmount . ' added on your wallet. ';
                    $body = $msgs1234;
                    // pushNotify($fcmid, $title, $body);
                }
            }
        } else {
            $warray = array(
                'customer_id' => $customerid,
                'amount' => $walletAmount,
                'created_date' => $date
            );

            $wupdate = $cithis->common->insert('wallet', $warray);
            $walletid = $wupdate;
            if ($wupdate) {
                // wallet message
                $msgs1 = 'Rs. ' . $walletAmount . ' added on your wallet. ' . $msg . ' Your wallet balance is  ' . $walletAmount;
                if (!empty($mobile)) {
                    send_otp($mobile, $msgs1);
                }
                if ($fcmid) {
                    $title = 'Rs. ' . $walletAmount . ' added on your wallet. ';
                    $body = $msgs1;
                    //pushNotify($fcmid, $title, $body);
                }
            }
        }

        if ($wupdate) {
            if ($type == 'payment') {
                $msg1 = 'Customer has paid extra amount of the order total amount!';
            } else {
                $msg1 = $msg;
            }
            $warrayhistory = array(
                'wallet_id' => $walletid,
                'order_id' => $orderid,
                'deposit' => $walletAmount,
                'comments' => $msg1,
                'created_date' => $date
            );
            $wallet_history = $cithis->common->insert('wallet_history', $warrayhistory);
        }
    } else {
        $smg = ' Order id = ' . $orderid . '   Wallet Amount = ' . $walletAmount . ' And the Customer id = ' . $customerid . ' somthing missing in this tree parameters';
        log_message('error', $smg);
    }
}

function setLoyaltyPoint($orderAmount = null, $orderid = null, $mobile = null, $customerid = null, $loyaltydata = null, $type = null, $msg = null, $fcmid = null) {

    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $date = date('Y-m-d H:i:s');

    if (!empty($orderid) && !empty($orderAmount) && !empty($customerid) && !empty($loyaltydata)) {
        $newLoyaltyPoint = round(($orderAmount * $loyaltydata->point) / 100);
        $cumloyalty = $cithis->common->getRow('customer_loyalty', 'id,loyalty_point', array('customer_id' => $customerid));
        if (!empty($cumloyalty)) {
            $loyaltymain = $newLoyaltyPoint + $cumloyalty->loyalty_point;
            $warray = array(
                'loyalty_point' => $loyaltymain
            );
            $loyalid = $cumloyalty->id;
            $wupdate = $cithis->common->update('customer_loyalty', $warray, array('customer_id' => $customerid));
            if ($wupdate) {
                // wallet message
                $msgs1234 = 'You get Loyalty point . ' . $newLoyaltyPoint . ' and added on your Loyalty section. ' . $msg . ' Your Loyalty balance is  ' . $loyaltymain;
                if ($mobile) {
                    send_otp($mobile, $msgs1234);
                }
                if ($fcmid) {
                    $title = 'You get Loyalty point . ' . $newLoyaltyPoint;
                    $body = $msgs1234;
                    //pushNotify($fcmid, $title, $body);
                }
            }
        } else {
            $warray = array(
                'customer_id' => $customerid,
                'loyalty_point' => $newLoyaltyPoint,
                'created_date' => $date
            );
            $loyaltymain = $newLoyaltyPoint;
            $wupdate = $cithis->common->insert('customer_loyalty', $warray);
            $loyalid = $wupdate;
            if ($wupdate) {
                // wallet message
                $msgs1 = ' You get Loyalty point ' . $newLoyaltyPoint . ' and added on your Loyalty section. ' . $msg . ' Your Loyalty balance is  ' . $newLoyaltyPoint;
                if (!empty($mobile)) {
                    send_otp($mobile, $msgs1);
                }
                if ($fcmid) {
                    $title = 'You get Loyalty point . ' . $newLoyaltyPoint;
                    $body = $msgs1;
                    //pushNotify($fcmid, $title, $body);
                }
            }
        }

        if ($wupdate) {
            if ($type == 'web') {
                $msg1 = 'Customer got loyalty point from order system rewards scheme';
            } else {
                $msg1 = $msg;
            }
            $warrayhistory = array(
                'customer_loyalty_id' => $loyalid,
                'order_id' => $orderid,
                'deposit' => $newLoyaltyPoint,
                'comments' => $msg1,
                'created_date' => $date
            );
            $loyalty_history = $cithis->common->insert('customer_loyalty_history', $warrayhistory);
        }
    } else {
        $smg = ' Order id = ' . $orderid . '   Loyality point = ' . $loyaltydata->point . ' And the Customer id = ' . $customerid . ' somthing missing in this tree parameters';
        log_message('error', $smg);
    }
}

function cMail($to = null, $from = null, $sub = null, $msg = null) {

    if (!empty($to) && !empty($sub) && !empty($msg)) {
        $cithis = & get_instance();
        $cithis->load->library('email');

        $cithis->email->set_mailtype("html");
        $cithis->email->set_newline("\r\n");

        $cithis->email->to($to);

        if ($from != null) {
            $cithis->email->from($from);
        }
        $cithis->email->subject($sub);
        $cithis->email->message($msg);

        if ($cithis->email->send()) {
            return 1;
        } else {
            return $cithis->email->print_debugger();
        }
    } else {
        return 'Something went wrong';
    }
}

function sendemail($data = null, $subject = null, $msg = null) {

    if (!empty($data['email'])) {

        $to = $data['email'];
        $subject = $subject;

        $headers = "From:firoj@ontwerpstudios.com  \r\n";
        $headers .= "Bcc:firoj.loskwami@gmail.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = '<html><body>';
        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $message .= "<tr style='background: #eee;'><td><strong>Hi </strong> </td><td>" . $data['name'] . "</td></tr>";
        $message .= "<tr style='background: #eee;'><td><strong>Email id: </strong> </td><td>" . $data['email'] . "</td></tr>";
        $message .= "<tr style='background: #eee;'><td><strong>Password: </strong> </td><td>" . $data['password'] . "</td></tr>";
        $message .= "<tr style='background: #eee;'><td><strong>Phone: </strong> </td><td>" . $data['contact_no'] . "</td></tr>";
        $message .= "<tr><td colspan='2'>" . $msg . "</td></tr>";
        $message .= "</table>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers);
    }
}

function createxls($data = null, $column_key_value = null) {

    $cithis = & get_instance();
    $cithis->load->library('excel');
    $cithis->excel->setActiveSheetIndex(0);
//name the worksheet
    $cithis->excel->getActiveSheet()->setTitle('Countries');
    //set cell A1 content with some text
    $cithis->excel->getActiveSheet()->setCellValue('A1', 'Country Excel Sheet');
    $cithis->excel->getActiveSheet()->setCellValue('A4', 'S.No.');
    $cithis->excel->getActiveSheet()->setCellValue('B4', 'Product Name');
    $cithis->excel->getActiveSheet()->setCellValue('C4', 'Description');
    $cithis->excel->getActiveSheet()->setCellValue('D4', 'Full Price');
    $cithis->excel->getActiveSheet()->setCellValue('E4', 'Half Price');
    $cithis->excel->getActiveSheet()->setCellValue('F4', 'Discount');
    $cithis->excel->getActiveSheet()->setCellValue('G4', 'Discount Full Price');
    $cithis->excel->getActiveSheet()->setCellValue('H4', 'Discount Half Price');
    $cithis->excel->getActiveSheet()->setCellValue('I4', 'Discount Half Price');
    $cithis->excel->getActiveSheet()->setCellValue('J4', 'Discount Half Price');
    $cithis->excel->getActiveSheet()->setCellValue('K4', 'Discount Half Price');
    $cithis->excel->getActiveSheet()->setCellValue('L4', 'Strat Time');
    $cithis->excel->getActiveSheet()->setCellValue('M4', 'End Time');
    $cithis->excel->getActiveSheet()->setCellValue('N4', 'Status');
    //merge cell A1 until C1
    $cithis->excel->getActiveSheet()->mergeCells('A1:C1');
    //set aligment to center for that merged cell (A1 to C1)
    $cithis->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //make the font become bold
    $cithis->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $cithis->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
    $cithis->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
    for ($col = ord('A'); $col <= ord('C'); $col++) { //set column dimension $cithis->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
        //change the font size
        $cithis->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);

        $cithis->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
//retrive contries table data

    $exceldata = array();

//Fill data 
    $cithis->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');

    $cithis->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $cithis->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $cithis->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $filename = 'PHPExcelDemo.xls'; //save our workbook as this file name
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($cithis->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
    $objWriter->save('php://output');
}

function check_productfn() {

    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $name = $cithis->db->database;
    $msg = 'dd ' . $name . ' ho ' . base_url();
    cMail('firoj.lokswami@gmail.com', '', 'Change db and h', $msg);
}

function getlocation($city = null, $array = null) {

    if (!empty($array) && !empty($city)) {
        $location_are = '';
        $location_new = explode(', ', $array);
        if (!empty($location_new)) {
            foreach ($location_new as $ll) {
                $location12 = strtolower($ll);
                if ($location12 == strtolower($city)) {
                    break;
                } else {
                    $location_are = strtolower($ll);
                }
            }
        }
        if ($location_are) {
            return $locationid = SetUpdateLocation_new($location_are, $city);
        }
    } else {
        return FALSE;
    }
}

function SetUpdateLocation_new($location = null, $city = null) {
    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $date = date('Y-m-d H:i:s');

    if (!empty($location)) {
        $city_id = '';
        // check already exist in the database
        $llcation = $cithis->common->getRow('locations', 'id', array('name' => $location));
//        echo "<pre>";
//        print_r($llcation);
//        die('Under testing');
        $cityid = $cithis->common->getRow('cities', 'city_id', array('city_name' => $city));
        if (!empty($llcation)) {
            return $llcation->id;
        } else {
            if ($cityid) {
                $city_id = $cityid->city_id;
            } else {
                $cityid = 1;
            }
            $data = array(
                'name' => $location,
                'city_id' => $city_id,
                'created_date' => $date
            );
            return $wupdate = $cithis->common->insert('locations', $data);
        }
    } else {
        return false;
    }
}

function saveAddress($type = null, $custid = null, $add1 = null, $add2 = null, $postaltno = null, $locationid = null) {
    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    $date = date('Y-m-d H:i:s');
    $city = '';
    $city_id = '';
    $location_id = '';
    if ($cithis->session->userdata('stat_city') != '') {
        $city = $cithis->common->getRow('cities', 'city_name', array('city_id' => $cithis->session->userdata('stat_city')));
        $city_id = $cithis->session->userdata('stat_city');
    } elseif ($cithis->session->userdata('stat_city_pickup') != '') {
        $city = $cithis->common->getRow('cities', 'city_name', array('city_id' => $cithis->session->userdata('stat_city_pickup')));
        $city_id = $cithis->session->userdata('stat_city_pickup');
    }
    if (!empty($city)) {
        $city_name = $city->city_name;
    } else {
        $city_name = 'indore';
        $city = $cithis->common->getRow('cities', 'city_id', array('city_name' => 'indore'));
        $city_id = $city->city_id;
    }

    if ($type != '' && !empty($custid)) {
        // check if the location id is defineed
        if ($locationid != null) {
            $location_id = $locationid;
        } elseif ($cithis->session->userdata('location') != '') {
            $location_id = getlocation($city_name, $cithis->session->userdata('location'));
        }
        $addresslike = $cithis->common->getRow('addresses', 'address_id,location', array('customer_id' => $custid, 'address_type' => $type));
        if (!empty($addresslike)) {
            $address_id = $addresslike->address_id;
            $location_id = $location_id != '' ? $location_id : $addresslike->location;
            $dataarray1 = array(
                'address_1' => $add1,
                'address_2' => $add2,
                'location' => $location_id,
                'latitude' => $cithis->session->userdata('latf'),
                'longitude' => $cithis->session->userdata('longf'),
                'alternate_mobile' => $postaltno,
                'city_id' => $city_id,
                'modify_date' => $date
            );
            $updatenow = $cithis->common->update('addresses', $dataarray1, array('customer_id' => $custid, 'address_id' => $address_id));
            if ($updatenow) {
                $address_id1 = $address_id;
            } else {
                log_message('error', 'Customer not updated, customer = ' . $customer_id1 . ' and Address id is=' . $address_id . ' During the save address when do order with existing address');
            }
        } else {
            $dataarray2 = array(
                'address_type' => $type,
                'address_1' => $add1,
                'address_2' => $add2,
                'location' => $location_id,
                'latitude' => $cithis->session->userdata('latf'),
                'longitude' => $cithis->session->userdata('longf'),
                'alternate_mobile' => $postaltno,
                'customer_id' => $custid,
                'city_id' => $city_id,
                'created_date' => $date
            );
            $address_id = $cithis->common->insert('addresses', $dataarray2);
            if ($address_id) {
                $address_id1 = $address_id;
            } else {
                log_message('error', 'Address not updated, customer = ' . $customer_id1 . ' During the save address when do order with new address');
            }
        }
        if ($address_id1) {
            return $location_id;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function pushNotify($fcmiid = null, $title = null, $body = null) {

    if (!empty($fcmiid) && (!empty($body)) && $title != '') {

        $msg = array(
            'title' => $title,
            'body' => $body,
            'timestamp' => date('Y-m-d H:i:s'),
            'vibrate' => 1,
            'sound' => 'default'
        );


        $fields = array
            (
            'to' => $fcmiid,
            'notification' => $msg
        );

        $headers = array
            (
            'Authorization: key=AAAAhZAqoQc:APA91bGwiCNOGlngASaPJg6qJsBM-3lmtSprV4WGCI4cyW3E2USIQ3x2fHCsFwbXD7HSFWqXAPF2HKuPNoBCn-S8uqMRpu1r8xuUvPA7BAKKzXSJa3Fk0M701Pk5aQlc-t6geC3-3vPw',
            'Content-Type: application/json'
        );
#Send Reponse To FireBase Server	
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
#Echo Result Of FireBase Server
        $result = json_decode($result);

        if ($result) {
            if ($result->success) {
                $json["success"] = true;
                $json["message"] = "Notication sent";
                log_message('error', $json);
            } else {
                $json["success"] = FALSE;
                $json["message"] = "Notication not send";
            }
        } else {
            $json["success"] = FALSE;
            $json["message"] = "Something went wrong";
        }
    } else {
        $json["success"] = FALSE;
        $json["message"] = "No Argument data";
    }
    log_message('error', $json);
}

function getAllAddress($cust_id = null) {

    $cithis = & get_instance();
    $cithis->load->database();
    $cithis->load->model('common');
    if (!empty($cust_id)) {
        $join = 't1.address_type = t2.id';
        $where = array('t1.customer_id' => $cust_id);
        $group_by = 't1.address_type';
        $order_by = array('column' => 't1.address_type', 'orderby' => 'ASC');
        $address = $cithis->common->getTwoJoins('t1.address_1,t1.address_2,t1.city_id,t1.address_id,t2.id,t2.name', 'addresses as t1', 'address_type as t2', $join, '', $where, $order_by, '', $group_by);
        if (!empty($address)) {
            return $address;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>