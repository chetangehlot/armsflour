<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Slider</h2>
                        <a class="pull-right" href="<?php echo site_url() . '/admin/slider/add'; ?>"><button type="button" class="btn btn-primary">Add Slide</button></a>
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
                        <p class="text-muted font-13 m-b-30">
                            Display All Slider List
                        </p>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Heading</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($sliderlist)) {
                                    foreach ($sliderlist as $slide) {
                                        $slide = (object) $slide;
                                        ?> 
                                        <tr>
                                            <td><?php echo ucfirst($slide->heading); ?></td>
                                            <td><?php echo ucfirst($slide->description); ?></td>
                                            <td><img   src="<?php echo base_url(); ?>assets/slider/thumbs/<?php echo $slide->image; ?>"></td>
                                            <td><?php echo date('d-m-Y H:i:s', strtotime($slide->createddate)); ?></td>
                                            <td>
                                                <a href="<?php echo base_url("admin/slider/del/") . $slide->id; ?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash fa-2x" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;&nbsp;&nbsp;
                                                <a href="<?php echo base_url("admin/slider/add/") . base64_encode($slide->id); ?>" ><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   
            <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>