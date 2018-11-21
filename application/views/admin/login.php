<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arms Flour Admin Panel | </title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>assets/admin/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>assets/admin/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
             <?php echo form_open('admin/login/admin_login', ['class'=>'form-horizontal form-label-left']) ?>
              <h1>Login Form</h1>
              <div>
                 <?php echo form_input(['name'=>'username', 'class'=>'form-control', 'required'=>'required', 'placeholder'=>'Username']) ?>
              </div>
              <div>
                 <?php echo form_password(['name'=>'password', 'class'=>'form-control','required'=>'required', 'placeholder'=>'Password']) ?>
              </div>
              <div>
                <?php echo form_reset(['name'=>'reset', 'value'=>'Reset', 'class'=>'btn-default']) ?>
                 <?php echo form_submit(['name'=>'submit', 'value'=>'LogIn', 'class'=>'btn-default']) ?>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>

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
              <div class="separator">
<!--                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>-->

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa"></i> Arms flour !</h1>
                  <p>©2018 All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>

     </div>
    </div>
  </body>
</html>
