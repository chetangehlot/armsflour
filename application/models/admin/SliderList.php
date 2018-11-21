<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SliderList extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getlist() {

        $this->db->select('id,menu_id,heading,description,image,web_img,createddate,url');
        $this->db->from('slider');
        $this->db->where('is_active=1');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->result();
            return $row;
        }
    }

}
?>

