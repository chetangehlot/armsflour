<!DOCTYPE html>
<html lang="en">

    <head>
        <?php
        $crt_cls = $this->router->fetch_class();
        $crt_mthd = $this->router->fetch_method();

        if ($crt_cls === 'Home' && $crt_mthd == 'product') {
            ?>
            <!--            <meta http-equiv="Refresh" content="60" hre=""> -->
        <?php } ?>

        <title>Food on call 24</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Cafe Food ordering from indore ,Ording,
              online,food,dinner,breakfast,lunch,roti,paneer,paratha,indore,FOC24, food on call" />
        <link style="background-color: black;" rel="icon" href="<?php echo base_url('assets/images/fav.png'); ?>">
        <!-- Custom Theme files -->
        <link href="<?php echo base_url(); ?>assets/fnt/css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
        <link href="<?php echo base_url(); ?>assets/fnt/css/style.css" type="text/css" rel="stylesheet" media="all">
        <link href="<?php echo base_url(); ?>assets/fnt/css/font-awesome.css" rel="stylesheet">
        <!-- font-awesome icons -->

        <link href="<?php echo base_url(); ?>assets/fnt/css/owl.carousel.css" rel="stylesheet" type="text/css" media="all" />
        <!-- Owl-Carousel-CSS -->
        <!-- //Custom Theme files -->
        <!-- js -->
        <script src="<?php echo base_url(); ?>assets/fnt/js/jquery-2.2.3.min.js"></script>
<style>
        .loader1 {
    background: rgba(0, 0, 0, 0.65) url("<?php echo base_url(); ?>assets/fnt/images/page-loader.gif") no-repeat scroll center center / 55px auto;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 9999;
}
    </style>
        <!-- //js -->
        <!-- web-fonts -->

        <!-- //web-fonts -->

        <?php
        if ($crt_cls === 'Home' && ($crt_mthd == 'index' || $crt_mthd == 'partyorder' )) {
            ?>
            <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
            <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
            <script>
                $(document).ready(function () {
                    // Datepicker Popups calender to Choose date.
                    $(function () {
                        $("#datepicker").datepicker({
                            showAnim: 'drop',
                            dateFormat: 'dd-mm-yy',
                            minDate: 0
                        });

                    });
                });
            </script>
        <?php }
        ?>

    </head>
    <?php
    $cls = '';
    if ($crt_cls === 'Home' && $crt_mthd === 'login') {
        $cls = 'bngclas';
    }
    ?>

    <body class="<?php echo $cls; ?>">
        <?php
        if ($crt_cls === 'Home' && $crt_mthd === 'product') {
            echo ' <div class="loader1"></div>';
        }
        ?>

        <div class="header" id="fkheader">
            <div class="w3ls-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->userdata('location') || $this->session->userdata('kitchen_address')) { ?>
                                <div class="w3ls-header-left">

                                    <ul style="list-style:none;color: #fff;">
                                        <li class="" style="">
                                            <?php
                                            if ($this->session->userdata('location')) {
                                                $loc = explode(',', $this->session->userdata('location'));
                                                $add1 = isset($loc[0]) ? $loc[0] : '';
                                                $add2 = isset($loc[1]) ? ', ' . $loc[1] : '';
                                                echo $add1 . $add2 . ' (Home Delivery)';
                                            } elseif ($this->session->userdata('kitchen_address')) {
                                                echo ucfirst($this->session->userdata('kitchen_address')) . ' (PICKUP)';
                                            }
                                            ?>

                                            <br>
                                            <a href="<?php echo base_url(); ?>" style="color: #fff;">
                                                <i class="fa fa-map-marker fa-lg" aria-hidden="true"> </i> Change Location</a>
                                        </li>
                                    </ul>

                                </div>
                            <?php } ?>
                            <div class="w3ls-header-right">
                                <ul>
                                    <li class="head-dpdn">
                                        <i class="fa fa-phone" aria-hidden="true"></i> Call us: <b> 9000002001 </b>
                                    </li>
                                    <?php
                                    if ($this->session->has_userdata('is_front_login')) {

                                        $wallet = getRow('wallet', 'amount', array('customer_id' => $this->session->userdata('cust_id')));

                                        $walletamount = '0.00';
                                        if (!empty($wallet)) {
                                            $walletamount = $wallet->amount ? number_format($wallet->amount, 2) : 0.00;
                                        }
                                        ?>
                                        <!-- Mega Menu -->
                                        <li class="dropdown">

                                            <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-success" data-target="#" href="javascript:void;">
                                                <?php echo $this->session->userdata('cust_name') ? ucfirst($this->session->userdata('cust_name')) : ' Visitor'; ?>
                                                <span class="caret">
                                                </span>
                                            </a>
                                            <ul class="dropdown-menu dmenufk" role="menu" aria-labelledby="dropdownMenu">
                                                <li><a href="<?php echo base_url('profile'); ?>">Profile</a></li>
                                                <li><a href="<?php echo base_url('profile/order'); ?>">Order history</a></li>
                                                <li><a href="<?php echo base_url('profile/address'); ?>">Address Book</a></li>
                                                <li><a href="<?php echo base_url('profile/wallet'); ?>"> Wallet(<?php echo $walletamount; ?>)</a></li>
                                                <li><a href="<?php echo base_url('profile/loyalty'); ?>">Loyalty</a></li>
                                                <li><a href="<?php echo base_url('track'); ?>">Track Order</a></li>
                                                <li><a href="<?php echo base_url('logout'); ?>"> Logout</a></li>
                                            </ul>
                                        </li>

                                    <?php } else { ?>
                                        <li class="head-dpdn">
                                            <a href="<?php echo base_url('login'); ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a>
                                        </li>
                                    <?php } ?>
                                    <li class="head-dpdn">
                                        <a href="<?php echo base_url('offers'); ?>"><i class="fa fa-gift" aria-hidden="true"></i> Offers</a>
                                    </li>
                                    <?php
                                    if ($this->session->has_userdata('is_front_login')) {
                                        ?>
                                    <li class="head-dpdn">
                                            <a href="<?php echo base_url('track'); ?>"><i class="fa fa-truck"></i> Track Order</a>
                                    </li>
                                    <?php } ?>
                                    <li class="head-dpdn">
                                        <a href="<?php echo base_url('partyorder'); ?>"><i class="fa fa-birthday-cake" aria-hidden="true"></i>Party Order</a>
                                    </li>
                                    <li>
                                <?php if ($crt_mthd != 'checkout' && $crt_mthd != 'order' && $crt_mthd != 'index' && $crt_mthd != 'home' && $crt_mthd != 'login') {
                                    ?>
                                    <div class="cart cart box_1">

                                                <button class="w3view-cart fkmkshopcart" type="button" name="button" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i><span id='cartitmtotal' class='badge'><?php echo (count($this->cart->contents()) > 0) ? count($this->cart->contents()) : ''; ?></span></button>

                                    </div>
                                <?php } ?>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- //header-one -->
            <!-- navigation -->
            <div class="navigation agiletop-nav">
                <div class="container">
                    <nav class="navbar navbar-default <?php
                    if ($crt_mthd === 'index' || $crt_mthd === 'login') {
                        echo 'pull-left';
                    }
                    ?>">
                        <div class="navbar-header w3l_logo">

                            <h1><a href="<?php
                                if ($this->session->userdata('kitchen_address') || $this->session->userdata('location')) {
                                    echo base_url('main');
                                } else {
                                    echo base_url();
                                }
                                ?>">
                                    <img src="<?php echo base_url('assets/images/ffc24.png'); ?>"></a>
                            </h1>
                        </div>
                        <?php
                        if ($crt_mthd == 'home' || $crt_mthd == 'product') {
                            ?>
                            <div class="pull-right margques fkmmargques">

                                <?php if (!empty($category)) { ?>

                                    <!-- scrooler -->

                                    <!-- Owl-Carousel -->
                                    <div id="owl-demo-menu" class="owl-carousel owl-demo text-center agileinfo-gallery-row">
                                        <?php
                                        foreach ($category as $cat) {
                                            if ($crt_mthd == 'product') {
                                                $href = '#catpro' . $cat['id'];
                                            } else {
                                                $href = base_url('product') . '#catpro' . $cat['id'];
                                            }
                                            $cls = '';
                                            if (@$catname == $cat['name']) {
                                                $cls = 'menucls';
                                            }
                                            ?>
                                            <a href="<?php
                                            echo $href;
                                            ?>" class="item g1 <?php echo $cls; ?>">
                                                <?php echo ucfirst(strtolower($cat['name'])); ?>

                                            </a>

                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>


                    </nav>
                    <?php if ($crt_mthd === 'index' || $crt_mthd === 'login') {
                        ?>
                        <div class="pull-right margques">
                            <marquee scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">Use coupon code for the Alu puri or Burger with chatni on App or website. Use Code <b>GRB5</b> and get 15% upto 90 Rs. cashback. tnc apply.</marquee>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <!-- //header-end -->

        <!-- banner -->
        <div class="banner <?php
        if ($crt_cls == 'Home' && $crt_mthd != 'index') {
            echo 'about-w3bnr';
        }
        ?> ">

            <div class="clearfix"></div>

            <!-- banner-text -->
            <?php if ($crt_cls == 'Home' && $crt_mthd == 'index') { ?>
                <div class="banner-text">
                    <div class="container">
                        <!--                        <h2 id="home_header_h2" class="pull-right" style="background-color: #67676761;">O2 Cafe De La Ville <br> <span>Best Chefs For you.</span></h2>-->
                        <?php
                        if (!empty(validation_errors())) {
                            echo ' <div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>' . validation_errors() . '</div>';
                        }
                        ?>
                        <!--  Accordian functionality        -->
                        <!-- <div class="agileits_search">
                        <form action="" method="post">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Home Delivery</a>
                                        </h4>         
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <input class="typeahead" autocomplete="off"  name="location" value="<?php echo $this->session->userdata('location') ? $this->session->userdata('location') : ''; ?>" type="text" id="fm_search" placeholder="Enter Your Area Name" required="">
                                            <input type="hidden" value="" name="hdntakaway" id="hdntakaway" />
                                            <select id="agileinfo_search" name="stat_city" required="">
                                                <option value="indore">Indore</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Pickup from hotel</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <span id='takeaway'><b><?php echo '23 Press complesx indore'; ?></b></span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Click">
                        </form>
                    </div> -->

                        <!-- new code for the pop up looing -->
                        <div class="message warning pull-left">
                            <div class="inset agile">
                                <div class="sap_tabs w3ls-tabs">
                                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
                                        <ul class="resp-tabs-list">
                                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Select Location</span></li>
                                            <!--                                            <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><label>/</label><span>Pickup</span></li>-->
                                        </ul>
                                        <div class="clear"> </div>
                                        <div class="resp-tabs-container">
                                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
                                                <div class="login-agileits-top">
                                                    <form id="mapfmid" action="" method="post">
                                                        <input type="hidden"  name="preorder_date" id="preorder_date" />
                                                        <input type="hidden" name="pre_ordertime" id="pre_ordertime" />
                                                        <?php if (!empty($citylist)) { ?>
                                                            <select class="form-control" id="stat_city" name="stat_city" required="">
                                                                <?php
                                                                foreach ($citylist as $city) {
                                                                    $citynew = $this->session->userdata('stat_city') ? $this->session->userdata('stat_city') : '';
                                                                    ?>
                                                                    <option <?php
                                                                    echo ($citynew == $city['city_name']) ? 'selected="selected"' : '';
                                                                    ?> value="<?php echo ucfirst($city['city_id']); ?>"><?php echo ucfirst($city['city_name']); ?></option>
                                                                    <?php } ?>
                                                            </select>
                                                            <br>
                                                        <?php } ?>

                                                        <input class="form-control" autocomplete="off" name="location" value="<?php echo $this->session->userdata('location') ? $this->session->userdata('location') : ''; ?>" type="text" id="fm_search1" placeholder="Enter Your Area Name" required="">
                                                        <!--                                                        <input class="typeahead form-control" autocomplete="off"  name="location" value="<?php echo $this->session->userdata('location') ? $this->session->userdata('location') : ''; ?>" type="text" id="fm_search" placeholder="Enter Your Area Name" required="">-->
                                                        <?php
                                                        $customerid = $this->session->userdata('cust_id');
                                                        if ($customerid != '') {
                                                            $alladdress = getAllAddress($customerid);

                                                            if (!empty($alladdress)) {
                                                                foreach ($alladdress as $loc) {
                                                                    $cls = $this->session->userdata('customer_address_id') == $loc['address_id'] ? 'alladdpms-active' : '';
                                                                    ?>
                                                                    <br>
                                                                    <div id_mamp="<?php echo $loc['address_id']; ?>" class="alladdpms <?php echo $cls; ?>">
                                                                        <?php echo ucfirst($loc['name']) . ' : ' . ucfirst($loc['address_1']) . ' ' . ucfirst($loc['address_2']); ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <input type="hidden" value="1" name="city-address" />
                                                        <input type="hidden" value="" id="address_new_id" name="address_new_id" />
                                                        <input type="hidden" value="<?php echo $this->session->userdata('latf') != null ? $this->session->userdata('longf') : ''; ?>" name="long" id="fklong" />
                                                        <input type="hidden" value="<?php echo $this->session->userdata('latf') != null ? $this->session->userdata('latf') : ''; ?>" name="lat" id="fklat" />
                                                        <input id="ordernow" type="submit"  value="Order Now">
                                                        <input type="button" id='preorder' class="submitbtn" value="Pre Order" style="margin-left:5px">

                                                    </form>
                                                </div>
                                            </div>

                                            <?php if ($crt_cls == 'Home' && $crt_mthd == 'index') { ?>
                                                <div class="modal fade calendar-popup" id="preordermodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="myModalLabel">Pre Order</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="input-group date" id="datetimepicker1">
                                                                            <input placeholder="Enter pre Order Date" type="text" class="form-control clspr" id="datepicker">
                                                                            <span class="input-group-addon">
                                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <select class="form-control clspr" name="timeslosts" id='timeslosts'>
                                                                            <option>Select Time slots</option>
                                                                    </select>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button id="sheduledata" type="button" class="btn btn-primary">OK</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                                <?php } ?>

                                                                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- end accordian  -->

                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- //banner -->
        <!-- breadcrumb -->

        <?php /* if ($crt_cls == 'Home' && $crt_mthd != 'index') { ?>

          <div class="container">
          <ol class="breadcrumb w3l-crumbs">
          <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">
          <?php
          echo ucfirst(str_replace('_', ' ', $crt_mthd));
          //echo ucfirst($crt_mthd);
          if ($crt_mthd === 'contact' || $crt_mthd === 'about') {
          echo ' Us';
          }
          ?>
          </li>
          </ol>
          </div>

          <?php
          } */
        ?>
        <!-- //breadcrumb -->