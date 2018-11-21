<!-- page content -->
<?php
$actnurl = $actionurl != '' ? $actionurl : '';
$actionform = $action_url . '/crm/' . $actnurl;
$total_cum = 0;
if ($customerdetails) {
    $total_cum = count($customerdetails);
}
?>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="fkm">
                    <div class="x_title">
                        <h2>New customer Report</h2>
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
                    ×</button> <strong>Oh snap!</strong>' . $this->session->flashdata('error') . '</div>';
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
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $actionform; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12 pull-right text-right">
                                            <div class="col-md-11 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Mobile" />
                                            </div>
                                            <a   class="btn btn-primary filterfm" data-toggle="tooltip"  title="Search"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px;">
                                        <!--                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                                                    <select name="filter_payment" class="form-control">
                                                                                        <option value="">Payment</option>
                                                                                        <option <?php echo isset($filter_payment) && $filter_payment == 'cod' ? 'Selected' : ''; ?> value="<?php echo 'cod'; ?>" <?php echo set_select('filter_payment', 'cod'); ?> ><?php echo 'COD'; ?></option>
                                                                                        <option <?php echo isset($filter_payment) && $filter_payment == 'online' ? 'Selected' : ''; ?>  value="<?php echo "online"; ?>" <?php echo set_select('filter_payment', 'online'); ?> ><?php echo 'ONLINE'; ?></option>
                                                                                    </select>
                                                                                </div>-->
                                        <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                            <input autocomplete="off" type="text" name="from_date"  required="" value="<?php echo isset($from_date) ? $from_date : ''; ?>" class="form-control col-md-7 col-xs-12 date form_datetime" placeholder="dd-mm-yyyy">
                                            <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group has-feedback">
                                            <input autocomplete="off" type="text" name="to_date"  required="" value="<?php echo isset($to_date) ? $to_date : ''; ?>" class="form-control col-md-7 col-xs-12 date form_datetime" placeholder="dd-mm-yyyy">
                                            <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                                        </div>
                                        <div class=" col-md-3 col-sm-12 col-xs-12 form-group">
                                            <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                            <a class="btn btn-danger" href="<?php echo $actionform; ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- totoal showing  -->
                        <div class="clear"></div>
                        <div class="clear"></div>
                        <div class="clear"></div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">

                                <span style="font-size:24px;">  Total New Customer: <?php echo $total_cum; ?></span>
                            </div>
                        </div> 
                        <div class="x_content">
                            <div class="text-center">
                                <h4>From: <?php echo (isset($from_date) && $from_date != '') ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y'); ?>    To:  <?php echo (isset($to_date) && $to_date != '') ? date('d-m-Y', strtotime($to_date)) : date('d-m-Y'); ?> </h4>
                            </div>
                            <table id="fkdelivery" style="width:100%" class="table  table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Total Order</th>
                                        <th>Total Sales</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($customerdetails)) {

                                        foreach ($customerdetails as $list) {
                                            $list = (object) $list;
                                            $total_order = 0;
                                            $total_sales = 0;

                                            $orderTotal = getRow('orders', 'sum(sub_total_amount) as total,count(order_id) as total_order', array('customer_id' => $list->id));
                                            if (!empty($orderTotal)) {
                                                $total_order = $orderTotal->total_order;
                                                $total_sales = $orderTotal->total;
                                            }
                                            ?>  
                                            <tr>
                                                <td><?php echo ucfirst($list->name); ?></td>
                                                <td><?php echo $list->mobile; ?></td>
                                                <td><?php echo $total_order > 0 ? $total_order : 0.00; ?></td>
                                                <td><?php echo $total_sales > 0 ? $total_sales : 0.00; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($list->created_date)); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><th class="text-center" colspan="5"> No Records Available</th></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>