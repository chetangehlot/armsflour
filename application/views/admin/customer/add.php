<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php
                            echo $page_title;
                            ?> Customer </h2>
                        <a class="pull-right" href="<?php echo $action_url . '/customer'; ?>"><button type="button" class="btn btn-primary">Customer List</button></a>
                        <div class="clearfix"></div>

                        <?php
                        if ($this->session->flashdata('success')) {
                            echo ' <div class = "alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                            <strong>Success!</strong>' . $this->session->flashdata('success') . '
                            </div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo ' <div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                            <strong>Danger!</strong> ' . $this->session->flashdata('error') . '
                            </div>';
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
                        <?php echo form_open_multipart('', ['class' => 'form-horizontal form-label-left', 'id' => 'customerids']) ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo form_input(['name' => 'name', 'value' => isset($customer->name) ? $customer->name : set_value('coupon_code'), 'class' => 'form-control col-md-7 col-xs-12', 'required' => '', 'placeholder' => 'Enter Name']) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left" data-toggle="tooltip" title="Mobile">Mobile <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">

                                <?php echo form_input(['name' => 'mobile', 'required' => '', 'value' => isset($customer->mobile) ? $customer->mobile : set_value('mobile'), 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Enter Mobile']) ?>
                                <?php echo form_error('mobile', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left" data-toggle="tooltip"  title="Email">Email</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo form_input(['name' => 'email', 'value' => isset($customer->email) ? $customer->email : set_value('email'), 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Enter Email']) ?>
                                <?php echo form_error('email', '<span class="text-danger">', '</span>'); ?>
                            </div>
                        </div>
                        <?php if (!empty($customer->id)) {
                            ?> 
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="status">
                                        <option <?php echo $customer->isactive == 1 ? 'selected="selected"' : ''; ?> value="1">enable</option>
                                        <option <?php echo $customer->isactive == 0 ? 'selected="selected"' : ''; ?> value="0">disable</option>
                                    </select>

                                </div>
                            </div>
                        <?php } ?>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php echo form_submit(['name' => 'submit', 'id' => 'sbtn', 'value' => $page_title . ' Customer', 'class' => 'btn btn-primary']) ?>
                                <a href="<?php echo $action_url . '/customer'; ?>" class="btn btn-danger">Cancel</a>
                                <input name="customerid" type="hidden"value="<?php echo isset($customer->id) ? base64_encode($customer->id) : ''; ?>">
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