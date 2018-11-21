<div id="contact" class="contact cd-section">
    <div class="container">
        <div class="contact-row agileits-w3layouts">  
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
            ?>
            <div class="row">
            <div class="inner-contact-box">
                <div class="col-xs-12">
                    <h1>Contact us</h1>
                    <span></span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 contact-w3lsleft">
                <div class="contact-grid agileits">
                    <h4>DROP US A LINE </h4>
                    <form action="<?php echo base_url('contact'); ?>" method="post"> 
                        <input type="text" name="name" placeholder="Name" required="">
                        <input type="email" name="email" placeholder="Email" required=""> 
                        <input type="text" name="phone" placeholder="Phone Number" required="">
                        <textarea name="message" placeholder="Message..." required=""></textarea>
                        <input type="submit" value="Submit" >
                    </form> 
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 contact-w3lsright">
                <h6><span>Sed interdum </span>interdum accumsan nec purus ac orci finibus facilisis. In sit amet placerat nisl in auctor sapien. </h6>
                <div class="address-row">
                    <div class="col-xs-2 address-left">
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    </div>
                    <div class="col-xs-10 address-right">
                        <h5>Visit Us</h5>
                        <p>Cafe, Indore India</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="address-row w3-agileits">
                    <div class="col-xs-2 address-left">
                        <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                    </div>
                    <div class="col-xs-10 address-right">
                        <h5>Mail Us</h5>
                        <p><a href="mailto:info@example.com"> mail@example.com</a></p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="address-row">
                    <div class="col-xs-2 address-left">
                        <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
                    </div>
                    <div class="col-xs-10 address-right">
                        <h5>Call Us</h5>
                        <p>+919999999999</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>  
            </div>
            </div>
        </div>	
    </div>


    <div class="map agileits">
        <iframe width="600" height="450" frameborder="0" style="border:0"src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=Malet%20St%2C%20London%20WC1E%207HU%2C%20United%20Kingdom+(Your%20Business%20Name)&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
        </iframe>

    </div>
    <!-- //map --> 
</div>
<!-- //contact -->   