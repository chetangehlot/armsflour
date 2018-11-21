<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Category</h2>
                        <a class="pull-right" href="<?php echo site_url() . 'admin/category/add'; ?>"><button type="button" class="btn btn-primary">Add Category</button></a>
                        <div class="clearfix"></div>
                        <?php
                        if ($this->session->flashdata('success')) {
                            echo '<br>';
                            echo '<div class="alert alert-success " role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    x</button> ' . $this->session->flashdata('success') . '</div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo '<br>';
                            echo '<div class="alert alert-danger " role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    x</button> <strong>Oh snap!</strong> ' . $this->session->flashdata('error') . '</div>';
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
                      
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                     <th>Image</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th>Priority No</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($allcategory)) {
                                    foreach ($allcategory as $cat) {
                                        $cls = '';
                                        $cat = (object) $cat;
                                               $image='';
                                            if (@getimagesize(base_url() . 'assets/category/thumbs/' .$cat->image)) {
                                                   $image = base_url() . 'assets/category/thumbs/' . $cat->image;
                                            }
                                             
                                        ?>
                                        <tr>

                                            <td><?php echo ucfirst($cat->category); ?></td>
                                            <td><img width="100" height="50" src="<?php echo $image; ?>"></td>
                                            <td><?php echo date('d-m-Y', strtotime($cat->created_date)); ?></td>
                                            <td>
                                                <?php
                                                if ($cat->modify_date != '0000-00-00 00:00:00') {
                                                    echo date('d-m-Y H:i:s', strtotime($cat->modify_date));
                                                } else
                                                    echo '---';
                                                ?>
                                            </td>
                                            <td><?php echo $cat->priority_no; ?></td>
                                            <td><?php echo $cat->is_active == 1 ? 'Enabled' : 'Disabled'; ?></td>
                                            <td>
                                                <?php if ($cat->id != 15) { ?>
                                                    <a href="<?php echo base_url('admin/category/del/') . base64_encode($cat->id); ?>" onclick="return confirm('Are you sure? You want to delete this!')"><i class="fa fa-trash" aria-hidden="true"></i>
                                                    <?php } ?>
                                                </a>
                                                <a href="<?php echo base_url('admin/category/add/') . base64_encode($cat->id); ?>">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                            
                                    }
                                } else {
                                    echo '<tr><th class="text-center" colspan="6"> No Records Available</th></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>