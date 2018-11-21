<!-- page content -->
<?php
$actnurl = $actionurl != '' ? $actionurl : '';
$actionform = $action_url . '/crm/' . $actnurl;
$from_date = $from_date;
$kithcen_name = '';
$to_date = $to_date;
$selectdata = '';
if (empty($from_date) && empty($to_date)) {
    $selectdata = date('d-m-Y');
} else {
    $selectdata = $from_date;
}
?>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="fkm">
                    <div class="x_title">
                        <h2>Total Sales</h2>

                        <div class="clearfix"></div>


                    </div>
                    <div class="panel-body panel-filter">
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $actionform; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12 pull-right text-right">
                                            <div class="col-md-11 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Customer By Name or Mobile" />
                                            </div>
                                            <a   class="btn btn-primary filterfm" data-toggle="tooltip"  title="Search"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <?php //if (!$user_strict_location) {  ?>
                                        <!--                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                                                    <select name="filter_location" class="form-control">
                                                                                        <option value="">Location</option> 
                                        <?php
                                        if (!empty($location)) {
                                            foreach ($location as $loc) {
                                                ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <option <?php echo $filter_location == $loc['id'] ? 'Selected' : ''; ?> value="<?php echo $loc['id']; ?>"><?php echo ucfirst($loc['name']); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                                                                    </select>
                                                                                </div>-->

                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <select name="filter_kitchen" class="form-control">
                                                <option value="">Select Kitchen</option>
                                                <?php
                                                if (!empty($kitchen)) {
                                                    foreach ($kitchen as $kit) {
                                                        if ($filter_kitchen == $kit['id']) {
                                                            $kithcen_name = $kit['name'];
                                                        }
                                                        ?>
                                                        <option <?php echo isset($filter_kitchen) && $filter_kitchen == $kit['id'] ? 'Selected' : ''; ?>  value="<?php echo $kit['id']; ?>"><?php echo $kit['name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <select name="filter_payment" class="form-control">
                                                <option value="">Payment</option>
                                                <option <?php echo isset($filter_payment) && $filter_payment == 'cod' ? 'Selected' : ''; ?> value="<?php echo 'cod'; ?>" <?php echo set_select('filter_payment', 'cod'); ?> ><?php echo 'COD'; ?></option>
                                                <option <?php echo isset($filter_payment) && $filter_payment == 'online' ? 'Selected' : ''; ?>  value="<?php echo "online"; ?>" <?php echo set_select('filter_payment', 'online'); ?> ><?php echo 'ONLINE'; ?></option>
                                            </select>
                                        </div>
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
                    </div> 
                    <div class="clear"></div>
                    <div class="clear"></div>
                    <div class="clear"></div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <span style="font-size:24px;">  Total Sales: <i class="fa fa-inr"></i> <?php echo isset($total_sales->sub_total) ? $total_sales->sub_total : 0; ?></span>
                            <br/>
                            <span style="font-size:24px;">  Total Order: <?php echo isset($total_sales->total_order) ? $total_sales->total_order : 0; ?></span>
                        </div>

                    </div> 
                    <div class="x_content">
                        <div class="text-center">
                            <h2><?php echo $kithcen_name != "" ? ucfirst($kithcen_name) : ''; ?></h2>
                            <h4>From: <?php echo (isset($from_date) && $from_date != '') ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y'); ?>    To:  <?php echo (isset($to_date) && $to_date != '') ? date('d-m-Y', strtotime($to_date)) : date('d-m-Y'); ?> </h4>
                        </div>
                        <p class="text-muted font-13 m-b-30">Order list</p>
                        <form action="" method="post" name="delchekcobx">
                            <table id="fkdelivery" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sub Total</th>
                                        <th>Disc</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>Wallet Total</th>
                                        <th>Total Ship Chrge</th>
                                        <th>Total Bill Amount</th>
                                        <th>Total Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($total_sales)) {
                                        ?>  <tr>
                                            <td><?php echo $selectdata; ?></td>
                                            <td><?php echo $total_sales->total != '' ? $total_sales->total : 0.00; ?></td>
                                            <td><?php echo $total_sales->discount_total != '' ? $total_sales->discount_total : '0.00'; ?></td>
                                            <td><?php ?></td>
                                            <td><?php ?></td>
                                            <td><?php echo $total_sales->wallet_total != '' ? $total_sales->wallet_total : 0.00; ?></td>
                                            <td><?php echo $total_sales->shipp_charge_total != '' ? $total_sales->shipp_charge_total : '0.00'; ?></td>
                                            <td><?php echo $total_sales->sub_total != '' ? $total_sales->sub_total : 0.00; ?></td>
                                            <td><?php echo $total_sales->total_order; ?></td>

                                        </tr>
                                        <?php
                                    } else {
                                        echo '<tr><th class="text-center" colspan="10"> No Records Available</th></tr>';
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