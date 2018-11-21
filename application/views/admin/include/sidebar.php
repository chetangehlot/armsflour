<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ARMS FLOUR</title>

        <!--Bootstrap -->
        <link href = "<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css" rel = "stylesheet">
        <!--Font Awesome -->
        <link href = "<?php echo base_url(); ?>assets/admin/css/font-awesome.min.css" rel = "stylesheet">

        <!--Custom Theme Style -->
        <link href = "<?php echo base_url(); ?>assets/admin/css/custom.min.css" rel = "stylesheet">
        <style>
            .nav-md .container.body .right_col {
                padding: 10px 20px 272px;
                margin-left: 230px;
            }
        </style>
        <script> base_url = "<?= base_url(); ?>";</script>
    </head>
    <body class = "nav-md">
        <div class = "container body">
            <div class = "main_container">
                <div class = "col-md-3 left_col">
                    <div class = "left_col scroll-view">
                        <div class = "navbar nav_title" style = "border: 0;">
                            <a href = "<?php echo site_url('admin/dashboard'); ?>" class = "site_title"><i class = "fa fa-paw"></i> <span>Admin Panel!</span></a>
                        </div>
                        <div class = "clearfix"></div>

                        <!--menu profile quick info -->
                        <!--<div class = "profile clearfix">
                        <div class = "profile_pic">
                        <img src = "<?php echo base_url(); ?>assets/images/img.png" alt = "..." class = "img-circle profile_img">
                        </div>
                        <div class = "profile_info">
                        <span>Welcome, </span>
                        <h2><?php echo $username = $this->session->username;
        ?></h2>
                </div>
                </div>-->
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <h3>General</h3>
                                <ul class="nav side-menu">
                                   
                                    <li>
                                        <a><i class="fa fa-tags"></i>Category<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo site_url('admin/category') ?>">Category list</a></li>
                                            <li><a href="<?php echo site_url('admin/category/add') ?>">Add Category</a></li> 

                                        </ul>
                                    </li>
                                   
                                    <li>
                                        <a><i class="fa fa-product-hunt"></i>Product<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">

                                            <li><a href="<?php echo site_url('admin/product') ?>">Product list</a></li>
                                            <li><a href="<?php echo site_url('admin/product/add') ?>">Add Product</a></li> 

                                        </ul>
                                    </li>
                                                                        <li>
                                        <a><i class="fa fa-sliders"></i>Sliders <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="<?php echo site_url('admin/slider') ?>">Slider List</a></li>                     
                                            <li><a href="<?php echo site_url('admin/slider/add') ?>">Add New</a></li>
                                        </ul>
                                    </li>
                                    
                                   
                                </ul>
                            </div>
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Settings" href="<?php echo site_url('admin/setting') ?>">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>

                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo site_url('admin/logout') ?>">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>