<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/DashboardModel');
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
        $this->load->model('admin/DashboardModel');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index() {
        $date = date('Y-m-d');
        $data['todaysales'] = $this->DashboardModel->totalsels($date);
        $data['totalorders'] = $this->DashboardModel->countValue('orders', 'order_id', $date);
        $data['totalproduct'] = $this->DashboardModel->countValue('product', 'pid');
        $data['totalcoupon'] = $this->DashboardModel->countValue('coupon', 'id');
        // itesm sale total
        $condition1 = " (( payment_type = 'ONLINE' AND status_id = '5') OR (payment = 'RECEIVED' AND payment_type = 'cod' AND status_id = '5') AND t1.order_date='" . date('Y-m-d') . "') ";
        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');
        $groupby = 't2.product_id';
        $data['total_sales'] = $this->common->getTwoJoins('sum(t2.quantity) as total_product,t2.name,(sum(t2.quantity)*t2.price) as totalpaid,t2.price,t2.is_nonveg,(sum(t2.quantity)*t2.discount) as total_discount, ', 'orders as t1', 'order_products as t2', 't2.order_id = t1.order_id', 'LEFT', $condition1, $order_by, '', $groupby);
        $totalsales = 0;
        if (!empty($data['total_sales'])) {
            foreach ($data['total_sales'] as $saltt) {
                $totalsales += $saltt['totalpaid'];
            }
        }
        $data['itesm_sales_total'] = number_format($totalsales, 2);
        $this->maintheme('crmdash', $data);
    }

    public function sales($id = null) {
        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " t2.mobile =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " t2.name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
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
        if (!empty($this->input->get('filter_kitchen'))) {
            $data['filter_kitchen'] = $this->input->get('filter_kitchen');
            $url .= 'filter_kitchen=' . $data['filter_kitchen'] . '&';
            $condition1 .= " t1.kitchen_id = " . $data['filter_kitchen'] . " AND ";
        } else {
            $data['filter_kitchen'] = '';
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

        $condition1 .= " (( payment_type = 'ONLINE' AND status_id = '5') OR (payment = 'RECEIVED' AND payment_type = 'cod' AND status_id = '5')) ";
        if (empty($this->input->get('to_date')) && empty($this->input->get('from_date'))) {
            $condition1 .= " AND order_date = '" . date('Y-m-d ') . "'";
        }
        $data['kitchen'] = $this->common->getRows('kitchen', 'name, id', array('is_active' => 1), array('column' => 'name', 'orderby' => 'ASC'));

        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');
        $data['actionurl'] = 'sales';
        $data['action_url'] = base_url('crm');
        $data ['order'] = $this->common->getTwoJoins('t1.order_id,t1.name as customer_name,t1.telephone,t1.address,t1.address2,t1.location_id,t1.status_id,t1.payment_type,t1.order_type,t1.order_total,t1.order_time,t1.order_date,t1.date_added,t1.tax', 'orders as t1', 'customers as t2', 't1.customer_id=t2.id', '', $condition1, $order_by);
        $data['total_sales'] = $this->common->getTwoJoins('sum(t1.order_total) as total,sum(t1.sub_total_amount) as sub_total,sum(t1.wallet_amount)as wallet_total, sum(t1.dicount_amount) as discount_total,sum(t1.shipping_charges) as shipp_charge_total,count(t1.order_id) as total_order', 'orders as t1', 'customers as t2', 't2.id = t1.customer_id', '', $condition1, $order_by, 1);
        $this->maintheme('sales', $data);
    }

    public function delivery() {
        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " t2.contact_no =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " t2.name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
        }
        if (!empty($this->input->get('filter_kitchen'))) {
            $data['filter_kitchen'] = $this->input->get('filter_kitchen');
            $url .= 'filter_kitchen=' . $data['filter_kitchen'] . '&';
            $condition1 .= " t1.kitchen_id = " . $data['filter_kitchen'] . " AND ";
        } else {
            $data['filter_kitchen'] = '';
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
            $condition1 .= " order_type = '" . $data['filter_type'] . "' AND ";
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
        $condition1 .= ' t1.date_added!=""';

        if (empty($this->input->get('to_date')) && empty($this->input->get('from_date'))) {
            $condition1 .= " AND order_date = '" . date('Y-m-d ') . "'";
        }
        //$total_row1 = $this->common->getRow('orders', 'count(order_id) as total', $condition1);

        $statuscond = array('status_for' => 'order');
        $data['status'] = $this->common->getRows('order_statuses', 'status_id, status_name', $statuscond, array('column' => 'status_id', 'orderby' => 'ASC'));

        $data['kitchen'] = $this->common->getRows('kitchen', 'name, id', array('is_active' => 1), array('column' => 'name', 'orderby' => 'ASC'));
        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');
        $data['actionurl'] = 'delivery';
        $data['action_url'] = base_url('admin');
        $data['order'] = $this->common->getTwoJoins('t2.name as delivery_boy, t2.contact_no, t2.amount_by_kitchen, t1.order_id, t1.name as customer_name, t1.telephone, t1.address, t1.address2, t1.location_id, t1.status_id, t1.payment_type, t1.order_type, t1.order_total,t1.sub_total_amount,t1.dicount_amount,t1.wallet_amount,t1.shipping_charges,t1.tax, t1.order_time, t1.order_date, t1.date_added', 'orders as t1', 'staff as t2', 't2.id = t1.sttaf_assignee_id', '', $condition1, $order_by);
        $data['total_sales'] = $this->common->getTwoJoins('sum(t1.order_total) as total,count(t1.order_id) as total_order', 'orders as t1', 'staff as t2', 't2.id = t1.sttaf_assignee_id', '', $condition1, $order_by, 1);

        $this->maintheme('delivery', $data);
    }

    public function product() {
        $condition1 = "";
        $url = '?';
        $date = date("Y-m-d");
        $data['show_popup'] = 0;

        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            $condition1 .= " t2.name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
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
        $condition1 .= ' t2.product_id!=""';
        if (empty($this->input->get('to_date')) && empty($this->input->get('from_date'))) {
            $condition1 .= " AND order_date = '" . date('Y-m-d ') . "'";
        }

        //$total_row1 = $this->common->getRow('orders', 'count(order_id) as total', $condition1);

        $order_by = array('column' => 'total_product', 'orderby' => 'DESC');
        $data['actionurl'] = 'product';
        $data['action_url'] = base_url('admin');
        $groupby = 't2.product_id';

        $data['total_sales'] = $this->common->getTwoJoins('count(t2.product_id) as total_product, t2.name', 'orders as t1', 'order_products as t2', 't2.order_id = t1.order_id', 'LEFT', $condition1, $order_by, '', $groupby);

        $this->maintheme('product', $data);
    }

    public function customer() {
        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " t2.mobile =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " t2.name  LIKE '%" . $data['filter_search'] . "%'  AND ";

            $condition1 .= ' t1.date_added!=""';

            $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');

            $data['orderlist'] = $this->common->getTwoJoins('t2.name, t2.mobile, t1.order_id, t1.address, t1.address2,t1.payment_type, t1.order_type, t1.order_total,t1.sub_total_amount, t1.order_time, t1.order_date', 'orders as t1', 'customers as t2', 't2.id = t1.customer_id', '', $condition1, $order_by);
            if (!empty($data['orderlist'])) {
                $data['last_order_deatils'] = $data['orderlist'][0];
            }
            $data['total_sales'] = $this->common->getTwoJoins('sum(t1.sub_total_amount) as total,count(t1.order_id) as total_order,t2.name,t2.email,t2.mobile,t2.id as customer_id', 'orders as t1', 'customers as t2', 't2.id = t1.customer_id', '', $condition1, $order_by, 1);
            if ($data['total_sales'] && $data['total_sales']->total_order > 0) {
                $data['avg_bill'] = number_format(($data['total_sales']->total / $data['total_sales']->total_order), 2);
                $cusmerid = $data['total_sales']->customer_id;
                $order_by1 = array('column' => 'count(t2.product_id)', 'orderby' => 'DESC');
                $groupby12 = "t2.name";
                $data['papular_product'] = $this->common->getTwoJoins('t2.name,count(t2.product_id)', 'orders as t1', 'order_products as t2', 't2.order_id = t1.order_id', '', array('t1.customer_id' => $cusmerid), $order_by1, '', $groupby12);
            }
        }

        $data['actionurl'] = 'customer';
        $data['action_url'] = base_url('admin');
        $this->maintheme('customer', $data);
    }

    public function customernew() {
        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search=' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " t1.mobile =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " t1.name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
        }

        if (!empty($this->input->get('from_date'))) {
            $data['from_date'] = $this->input->get('from_date');
            $url .= 'from_date=' . $data['from_date'] . '&';
            $condition1 .= " DATE(t1.created_date) >= '" . date('Y-m-d ', strtotime($data['from_date'])) . "' AND ";
        } else {
            $data['from_date'] = '';
        }
        if (!empty($this->input->get('to_date'))) {
            $data['to_date'] = $this->input->get('to_date');
            $url .= 'to_date=' . $data['to_date'] . '&';
            $condition1 .= " DATE(t1.created_date) <= '" . date('Y-m-d ', strtotime($data['to_date'])) . "' AND ";
        } else {
            $data['to_date'] = '';
        }
        $condition1 .= ' t1.created_date!=""';
        if (empty($this->input->get('to_date')) && empty($this->input->get('from_date'))) {
            $condition1 .= " AND DATE(t1.created_date) = '" . date('Y-m-d ') . "'";
        }

        $order_by = array('column' => 't1.created_date', 'orderby' => 'DESC');
        $data['actionurl'] = 'customernew';
        $data['action_url'] = base_url('admin');
        // $data['customerdetails'] = $this->common->getTwoJoins('t1.name, t1.mobile, count(t2.order_id) as total_order,count(t2.sub_total_amount) as total_sales,t1.created_date', 'customers as t1', 'orders as t2', 't2.customer_id = t1.id', 'LEFT', $condition1, $order_by);
        $data['customerdetails'] = $this->common->getRows('customers as t1', 't1.id,t1.name,t1.mobile,t1.created_date', $condition1, $order_by);
        $this->maintheme('customernew', $data);
    }

    public function itemSale($id = null) {

        $condition1 = "";
        $url = '?';
        $data['show_popup'] = 0;

        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            $url .= 'filter_search = ' . $data['filter_search'] . '&';
            if (is_numeric($data['filter_search']))
                $condition1 .= " t2.mobile =" . $data['filter_search'] . " AND ";
            else
                $condition1 .= " t2.name  LIKE '%" . $data['filter_search'] . "%'  AND ";
        } else {
            $data['filter_search'] = '';
        }

        if (!empty($this->input->get('filter_payment'))) {
            $data['filter_payment'] = strtolower($this->input->get('filter_payment'));
            $url .= 'filter_payment = ' . $data['filter_payment'] . '&';
            $condition1 .= " payment_type = '" . $data['filter_payment'] . "' AND ";
        } else {
            $data['filter_payment'] = '';
        }
        // Kitchen type
        if (!empty($this->input->get('filter_type'))) {
            $data['filter_type'] = $this->input->get('filter_type');
            $url .= 'filter_type = ' . $data['filter_type'] . '&';
            $condition1 .= " t1.kitchen_id = '" . $data['filter_type'] . "' AND ";
        } else {
            $data['filter_type'] = '';
        }
        // category type
        if (!empty($this->input->get('filter_cat'))) {
            $data['filter_cat'] = $this->input->get('filter_cat');
            $url .= 'filter_cat = ' . $data['filter_cat'] . '&';
            $condition1 .= " t3.category_id= '" . $data['filter_cat'] . "' AND ";
        } else {
            $data['filter_cat'] = '';
        }
        if (!empty($this->input->get('from_date'))) {
            $data['from_date'] = $this->input->get('from_date');
            $url .= 'from_date = ' . $data['from_date'] . '&';
            $condition1 .= " order_date >= '" . date('Y-m-d ', strtotime($data['from_date'])) . "' AND ";
        } else {
            $data['from_date'] = '';
        }
        if (!empty($this->input->get('to_date'))) {
            $data['to_date'] = $this->input->get('to_date');
            $url .= 'to_date = ' . $data['to_date'] . '&';
            $condition1 .= " order_date <= '" . date('Y-m-d ', strtotime($data['to_date'])) . "' AND ";
        } else {
            $data['to_date'] = '';
        }

        $condition1 .= " (( payment_type = 'ONLINE' AND status_id = '5') OR (payment = 'RECEIVED' AND payment_type = 'cod' AND status_id = '5')) ";
        if (empty($this->input->get('to_date')) && empty($this->input->get('from_date'))) {
            $condition1 .= " AND order_date = '" . date('Y-m-d ') . "'";
        }

        $data['kitchen'] = $this->common->getRows('kitchen', 'name, id', array('is_active' => 1), array('column' => 'name', 'orderby' => 'ASC'));
        $data['category'] = $this->common->getRows('category', 'name, id', array('is_active' => 1), array('column' => 'name', 'orderby' => 'ASC'));
        $statuscond = array('status_for' => 'order');
        $data['status'] = $this->common->getRows('order_statuses', 'status_id, status_name', $statuscond, array('column' => 'status_id', 'orderby' => 'ASC'));
        $order_by = array('column' => 't1.date_added', 'orderby' => 'DESC');
        $data['actionurl'] = 'itemSale';
        $data['action_url'] = base_url('crm');
        $groupby = 't2.product_id';
        //$data['total_sales'] = $this->common->getTwoJoins('sum(t2.quantity) as total_product, t2.name, (sum(t2.quantity)*t2.price) as totalpaid, t2.price, t2.is_nonveg, (sum(t2.quantity)*t2.discount) as total_discount ', 'orders as t1', 'order_products as t2', 't2.order_id = t1.order_id', 'LEFT', $condition1, $order_by, '', $groupby);


        $tablejoin = array(array('table1' => 'order_products as t2', 'join1' => 't2.order_id=t1.order_id'), array('table1' => 'product_category as t3', 'join1' => 't3.product_id=t2.product_id'));
        $data['total_sales'] = $this->common->getMoreJoin('sum(t2.quantity) as total_product, t2.name, (sum(t2.quantity)*t2.price) as totalpaid, t2.price, t2.is_nonveg, (sum(t2.quantity)*t2.discount) as total_discount,t3.category_id,count(t1.order_id) as total_order', 'orders as t1', $tablejoin, $condition1, $order_by, '', $groupby);

        $totalsales = 0;
        $total_order = 0;
        if (!empty($data['total_sales'])) {
            foreach ($data['total_sales'] as $saltt) {
                $total_order += $saltt['total_order'];
                $totalsales += $saltt['totalpaid'];
            }
        }
        $data['salestotal'] = number_format($totalsales, 2);
        $data['total_order'] = $total_order;
        $data['total_order_sales'] = $this->common->getMoreJoin('count(t1.order_id) as total_order', 'orders as t1', $tablejoin, $condition1, $order_by, '', $groupby, '', 1);
        // $data['total_order_sales1'] = $this->common->getTwoJoins('count(t1.order_id) as total_order', 'orders as t1', 'order_products as t2', 't2.order_id = t1.order_id', 'LEFT', $condition1, $order_by, 1);

        $this->maintheme('itemsale', $data);
    }

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/crm/' . $page, $data);
        }
        $this->load->view('admin/include/footer', $data);
    }

}

?>
