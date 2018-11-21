<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Wallet</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body panel-filter">
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $action_url . '/customer/wallet'; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Id" />
                                            </div>
                                            <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                            <a class="btn btn-danger" href="<?php echo $action_url . '/customer/wallet'; ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">Wallet List</p>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Amount</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($addlist)) {
                                    foreach ($addlist as $list) {
                                        $list = (object) $list;
                                        ?> <tr>

                                            <td><?php echo $list->name != null ? ucfirst($list->name) : 'Guest'; ?></td>
                                            <td><?php echo $list->amount; ?></td>
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