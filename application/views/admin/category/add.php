<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo $page_title; ?> Category </h2>
                        <a class="pull-right" href="<?php echo site_url() . '/admin/category'; ?>"><button type="button" class="btn btn-primary">Category List</button></a>
                        <div class="clearfix"></div>

                        <?php
                        if ($this->session->flashdata('success')) {
                            echo ' <div class = "alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    x</button>
                            <strong>Success!</strong>' . $this->session->flashdata('success') . '
                            </div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo ' <div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    x</button>
                            <strong>Danger!</strong> ' . $this->session->flashdata('error') . '
                            </div>';
                        }
                        if (!empty(validation_errors())) {
                            echo ' <div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    x</button>Please fill required filed...<br>
                             ' . validation_errors() . '
                            </div>';
                        }
                        ?>
                    </div>
                    <div class="x_content">
                        <br />
                        <?php echo form_open_multipart('admin/category/add', ['class' => 'form-horizontal form-label-left', 'id' => 'servicesid']) ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Category Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo form_input(['name' => 'name', 'value' => isset($category->name) ? $category->name : '', 'class' => 'form-control col-md-7 col-xs-12', 'required' => '', 'placeholder' => 'Enter Category Name']) ?>
                            </div>
                        </div>
                        
                        <div class="form-group" id="is-special" style="<?php echo (isset($category->is_special) && $category->is_special > 0) ? '' : 'display:none;'; ?>" >
                            <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Category Banner Image <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="banner_image" id="banner_image" />
                                <?php
                                if (isset($category->banner_image) && $category->is_special) {

                                    $imagepath1 = base_url('assets/category/thumbs/') . $category->banner_image;
                                    if (@getimagesize($imagepath1)) {
                                        ?>
                                        <img src="<?php echo $imagepath1; ?>" class="img-thumbnail img-responsive" />
                                        <?php
                                    }
                                }
                                ?>
                                <label class="text-info">Prefer image with and height are 1500*400 pixel</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Category Image <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php if (!empty($category->id)) {
                                    ?> 
                                    <input type="file" name="image" />
                                <?php } else { ?>
                                    <input type="file" name="image" required="" />
                                    <?php
                                }
                                if (isset($category->image)) {

                                    $imagepath = base_url('assets/category/thumbs/') . $category->image;
                                    if (@getimagesize($imagepath)) {
                                        ?>
                                        <img src="<?php echo $imagepath; ?>" class="img-thumbnail img-responsive" />
                                        <?php
                                    }
                                }
                                ?>

                                <label class="text-info">Prefer image with and height are 400*400 pixel</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Priority </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input  class="form-control" type="number" value="<?php echo isset($category->priority_no) ? $category->priority_no : ''; ?>" name="priority_no" >
                                <span>(If enter 1,2 so on then it will appear on first, second so on)</span>
                            </div>
                        </div>
                       
                        <?php if (!empty($category->id)) {
                            ?> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="status">
                                        <option <?php echo $category->is_active == 1 ? 'selected="selected"' : ''; ?> value="1">enable</option>
                                        <option <?php echo $category->is_active == 0 ? 'selected="selected"' : ''; ?> value="0">disable</option>
                                    </select>

                                </div>
                            </div>
                        <?php } ?>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                <?php echo form_submit(['name' => 'submit', 'id' => 'sbtn', 'value' => $page_title . ' Category', 'class' => 'btn btn-primary']) ?>
                                <a href="<?php echo base_url('admin/category'); ?>" class="btn btn-danger">Cancel</a>
                                <input name="categoryid" type="hidden"value="<?php echo isset($category->id) ? base64_encode($category->id) : ''; ?>">
                                <img style="display: none;" width="50px" height="50px" id='loaderimg' src="<?php echo base_url(); ?>assets/images/Loading.gif" >
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>