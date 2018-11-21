<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Add Slider </h2>
                        <div class="clearfix"></div>

                        <?php
                        if ($this->session->flashdata('success')) {
                            echo '<br>';
                            echo '<div class="alert alert-success " role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button> ' . $this->session->flashdata('success') . '</div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo '<br>';
                            echo '<div class="alert alert-danger " role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button> <strong>Oh snap!</strong> ' . $this->session->flashdata('error') . '</div>';
                        }
                        if (!empty(validation_errors())) {
                            echo ' <div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>Please fill required filed...<br>
                             ' . validation_errors() . '
                            </div>';
                        }
                        ?>
                    </div>
                    <div class="x_content">
                        <br />
                        <?php echo form_open_multipart('admin/slider/add', ['class' => 'form-horizontal form-label-left', 'id' => 'sliderupload']) ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Heading <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <?php echo form_input(['name' => 'heading', 'value' => isset($sliderlist->heading) ? $sliderlist->heading : '', 'class' => 'form-control col-md-7 col-xs-12', 'required' => '', 'placeholder' => 'Enter heading']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Description <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <?php echo form_textarea(['name' => 'desc', 'value' => isset($sliderlist->description) ? $sliderlist->description : '', 'class' => 'form-control col-md-7 col-xs-12', 'required' => '', 'placeholder' => 'Enter description']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Slider Image <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="hdnsliderids" value="<?php echo isset($sliderlist->id) ? base64_encode($sliderlist->id) : ''; ?>">

                                <?php
                                if (isset($sliderlist->id)) {
                                    echo form_input(['name' => 'file', 'id' => 'file', 'size' => '20', 'type' => 'file']);
                                } else {
                                    echo form_input(['name' => 'file', 'id' => 'file', 'required' => '', 'size' => '20', 'type' => 'file']);
                                }
                                ?>
                                <label>Image width 1300px or greater and height 400px or greater is mandatory.</label>
                                <?php
                                if (isset($sliderlist->image)) {
                                    ?>
                                    <img src="<?php echo base_url('assets/slider/thumbs/') . $sliderlist->image; ?>"
                                    <?php
                                }
                                ?>
                                     <br>
                                <span style="display: none;" id="errorcls" class="alert alert-danger">Please uploaded png formate</span>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php echo form_submit(['name' => 'submit', 'id' => 'sbtn', 'value' => 'Upload Image', 'class' => 'btn btn-primary']) ?>
                                <a href="<?php echo base_url('admin/slider'); ?>" class="btn btn-danger">Cancel</a>
                                <img style="display: none;" width="50px" height="50px" id='loaderimg' src="<?php echo base_url(); ?>assets/images/Loading.gif" >
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="clearfix"></div>

