<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
        echo CI_VERSION;
    }

    public function index() {
       
        $order_by = array('column' => 't1.priority_no', 'orderby' => 'ASC');

        $joins = 't1.parent_id=t2.id';
        $data['allcategory'] = $this->common->getTwoJoins('t1.id,t1.image,t1.created_date,t1.modify_date,t1.is_active,t1.name as category,t2.name as parent_category,t1.priority_no', 'category as t1', 'category as t2', $joins, 'left', '', $order_by);
        $this->maintheme('list', $data);
    }

    public function add($ids1 = null) {
        
        
        $data['list'] = '';
        $data['page_title'] = 'Add';
        $ids = '';
        $cateid = '';
        $parent_id = 0;

        if (!empty($ids1)) {
            $data['page_title'] = 'Edit';
            $ids = base64_decode($ids1);
            $cond = array('id' => $ids);
            $data['category'] = $this->common->getRow('category', '*', $cond);
        }

        if ($this->input->post()) {
            $categorybanner_image = '';
            $category_image = '';
            $this->form_validation->set_rules('name', 'Name', 'required');
            // $this->form_validation->set_rules('description', 'Description', 'required');
            $categoryid = $this->input->post('categoryid') ? base64_decode($this->input->post('categoryid')) : '';
            if ($categoryid == '') {
                if (empty($_FILES['image']['name'])) {
                    $this->form_validation->set_rules('image', 'Category image', 'required');
                }
            }

            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $datetme = date("Y-m-d");


                if (!empty($_FILES['image']['name'])) {
                    try {
                        $category_image = $this->fileuploadmain($_FILES);
                    } catch (Exception $e) {
                        log_message('category images', $e->getMessage());
                    }
                }

                if ((empty($_FILES['image']['name']) || (!empty($category_image)))) {

                    if (!empty($categoryid)) {

                        $status = $this->input->post('status');
                        $dataarray = array(
                            'name' => $this->input->post('name'),
                            'priority_no' => $this->input->post('priority_no'),
                            'modify_date' => $datetme,
                            'is_active' => $status
                        );

                        if (!empty($category_image)) {
                            $dataarray['image'] = $category_image;
                        }



                        $data['page_title'] = 'Edit';
                        $where = array('id' => $categoryid);
                        $lastid = $this->common->update('category', $dataarray, $where);
                        if ($lastid) {
                            $this->session->set_flashdata('success', ' Category has been ' . $data['page_title'] . '!');
                        } else {
                            $this->session->set_flashdata('success', ' No Record update');
                        }

                        redirect('admin/category/add/' . base64_encode($categoryid));
                    } else {

                        $data['page_title'] = 'Add';
                        $dataarray = array(
                            'name' => $this->input->post('name'),
                            'priority_no' => $this->input->post('priority_no'),

                            'image' => $category_image,
                            'created_date' => $datetme
                        );

                        $lastid = $this->common->insert('category', $dataarray);
                    }
                    if ($lastid) {
                        $this->session->set_flashdata('success', 'Category has been ' . $data['page_title'] . '!');
                        redirect('admin/category/add');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                    }
                }
            }
        }

        $this->maintheme('add', $data);
    }

    public function del($id = NULL) {
        $id = base64_decode($id);
        if ($id != '' && $id != NULL) {
            $this->load->model('admin/Common');
            $value = $this->common->del('category', $id);

            if ($value) {
                $this->session->set_flashdata('success', ' Category has been deleted!');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong!');
            }
        }
        redirect('admin/category');
    }

// this function is use for the get theme function

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/category/' . $page, $data);
        }
        $this->load->view('admin/include/footer');
    }

    public function fileuploadmain($filpost) {

        $config['upload_path'] = 'assets/category/';
        $config['allowed_types'] = 'jpg|png|jpeg|JPG';
        $config['max_size'] = 5000;
        $config['min_width'] = 400;
        $config['min_height'] = 300;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) {
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
            $this->form_validation->set_rules('file', 'File', 'required');
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

    public function fileuploadmainbanner($filpost) {


        $config1['upload_path'] = 'assets/category/';
        $config1['allowed_types'] = 'jpg|png|jpeg|JPG';
        $config1['max_size'] = 5000;
        $config1['min_width'] = 1400;
        $config1['min_height'] = 300;

        $this->load->library('upload', $config1);
        if (!$this->upload->do_upload('banner_image')) {
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
            $this->form_validation->set_rules('banner_image', 'File', 'required');
            return false;
        } else {

            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $thumrest = $this->image_thumb($config1['upload_path'], $file_name);
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