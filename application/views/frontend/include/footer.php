<!-- footer -->
<!-- its new footer  -->
<div class="footerfkm">
    <div class="container">
        <div class="row">
            <div class="col-sm-8"> 
                <img class="imgftm" src="<?php echo base_url('assets/images/mobileview1.png'); ?>" alt="" class="phone"> 
            </div>
            <div class="col-sm-2 text-center trackbygtm" data-object-category="Button Footer Strip" data-object-label="Android" data-object-action="Open Playstore">
                <a href="https://play.google.com/store" target="_blank">
                    <img src="<?php echo base_url('assets/images/googleplay.png'); ?>" alt="" class="img-responsive">
                </a>
            </div>
            <div class="col-sm-2 text-center trackbygtm" data-object-category="Button Footer Strip" data-object-label="iOS" data-object-action="Open Appstore">
                <a href="https://www.apple.com/itunes/" target="_blank">
                    <img src="<?php echo base_url('assets/images/appleplay.png'); ?>" alt="" class="img-responsive">
                </a>
            </div>
        </div>
    </div>
</div>

<div class="footer agileits-w3layouts">
    <div class="container">
        <div class="w3_footer_grids">
            <div class="col-xs-12 col-md-4 col-sm-4 footer-grids w3-agileits">
                <h3>FOC24</h3>
                <ul>
                    <li><a href="<?php echo base_url('about') ?>">About Us</a></li>
                    <li><a href="<?php echo base_url('contact') ?>">Contact Us</a></li> 
                    <li><a href="<?php echo base_url('deliveryPolicy') ?>">Delivery Policies</a></li>
                    <li><a href="<?php echo base_url('privacyPolicy') ?>">Privacy Policies</a></li>
                    <li><a href="<?php echo base_url('careers') ?>">We're Hiring</a></li>  
                </ul>
            </div> 
            <div class="col-xs-12 col-md-4 col-sm-4 footer-grids w3-agileits">
                <h3>Explore</h3>
                <ul>
                    <li><a href="<?php echo base_url('offers') ?>">Offers</a></li>
                    <li><a href="<?php echo base_url('faq') ?>">FAQ</a></li>  
                    <li><a href="<?php echo base_url('termsCond') ?>">Disclaimers</a></li> 
                    <li><a href="<?php echo base_url('partyorder') ?>">Party Order</a></li>
                </ul>  
            </div>
            <div class="col-xs-12 col-md-4 col-sm-4 footer-grids w3-agileits">
                <h3>Follow</h3>
                <ul class="f3">
                    <li>
                        <a target="_blank" href="https://www.facebook.com/foc24.com/" class="fa fa-facebook icon facebook"> </a>
                    </li>
                    <li>
                        <a target="_blank" href="https://twitter.com/foc24.com" class="fa fa-twitter icon twitter"> </a>
                    </li>

                </ul>   
            </div>
            <div class="clearfix"> </div>
        </div>
    </div> 
</div>

<div class="copyw3-agile"> 
    <div class="container">
        <p>&copy; 2018 cafe. All rights reserved </p>
    </div>
</div>

<!-- //footer --> 
<script src="<?php echo base_url(); ?>assets/fnt/js/fkmcart.js"></script>
<script>
    var checkoutp = '<?php echo base_url('checkout'); ?>';
    // Mini Cart
    paypal1.minicart1.render({//use only unique class names other than paypal1.minicart1.Also Replace same class name in css and minicart.min.js
        action: checkoutp,
        currency_code: 'INR'
    });
    var ctotal = paypal1.minicart1.cart.items().length;
    if (ctotal > 0)
        $("#cartitmtotal").html(ctotal);
    if (~window.location.search.indexOf('reset=true')) {
        paypal1.minicart1.reset();
    }

//    paypal1.minicart1.cart.on('checkout', function (evt) {
//    var items = this.items();
//            console.log(items);
//    });

    /*  paypal1.minicart1.cart.on('checkout', function (evt) {
     var items = this.items(),
     len = items.length,
     total = 0,
     productname = '',
     i; */
    // alert(this.subtotal());
    // Count the number of each item in the cart
    /*  for (i = 0; i < len; i++) {
     
     total += items[i].get('quantity');
     pr_name = items[i].get('item_name');
     pr_qty = items[i].get('quantity');
     pr_price = items[i].get('amount');
     subtotal_inr = items[i].get('subtotal');
     productname = items[i].get();
     //console.log(productname);
     // alert(pr_price * pr_qty);
     }
     
     }); */

    //Reset cart an empty when checkout functioality is done
</script>  
<!-- //cart-js -->	
<!-- Owl-Carousel-JavaScript -->
<script src="<?php echo base_url(); ?>assets/fnt/js/owl.carousel.js"></script>
<script>
    $(document).ready(function () {
        $("#owl-demo1").owlCarousel({
            items: 3,
            lazyLoad: true,
            autoPlay: true,
            pagination: true,
        });
        $("#owl-demo").owlCarousel({
            items: 1,
            lazyLoad: true,
            autoPlay: true,
            pagination: true,
        });
        $("#owl-demo-menu").owlCarousel({
            items: 5,
            lazyLoad: true,
            pagination: false,
            navigation: true,
            navigationText: ['<i class="fa fa-angle-left fa-3x"></i>', '<i class="fa fa-angle-right fa-3x">'],
            scrollPerPage: false,
        });
    });</script>
<!-- //Owl-Carousel-JavaScript -->  
<!-- start-smooth-scrolling -->
<!--<script src="<?php echo base_url(); ?>assets/fnt/js/SmoothScroll.min.js"></script>  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fnt/js/move-top.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/fnt/js/easing.js"></script>	
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".scroll").click(function (event) {
            event.preventDefault();
            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
        });
    });</script>
<!-- //end-smooth-scrolling -->	  
<!-- smooth-scrolling-of-move-up -->
<script type="text/javascript">
    $(document).ready(function () {
        /*
         var defaults = {
         containerID: 'toTop', // fading element id
         containerHoverID: 'toTopHover', // fading element hover id
         scrollSpeed: 1200,
         easingType: 'linear' 
         };
         */

        $().UItoTop({easingType: 'easeOutQuart'});
    });</script>
<!-- //smooth-scrolling-of-move-up --> 
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo base_url(); ?>assets/fnt/js/bootstrap.js"></script>

<?php if ($this->router->fetch_class() == 'profile' && $this->router->fetch_method() == 'index') { ?>
    <link href="<?php echo base_url(); ?>assets/vendors/datatables.net/css/dataTables.bootstrap.min.css" type="text/css" rel="stylesheet" media="all">
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            $('#fkprofile').DataTable({
                responsive: true,
                bSort: false
            });
            $('#fkprofile12').DataTable({
                responsive: true,
                bSort: false
            });
            $('#fkprofile123').DataTable({
                responsive: true,
                bSort: false
            });
        });</script>
<?php } if ($this->router->fetch_class() == 'Home' && $this->router->fetch_method() == 'index') { ?>

    <script src="<?php echo base_url(); ?>assets/fnt/js/easyResponsiveTabs.js"></script>

    <script type="text/javascript">
        var kitcin = '<?php echo $this->session->userdata('kitchen_address'); ?>';
        var locaid = '<?php echo $this->session->userdata('location'); ?>';
        $(document).ready(function () {
            $('#horizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion           
                width: 'auto', //auto or any width like 600px
                fit: true   // 100% fit in a container
            });
            $("ul.resp-tabs-list > li").removeClass("resp-tab-active");
            $("div.resp-tabs-container > h2").removeClass("resp-tab-active");
            $("div.resp-tabs-container > div").removeClass("resp-tab-content-active");
            $("div.resp-tabs-container > div").hide();
            if (kitcin) {
                $('ul.resp-tabs-list > li[aria-controls="tab_item-1"]').addClass("resp-tab-active");
                $('div.resp-tabs-container > h2[aria-controls="tab_item-1"]').addClass("resp-tab-active");
                $('div.resp-tabs-container > div[aria-labelledby="tab_item-1"]').addClass("resp-tab-content-active");
                $('div.resp-tabs-container > div[aria-labelledby="tab_item-1"]').show();
            } else {
                $('ul.resp-tabs-list > li[aria-controls="tab_item-0"]').addClass("resp-tab-active");
                $('div.resp-tabs-container > h2[aria-controls="tab_item-0"]').addClass("resp-tab-active");
                $('div.resp-tabs-container > div[aria-labelledby="tab_item-0"]').addClass("resp-tab-content-active");
                $('div.resp-tabs-container > div[aria-labelledby="tab_item-0"]').show();
            }
            // Data table functionality
        });
        $(document).on("click", "#fm_search1", function () {
            $("#fm_search1").css('border', 'none');
            $('#map_modal').modal('show');
        });
        $(document).on("click", ".searchcanc", function () {
            $('.cinp').val('');
        });
    // submit the map
        $(document).on("click", "#cnfgmp", function () {
            var locttsion = $("#location").val();
            var lat = $("#lat").val();
            var lng = $("#lng").val();
            if (locttsion != '' && lat != '' && lng != '') {
                $("#fm_search1").val(locttsion);
                $("#fklong").val(lng);
                $("#fklat").val(lat);
                $('#map_modal').modal('hide');
                //$('#mapfmid').submit();
            } else {
                alert('Please enter Location');
            }
        });

    </script>
<?php } ?>
<?php if ($this->router->fetch_method() == 'product' || $this->router->fetch_method() == 'checkout' || $this->router->fetch_method() == 'order') { ?>  
    <script src="<?php echo base_url(); ?>assets/fnt/js/pro.js"></script>
    <?php
}
if ($this->session->userdata('cartempty') == 1) {
    ?>
    <script type="text/javascript"> paypal1.minicart1.reset();</script>
    <?php
    $this->session->unset_userdata('cartempty');
}
if ($this->router->fetch_method() == 'home') {
    ?>
    <script type="text/javascript">
        $('#popupmodel').modal('show');
    </script>
<?php }
?>
<script>
//    window.onscroll = function () {
//        myFunction()
//    };
//
//    var header = document.getElementById("fkheader");
//    var sticky = header.offsetTop;
//
//    function myFunction() {
//        if (window.pageYOffset > sticky) {
//            header.classList.add("sticky");
//        } else {
//            header.classList.remove("sticky");
//        }
//    }


    $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
            $('#fkheader').addClass("sticky");
        } else {
            $('#fkheader').removeClass("sticky");
        }
    });

</script>


<?php if ($this->router->fetch_method() == 'product') { ?>
    <script type="text/javascript">

        // Select all links with hashes
        $('a[href*="#"]')
                // Remove links that don't actually link to anything
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function (event) {
                    // On-page links
                    if (
                            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                            &&
                            location.hostname == this.hostname
                            ) {
                        // Figure out element to scroll to
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        // Does a scroll target exist?
                        if (target.length) {
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top
                            }, 1000, function () {
                                // Callback after animation
                                // Must change focus!
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                } else {
                                    $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                                    $target.focus(); // Set focus again
                                }
                                ;
                            });
                        }
                    }
                });
    </script>

<?php } ?>
<script type="text/javascript">

    $(window).load(function () {
        $(".loader1").hide();
        //$('.backgorund_images').show();
    });
</script>

</body>
</html>