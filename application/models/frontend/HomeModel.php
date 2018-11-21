<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HomeModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getRows($tbname = null, $colmn = null, $condition = null, $order = null, $groupby = null) {

        if (!empty($tbname) && !empty($colmn)) {
            $this->db->select($colmn);
            $this->db->from($tbname);
            if (!empty($condition)) {
                $this->db->where($condition);
            }
            if (!empty($order)) {
                $column = $order['column'];
                $orderby = $order['orderby'];
                $this->db->order_by($column, $orderby);
            }
            $query = $this->db->get();
            //echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    public function get_search_product($tbname = null, $colmn = null, $condition = null, $order = null, $like = null, $limit = null) {

        if (!empty($tbname) && !empty($colmn)) {
            $this->db->select($colmn);
            $this->db->from($tbname);
            if (!empty($condition)) {
                $this->db->where($condition);
            }
            if (!empty($like)) {
                $this->db->like($like);
            }
            if (!empty($limits)) {
                $limit = $limits['limit'];
                $start = $limits['start'];
                $this->db->limit($limit, $start);
            }
            if (!empty($order)) {
                $column = $order['column'];
                $orderby = $order['orderby'];
                $this->db->order_by($column, $orderby);
            }
            $query = $this->db->get();
            //echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    public function get_category_product($category = null, $cafeid = null, $likecond = null, $limit = null) {

        if (!empty($category) && !empty($cafeid)) {
            $this->db->select('t1.*');
            $this->db->from('product as t1');
            $this->db->join('product_category as t2', 't2.product_id=t1.pid', 'left');
            $this->db->where('t2.category_id', $category);
            $this->db->where('t1.service_id', $cafeid);
            if (!empty($likecond)) {
                $this->db->like($likecond);
            }
            if (!empty($limits)) {
                $limit = $limits['limit'];
                $start = $limits['start'];
                $this->db->limit($limit, $start);
            }
            $this->db->order_by('t1.created_date', 'DESC');
            $query = $this->db->get();
            // echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    public function getLocations($tbname = null, $name = null, $condiont = null) {

        if (!empty($tbname) && !empty($name)) {

            //$this->db->select('id,name');
            $this->db->select('name');
            $this->db->from($tbname);
            $this->db->like('name', $name);
            if (!empty($condiont)) {
                $this->db->where($condiont);
            }
            $query = $this->db->get();
            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
                // return false;
            }
        }
    }

    public function getRow($tbname = null, $colmn = null, $condition = null, $order = null, $groupby = null) {
        if (!empty($tbname) && !empty($colmn)) {
            $this->db->select($colmn);
            $this->db->from($tbname);
            if (!empty($condition)) {
                $this->db->where($condition);
            }
            if (!empty($order)) {
                $column = $order['column'];
                $orderby = $order['orderby'];
                $this->db->order_by($column, $orderby);
            }
            $query = $this->db->get();
            //$this->db->last_query();
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

    public function getTwoJoins($select = null, $table1 = null, $table2 = null, $join1 = null, $type = null, $where = null, $orderby = null, $single = null) {

        if (!empty($select))
            $this->db->select($select);
        else
            $this->db->select('*');

        if (!empty($table1) && !empty($table2) && !empty($join1)) {
            $this->db->from($table1);

            if (!empty($type)) {
                $this->db->join($table2, $join1, $type);
            } else {
                $this->db->join($table2, $join1);
            }
        }
        if (!empty($where))
            $this->db->where($where);
        if (!empty($orderby)) {
            $column = $orderby['column'];
            $orderby1 = $orderby['orderby'];
            $this->db->order_by($column, $orderby1);
        }

        $query = $this->db->get();
        // echo $this->db->last_query(); die;
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

    public function insert($tablename, $data) {

        if (isset($tablename) && isset($data)) {
            $this->db->insert($tablename, $data);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
                return $insert_id;
            } else {
                return false;
            }
        }
        return $this->db->insert_id();
    }

    public function update($tablename, $data, $condition) {

        if (isset($tablename) && isset($data) && !empty($condition)) {
            $this->db->where($condition);
            $this->db->update($tablename, $data);
            $update_id = $this->db->affected_rows();
            if ($update_id) {
                return $update_id;
            } else {
                return false;
            }
        }
    }

    public function getS($tbname = null) {

        if (!empty($tbname)) {
            $this->db->select('lu,su');
            $this->db->from($tbname);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

}
?>

