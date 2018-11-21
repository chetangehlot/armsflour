<!-- page content -->
<?php
$actnurl = $actionurl != '' ? $actionurl : '';
$actionform = $action_url . '/crm/' . $actnurl;
$wallet_amount = $loyalty_poinmt = 0;
$last_order_date = '';
if (isset($total_sales) && $total_sales->customer_id) {
    $getcustomer = getRow('wallet', 'amount', array('customer_id' => $total_sales->customer_id));

    if ($getcustomer) {
        $wallet_amount = $getcustomer->amount;
    }

    $logyaltypointget = getRow('customer_loyalty', 'loyalty_point', array('customer_id' => $total_sales->customer_id));

    if ($logyaltypointget) {
        $loyalty_poinmt = $logyaltypointget->loyalty_point;
    }
}

if (isset($last_order_deatils)) {
    $datetime = $last_order_deatils['order_date'];
    if (!empty($datetime))
        $last_order_date = time_elapsed_string($datetime);
}
?>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="fkm">
                    <div class="x_title">
                        <h2>Customer Report</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="panel-body panel-filter">
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $actionform; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12 pull-right text-right">
                                            <div class="col-md-10 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Mobile" />
                                            </div>
                                            <a   class="btn btn-primary filterfm" data-toggle="tooltip"  title="Search"><i class="fa fa-search"></i></a>
                                            <a class="btn btn-danger" href="<?php echo $actionform; ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:10px;">
                                        <!--                                        <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                                                                    <input autocomplete="off" type="text" name="from_date"  required="" value="<?php echo isset($from_date) ? $from_date : ''; ?>" class="form-control col-md-7 col-xs-12 date form_datetime" placeholder="dd-mm-yyyy">
                                                                                    <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                                                                                </div>
                                                                                <div class="col-md-3 col-sm-12 col-xs-12 form-group has-feedback">
                                                                                    <input autocomplete="off" type="text" name="to_date"  required="" value="<?php echo isset($to_date) ? $to_date : ''; ?>" class="form-control col-md-7 col-xs-12 date form_datetime" placeholder="dd-mm-yyyy">
                                                                                    <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                                                                                </div>
                                                                                <div class=" col-md-3 col-sm-12 col-xs-12 form-group">
                                                                                    <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                                                                    <a class="btn btn-danger" href="<?php
                                        $actnurl = $actionurl != '' ? $actionurl : '';
                                        echo base_url('admin/crm/') . $actnurl;
                                        ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                                                                </div>-->
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- totoal showing  -->
                        <div class="clear"></div>
                        <div class="clear"></div>
                        <div class="clear"></div>
                        <?php
                        if (isset($total_sales->customer_id) && !empty($total_sales->customer_id)) {
                            ?>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <span style="font-size:24px;"> Name: <?php echo ucfirst($total_sales->name); ?></span>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                    <span style="font-size:20px;">  Mobile : <?php echo $total_sales->mobile; ?></span>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                    <span style="font-size:16px;">  <b> Total Bill Amount : </b>    <i class="fa fa-inr"></i> <?php echo isset($total_sales->total) ? $total_sales->total : 0; ?></span>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <span style="font-size:16px;">  <b> Total Order: </b> <?php echo isset($total_sales->total_order) ? $total_sales->total_order : 0; ?></span>
                                </div>
                            </div> 
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                    <span style="font-size:16px;">  <b> Average Bill: </b> <i class="fa fa-inr"></i> <?php echo isset($avg_bill) ? $avg_bill : 0; ?></span>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <span style="font-size:16px;"> <b> Wallet Amount: </b> <?php echo isset($wallet_amount) ? $wallet_amount : 0; ?></span>
                                </div>
                            </div> 
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                    <span style="font-size:16px;">  <b> Loyalty Point: </b>  <?php echo isset($loyalty_poinmt) ? $loyalty_poinmt : 0; ?></span>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <span style="font-size:16px;"> <b> Last Order </b> : <?php echo $last_order_date; ?></span>
                                </div>
                            </div> 
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <span style="font-size:16px;">  <b> Favourite Products: </b>  <?php
                                        if (!empty($papular_product)) {
                                            $prd = '';
                                            foreach ($papular_product as $pr) {
                                                $prd .= ($prd == '') ? ucfirst($pr['name']) : ', ' . ucfirst($pr['name']);
                                            }
                                        }
                                        echo $prd;
                                        ?>

                                    </span>
                                </div>
                            </div> 

                            <div class="x_content">
                                <div class="text-center">
                                    <h2>

                                    </h2>
                                    <h4>

                                    </h4>
                                </div>
                            <?php } ?>
                            <form action="" method="post" name="delchekcobx">
                                <table id="fkdelivery" style="width:100%" class="table  table-bordered ">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Address</th>
                                            <th>Mode</th>
                                            <th>Total Bill</th>
                                            <th>Time-Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($orderlist)) {
                                            $i = 1;
                                            foreach ($orderlist as $list) {
                                                $list = (object) $list;
                                                ?>  
                                                <tr>
                                                    <td><?php echo $list->order_id; ?></td>
                                                    <td><?php echo $list->address; ?></td>
                                                    <td><?php echo ucfirst($list->payment_type); ?></td>
                                                    <td>Rs.<?php echo number_format($list->sub_total_amount, 2); ?></td>
                                                    <td> <?php echo date('h:i A', strtotime($list->order_time)) . ' <br> ' . date('d M y', strtotime($list->order_date)); ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        } else {
                                            echo '<tr><th class="text-center" colspan="6"> No Records Available</th></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>