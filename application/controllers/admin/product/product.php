<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
    }

    public function index() {

        $pagination_url = base_url('admin/product');

        if ($this->input->get('page')) {
            $filter['page'] = (int) $this->input->get('page');
        } else {
            $filter['page'] = '';
        }
        $condition1 = '';
        if (!empty($this->input->get('filter_search'))) {
            $data['filter_search'] = $this->input->get('filter_search');
            if (is_numeric($data['filter_search']))
                $condition1 .= " pid =" . $data['filter_search'];
            else
                $condition1 .= " pname  LIKE '%" . $data['filter_search'] . "%' ";
        } else {
            $data['filter_search'] = '';
        }

        $total_row1 = $this->common->getRow('product', 'count(pid) as total', $condition1);

        if (!empty($total_row1)) {
            $total_row = $total_row1->total;
        } else {
            $total_row = 0;
        }
        $showpage = 20;
        $data ['links'] = getPagination($total_row, $pagination_url);

        if (!empty($filter['page']) AND $filter ['page'] !== 0) {
            $filter['page'] = $filter['page'];
        } else {
            $filter['page'] = 0;
        }
        $data['action_url'] = base_url('admin');
        $order_by = array('column' => 'created_date', 'orderby' => 'DESC');
        $data['plist'] = $this->common->getRows('product', 'pid,pname,image,description,price,is_active,created_date,modify_date', $condition1, $order_by, '', array('limit' => $showpage, 'start' => $filter['page']));

        $this->maintheme('list', $data);
    }


    public function add($ids1 = null) {
        $data['action_url'] = base_url('admin');
        $data['servicelist'] = '';
        $data['page_title'] = 'Add';
        $ids = '';
        $productid = '';
        $filename = '';

// category can add on multiple on the product

        $order_by1 = array('column' => 'created_date', 'orderby' => 'DESC');
        $where1 = array('is_active' => '1');
        $data['pcategory'] = $this->common->getRows('category', 'id,name', $where1, $order_by1);
        
        if (!empty($ids1)) {
            $data['page_title'] = 'Edit';
            $ids = base64_decode($ids1);
            $cond = array('pid' => $ids);
            $data['productlist'] = $this->common->getRow('product', '*', $cond);

            $condcat = array('product_id' => $ids);
            //produt category
            $vendorcat = [];
            $categoryproduct = $this->common->getRows('product_category', '*', $condcat);

            if (!empty($categoryproduct)) {
                foreach ($categoryproduct as $vendor_category => $value) {
                    $vendorcat[] = $value['category_id'];
                }
            }

            $data['categoryproduct'] = $vendorcat;

            // product attribute
            $data['productattribute'] = $this->common->getRows('product_attribute', '*', $condcat);
        }
        if ($this->input->post()) {
            
            $this->form_validation->set_rules('name', 'Product Name', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');
            $catarray = $this->input->post('categoryid');
            $optionname = $this->input->post('optionname');
            $optiondesc = $this->input->post('des');
            $optionprice = $this->input->post('optionprice');

            if ((empty($catarray[0])) || empty($catarray)) {

                $this->form_validation->set_rules('categoryid[]', 'Category', 'required');
            }
            
            $productid = $this->input->post('productid') ? base64_decode($this->input->post('productid')) : '';


            if ($this->form_validation->run() == FALSE) {
                
            } else {

                if (!empty($_FILES['image']['name'])) {

                    $filename = $this->fileuploadmain($_FILES);
                }
                if ((empty($_FILES['image']['name']) || (!empty($filename)))) {

                    $datetme = date("Y-m-d H:i:s");
                    $name = $this->input->post('name');

                    $price = $this->input->post('price');
                    $description = $this->input->post('description');

                    if (!empty($productid)) {
                        if (!empty($filename)) {
                            $dataarray = array(
                                'pname' => $name,
                                'description' => $description,
                                'price' => $price,
                                'image' => $filename,
                                'is_active' => $this->input->post('status'),
                                'modify_date' => $datetme
                            );
                        } else {
                            $dataarray = array(
                                'pname' => $name,                               
                                'price' => $price,                                
                                'description' => $description,
                                'is_active' => $this->input->post('status'),
                                'modify_date' => $datetme
                            );
                        }

                        $data['page_title'] = 'Edit';
                        $where = array('pid' => $productid);
                        $lastid = $this->common->update('product', $dataarray, $where);

                        if ($lastid) {

                            $delattribute = $this->common->delbyrefrence('product_attribute', $productid, 'product_id');
                            $delcategory = $this->common->delbyrefrence('product_category', $productid, 'product_id');

                            // after the update product the attribute and category will be deleted

                            foreach ($catarray as $cat) {
                                $catdata = array(
                                    'product_id' => $productid,
                                    'category_id' => $cat
                                );
                                $this->common->insert('product_category', $catdata);
                            }
                            if (!empty($optionname)) {

                                $i = 0;
                                foreach ($optionname as $oname) {
                                    $podattrdata = '';
                                    if ((!empty($oname)) && (!empty($optionprice[$i]))) {
                                        $podattrdata = array(
                                            'product_id' => $productid,
                                            'paname' => $oname,
                                            'description' => $optiondesc[$i],
                                            'pavalue' => $optionprice[$i]
                                        );
                                        $this->common->insert('product_attribute', $podattrdata);
                                    }
                                    $i++;
                                }
                            }

                        // insert log for all activity
                            $lourl = base_url() . 'admin/product/add/' . $productid;
                            $ip = $_SERVER['REMOTE_ADDR'];
                            $logdata = array(
                                'url' => $lourl,
                                'subject' => 'Update product',
                                'type' => 'admin',
                                'ip' => $ip,
                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                'userid' => $this->session->userdata('admin_id'),
                                'username' => $this->session->userdata('username'),
                                'created_date' => $datetme
                            );
                            $this->common->insert('user_log', $logdata);
                            $this->session->set_flashdata('success', ' Product has been ' . $data['page_title'] . '!');

                            $this->session->set_flashdata('success', ' Recored has been ' . $data['page_title'] . '!');
                        } else {
                            $this->session->set_flashdata('success', 'No record update');
                        }
                        redirect('admin/product/add/' . base64_encode($productid));
                    } else {
// For the add product functionality

                        $data['page_title'] = 'Add';
                        $dataarray = array(
                            'pname' => $name,
                            'description' => $description,
                            'price' => $price,
                            'image' => $filename,
                            'created_date' => $datetme
                        );
                        $lastid = $this->common->insert('product', $dataarray);
                    }
                    if ($lastid) {

                        foreach ($catarray as $cat) {
                            $catdata = array(
                                'product_id' => $lastid,
                                'category_id' => $cat
                            );
                            $this->common->insert('product_category', $catdata);
                        }
                        if (!empty($optionname)) {

                            $i = 0;
                            foreach ($optionname as $oname) {
                                $podattrdata = '';
                                if ((!empty($oname)) && (!empty($optionprice[$i]))) {
                                    $podattrdata = array(
                                        'product_id' => $lastid,
                                        'paname' => $oname,
                                        'description' => $optiondesc[$i],
                                        'pavalue' => $optionprice[$i]
                                    );
                                    $this->common->insert('product_attribute', $podattrdata);
                                }
                                $i++;
                            }
                        }

// insert log for all activity
                        $lourl = base_url() . 'admin/product/add' . $lastid;
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $logdata = array(
                            'url' => $lourl,
                            'subject' => 'Insert product',
                            'type' => 'admin',
                            'ip' => $ip,
                            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'userid' => $this->session->userdata('admin_id'),
                            'username' => $this->session->userdata('username'),
                            'created_date' => $datetme
                        );
                        $this->common->insert('user_log', $logdata);
                        $this->session->set_flashdata('success', ' Product has been ' . $data['page_title'] . '!');
                        redirect('admin/product/add');
                    } else {
                        $this->session->set_flashdata('error', ' Something went wrong!');
                    }
                }
            }
        }

        $this->maintheme('add_details', $data);
    }

    public function del($id = NULL) {
        $id = base64_decode($id);
        if ($id != '' && $id != NULL) {
            $this->load->model('admin/Common');
            $value = $this->common->delbyrefrence('product', $id, 'pid');

            if ($value) {

                $lourl = base_url() . 'admin/product/del' . $id;
                $ip = $_SERVER['REMOTE_ADDR'];
                $logdata = array(
                    'url' => $lourl,
                    'subject' => 'Delete product with servers',
                    'type' => 'admin',
                    'ip' => $ip,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'userid' => $this->session->userdata('admin_id'),
                    'username' => $this->session->userdata('username'),
                    'created_date' => date("Y-m-d H:i:s")
                );
                $this->common->insert('user_log', $logdata);
                $attribute = $this->common->delbyrefrence('product_attribute', $id, 'product_id');
                $category = $this->common->delbyrefrence('product_category', $id, 'product_id');

                $this->session->set_flashdata('success', ' Product detail has been deleted!');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong!');
            }
        }
        redirect('admin/product');
    }

// this function is use for the get theme function

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/product/' . $page, $data);
        }
        $this->load->view('admin/include/footer');
    }

    public function fileuploadmain($filpost) {

        $config['upload_path'] = 'assets/products/';
        $config['allowed_types'] = 'jpg|png|jpeg|JPG';
        $config['max_size'] = 5000;
        $config['min_width'] = 250;
        $config['min_height'] = 250;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) {
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
            $this->form_validation->set_rules('sliderfile', 'attachement', 'required');
            return false;
        } else {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $thumrest = $this->image_thumb($config['upload_path'], $file_name);
            if ($thumrest) {
                return $file_name;
            } else {
                return FALSE;
            }
        }
    }

    public function image_thumb($folder_name, $image_name) {
        $origin_folder = $folder_name;
        $width = 200;
        $height = 200;
        $folder_name = $folder_name . '/thumbs';
        if (file_exists($folder_name) == false) {
            mkdir($folder_name, 0777);
        }
// Path to image thumbnail
        $image_thumb = dirname($folder_name . '/' . $image_name) . '/' . $image_name;

        if (!file_exists($image_thumb)) {
// LOAD LIBRARY
            $this->load->library('image_lib');

// CONFIGURE IMAGE LIBRARY
            $config['image_library'] = 'gd2';
            $config['source_image'] = $origin_folder . '/' . $image_name;
            $config['new_image'] = $image_thumb;
            $config['maintain_ratio'] = TRUE;
            $config['height'] = $height;
            $config['width'] = $width;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
            return true;
        }
    }

}

?>