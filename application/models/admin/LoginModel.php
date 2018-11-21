<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LoginModel extends CI_Model {

	function __construct() 
        {
		parent::__construct();
			$this->load->database();

	}
        public function login_valid($username,$password)
	{
            //echo 546546; die();
            $user_data = array();
            $sql="SELECT * FROM adminlogin WHERE username = ".$this->db->escape($username)." AND password = ".$this->db->escape(md5($password));
            $customer_query = $this->db->query($sql);
            //print_r($customer_query); die();
            if ($customer_query->num_rows()) {
                $user_data =  $customer_query->row(); 
                //print_r($user_data); die();
                return $user_data;
            }
            else 
                {
                    return false;
                }
        }
}
?>

