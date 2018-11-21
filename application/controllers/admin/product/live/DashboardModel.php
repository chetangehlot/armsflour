<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function totalsels($date = null) {

        $this->db->select_sum('order_total');
        $this->db->where('payment', 'RECEIVED');
        $this->db->where('status_id', '5');
        if ($date != null) {
            $this->db->where('order_date', $date);
        }
        $result = $this->db->get('orders')->row();
        //echo '<br>' . $this->db->last_query();
        if (count($result->order_total) > 0) {
            return $result->order_total;
        } else {
            return false;
        }
    }

    public function countValue($table = null, $column_name = null) {

        if ((!empty($table)) && (!empty($column_name))) {
            $this->db->select('Count(' . $column_name . ') as totalorders');
            $this->db->from($table);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->totalorders;
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }

    public function getCustomerAddress($where = null, $orderby = null, $groupby = null, $limits = null) {

        $this->db->select('t1.address_id,t1.address_1,t1.address_2,t1.postcode,t1.created_date,t2.city_name,t3.name');
        $this->db->from('addresses t1');
        $this->db->join('cities t2', 't1.city_id=t2.city_id', 'left');
        $this->db->join('customers t3', 't3.id=t1.customer_id', 'left');

        if (!empty($where))
            $this->db->where($where);
        if (!empty($orderby)) {
            $column = $orderby['column'];
            $orderby1 = $orderby['orderby'];
            $this->db->order_by($column, $orderby1);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }

        if (!empty($limits)) {
            $limit = $limits['limit'];
            $start = $limits['start'];
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
//echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {

            if (!empty($single)) {
                return $query->row();
            } else {
                return $query->result_array();
            }
        } else {
            return false;
        }
    }

    public function getCustomerAddressAll($where = null, $orderby = null) {

        $this->db->select('t1.address_id');
        $this->db->from('addresses t1');
        $this->db->join('cities t2', 't1.city_id=t2.city_id', 'left');
        $this->db->join('customers t3', 't3.id=t1.customer_id', 'left');

        if (!empty($where))
            $this->db->where($where);
        if (!empty($orderby)) {
            $column = $orderby['column'];
            $orderby1 = $orderby['orderby'];
            $this->db->order_by($column, $orderby1);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }

        if (!empty($limits)) {
            $limit = $limits['limit'];
            $start = $limits['start'];
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
//echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return count($query->result_array());
        } else {
            return false;
        }
    }

    public function getMoreJoin($select = null, $from_table = null, $join = null, $where = null, $orderby = null, $limits = null) {
        if (!empty($from_table) && !empty($join)) {


            if ($select)
                $this->db->select($select);
            else
                $this->db->select('*');

            $this->db->from($from_table);

            foreach ($join as $ta) {

                $this->db->join($ta['table1'], $ta['join1']);
            }

            if (!empty($where))
                $this->db->where($where);
            if (!empty($orderby)) {
                $column = $orderby['column'];
                $orderby1 = $orderby['orderby'];
                $this->db->order_by($column, $orderby1);
            }
            if (!empty($groupby)) {
                $this->db->group_by($groupby);
            }

            if (!empty($limits)) {
                $limit = $limits['limit'];
                $start = $limits['start'];
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            //echo $this->db->last_query();
            if ($query->num_rows() > 0) {

                if (!empty($single)) {
                    return $query->row();
                } else {
                    return $query->result_array();
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
?>
    