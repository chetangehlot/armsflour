<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (empty($this->session->userdata('is_logged_in'))) {
            redirect('admin');
        }
        $this->load->model('common');
    }

    public function index() {

        $data['sliderlist'] = $this->common->getRows('slider', 'id,heading,description,image,createddate', array('is_active' => 1), array('column' => 'createddate', 'orderby' => 'DESC'));

        $this->maintheme('slider/sliderlist', $data);
    }

    public function add($ids1 = null) {

        $this->load->model('Common');
        $data['page_title'] = 'Add';

        if (!empty($ids1)) {
            $data['page_title'] = 'Edit';
            $ids = base64_decode($ids1);
            $cond = array('id' => $ids);
            $data['sliderlist'] = $this->common->getRow('slider', 'id,heading,description,image,createddate', $cond);
        }

        if ($this->input->post()) {
            $slider_image = '';
            $datetme = date('Y-m-d H:i:s');
            $this->form_validation->set_rules('heading', 'heading', 'required');
            $this->form_validation->set_rules('desc', 'description', 'required');
            $hdnsliderids = $this->input->post('hdnsliderids') ? base64_decode($this->input->post('hdnsliderids')) : '';

            if ($hdnsliderids == '') {
                if (empty($_FILES['file']['name'])) {
                    $this->form_validation->set_rules('image', 'Slider Image', 'required');
                }
            }
            if ($this->form_validation->run() == FALSE) {
                
            } else {

                if (!empty($_FILES['file']['name'])) {
                    try {
                        $slider_image = $this->fileuploadmain($_FILES);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }

                if ($hdnsliderids) {

                    $dataarray12 = array(
                        'heading' => $this->input->post('heading'),
                        'description' => $this->input->post('desc'),
                        'modifieddate' => $datetme
                    );
                    if (!empty($slider_image)) {
                        $dataarray12['image'] = $slider_image;
                    }
                    $lastid = $this->Common->update('slider', $dataarray12, array('id' => $hdnsliderids));
                    if ($lastid) {
                        $this->session->set_flashdata('success', ' Slider has been ' . $data['page_title'] . '!');
                    } else {
                        $this->session->set_flashdata('success', ' No Record update');
                    }

                    redirect('admin/slider/add/' . base64_encode($hdnsliderids));
                } else {


                    $dataarray = array(
                        'heading' => $this->input->post('heading'),
                        'description' => $this->input->post('desc'),
                        'image' => $slider_image,
                        'createddate' => $datetme
                    );

                    $lastid = $this->Common->insert('slider', $dataarray);

                    if ($lastid) {
                        $this->session->set_flashdata('success', 'Slider uploaded succefully!');
                        redirect('admin/slider');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                    }
                }
            }
        }
        $this->maintheme('slider/addslider', $data);
    }

    public function del($id = NULL) {

        if ($id != '' && $id != NULL) {
            $this->load->model('admin/Common');
            $value = $this->Common->del('slider', $id);

            if ($value) {
                $this->session->set_flashdata('success', ' Slider deleted succefully!');
            } else {
                $this->session->set_flashdata('error', ' Something went wrong!');
            }
        }
        redirect('admin/slider');
    }

    public function fileuploadmain($filpost) {

        $config['upload_path'] = 'assets/slider/';
        $config['allowed_types'] = 'jpg|png|jpeg|JPG';
        $config['max_size'] = 5000;
        $config['min_width'] = 1300;
        $config['min_height'] = 300;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
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

    public function maintheme($page = null, $data = null) {

        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/include/header');
        if (!empty($page)) {
            $this->load->view('admin/' . $page, $data);
        }
        $this->load->view('admin/include/footer', $data);
    }

}

?>