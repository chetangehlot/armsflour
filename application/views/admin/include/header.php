<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo base_url(); ?>assets/images/img.png" alt="">
                        <?php echo $username = $this->session->username; ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?php echo site_url('admin/profile') ?>"> Profile</a></li>
                        <li>
                            <a href="<?php echo site_url('admin/setting') ?>">
                                <span>Settings</span>
                            </a>
                        <li><a href="<?php echo site_url('admin/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <script>var base_url = "<?= base_url(); ?>";</script>
    </div>
</div>