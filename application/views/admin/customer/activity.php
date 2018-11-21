<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Customer Activity</h2>

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
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $action_url . '/customer/activity'; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Id" />
                                            </div>
                                            <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                            <a class="btn btn-danger" href="<?php echo $action_url . '/customer/activity'; ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">Customer Activity</p>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Subject</th>
                                    <th>URL</th>
                                    <th>Comment</th>
                                    <th>IP</th>
                                    <th>Device Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($addlist)) {
                                    foreach ($addlist as $list) {
                                        $list = (object) $list;
                                        ?> <tr>

                                            <td><?php echo $list->username != null ? ucfirst($list->username) : 'Guest'; ?></td>
                                            <td><?php echo $list->subject != null ? ucfirst($list->subject) : '---'; ?></td>
                                            <td><?php echo $list->url != null ? $list->url : '---'; ?></td>
                                            <td><?php echo $list->comment != null ? ucfirst($list->comment) : '---'; ?></td>
                                            <td><?php echo $list->ip != null ? $list->ip : '---'; ?></td>
                                            <td><?php echo $list->type != null ? ucfirst($list->type) : '---'; ?></td>
                                            <td><?php echo $list->created_date != '0000-00-00 00:00:00' ? date('d-m-Y h:i:s a', strtotime($list->created_date)) : '---'; ?></td>

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