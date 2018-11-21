<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>My Account Setting</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <?php
                        $attr = array('id' => 'demo-form2', 'class' => 'form-horizontal form-label-left');
                        echo form_open('admin/dashboard/update_passwrod', $attr);
                        ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Current Password <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                $data = array(
                                    'name' => 'cpassword',
                                    'id' => 'password',
                                    'required' => 'required',
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'type' => 'password'
                                );
                                echo form_input($data);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="newpassword">New Password <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                $data = array(
                                    'name' => 'npassword1',
                                    'id' => 'npassword1',
                                    'required' => 'required',
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'type' => 'password'
                                );
                                echo form_input($data);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="againpassword" class="control-label col-md-3 col-sm-3 col-xs-12">Again Enter New Password</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                $data = array(
                                    'name' => 'npassword2',
                                    'id' => 'npassword2',
                                    'required' => 'required',
                                    'class' => 'form-control col-md-7 col-xs-12',
                                    'type' => 'password'
                                );
                                echo form_input($data);
                                ?>
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <a href="<?php echo base_url('admin/dashboard/index'); ?>"><button class="btn btn-danger" type="button">Cancel</button></a>
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>