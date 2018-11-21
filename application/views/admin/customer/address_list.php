<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Address</h2>
                        <a class="pull-right" href="<?php echo $action_url . '/customer/add'; ?>"><button type="button" class="btn btn-primary">Add Customer</button></a>
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
                    <div class="panel-body panel-filter">
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $action_url . '/customer'; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Id" />
                                            </div>
                                            <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                            <a class="btn btn-danger" href="<?php
                                            echo $action_url . '/customer';
                                            ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">Address list</p>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Address 1</th>
                                    <th>Address 2</th>
                                    <th>City</th>
                                    <th>Postcode</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($addlist)) {
                                    foreach ($addlist as $list) {
                                        $list = (object) $list;
                                        ?> <tr>

                                            <td><?php echo $list->name != null ? ucfirst($list->name) : '---'; ?></td>
                                            <td><?php echo $list->address_1 != null ? $list->address_1 : '---'; ?></td>
                                            <td><?php echo $list->address_2 != null ? $list->address_2 : '---'; ?></td>
                                            <td><?php echo $list->city_name != null ? $list->city_name : '---'; ?></td>
                                            <td><?php echo $list->postcode != null ? $list->postcode : '---'; ?></td>
                                            <td><?php echo $list->created_date != '0000-00-00 00:00:00' ? date('d m Y H:i', strtotime($list->created_date)) : '---'; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/customer/deladd/') . base64_encode($list->address_id); ?>" onclick="return confirm('Are you sure? You want to delete this!')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                    <?php if (!empty($links)) { ?>
                        <div class="pull-right">
                            <?php echo $links; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>