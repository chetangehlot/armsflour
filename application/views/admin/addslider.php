<?php include_once('include/sidebar.php'); ?>
<?php include_once('include/header.php'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add School </h2>
                    <div class="clearfix"></div>
                     <?php
                        if ($this->session->flashdata('success')) {
                            echo '<br>';
                            echo '<div class="alert alert-success" role="alert"> ' . $this->session->flashdata('success') . '</div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo '<br>';
                            echo '<div class="alert alert-danger" role="alert"> <strong>Oh snap!</strong> ' . $this->session->flashdata('error') . '</div>';
                        }
                        ?>
                  </div>
<div class="x_content">
                    <br />
                    <?php echo form_open_multipart('admin/dashboard/index', ['class'=>'form-horizontal form-label-left']) ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Slider Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            
                          <?php echo form_input(['name'=>'name', 'class'=>'form-control col-md-7 col-xs-12', 'required'=>'required', 'placeholder'=>'Enter Name']) ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Slider Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <?php echo form_upload(['name'=>'image', 'size'=>'20', 'multiple'=>'true', 'type'=>'file', 'required'=>'required']) ?>
                            
                        </div>
                      </div>
                                        
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <a href="index" class="btn btn-danger">Cancel</a>
                        <?php echo form_submit(['name'=>'submit', 'value'=>'Upload Image', 'class'=>'btn btn-primary']) ?>
                        </div>
                      </div>
                              
                    <?php echo form_close(); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
            
  <?php include_once('include/footer.php'); ?>
     
