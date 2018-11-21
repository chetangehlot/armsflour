<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Common extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
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

    public function del($tablename, $ids) {

        $this->db->where('id', $ids);
        $this->db->delete($tablename);
        return $this->db->affected_rows();
    }

    public function delbyrefrence($tablename, $ids, $whereid) {

        $this->db->where($whereid, $ids);
        $this->db->delete($tablename);
        return $this->db->affected_rows();
    }

    public function adduser($header) {

        $lastid = 0;

        foreach ($header as $value) {

            $activation_code = trim($value['A']);


            if (!empty($activation_code)) {

                $checkisnotexist = $this->existindb($activation_code);

                if (empty($checkisnotexist)) {
                    $role = trim($value['B']);
                    $useraccess = trim($value['C']);

                    $dataarray = array(
                        'activation_code' => $activation_code,
                        'useraccess' => $useraccess,
                        'role' => $role
                    );

                    $lastid = $this->insert('userlist', $dataarray);
                }
            }
        }
        return $lastid;
    }

    public function existindb($activation_code) {
        $this->db->select('id');
        $this->db->from('userlist');
        $this->db->where('activation_code="' . $activation_code . '"');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        } else {
            return false;
        }
    }

}
?>

