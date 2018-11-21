<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OrderModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_order_history($orderid = null) {

        if (!empty($orderid)) {
            $this->db->select('t1.comment,t1.date_added,t1.notify,t3.name as admin_name,t1.sttaf_assignee_id,t2.status_comment,t4.name as kitchen_name,t4.contact_no as kitchen_contact,t2.status_name,t2.status_color');
            $this->db->from('order_status_history as t1');
            $this->db->join('order_statuses as t2', 't1.status_id=t2.status_id');
            $this->db->join('adminlogin as t3', 't1.admin_id=t3.id', 'left');
            $this->db->join('kitchen as t4', 't4.id=t1.kitchen_id', 'left');
            $this->db->where('t1.order_id', $orderid);
            $this->db->order_by('t1.date_added', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

}
?>

