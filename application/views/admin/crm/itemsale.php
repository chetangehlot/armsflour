<!-- page content -->
<?php
$actnurl = $actionurl != '' ? $actionurl : '';
$actionform = $action_url . '/crm/' . $actnurl;
$kithcen_name = '';
?>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="fkm">
                    <div class="x_title">
                        <h2>Item Wise Sales Report </h2>
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

                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <select name="filter_type" class="form-control">
                                                <option value="">Select Kitchen</option>
                                                <?php
                                                if (!empty($kitchen)) {

                                                    foreach ($kitchen as $kit) {
                                                        if ($filter_type == $kit['id']) {
                                                            $kithcen_name = $kit['name'];
                                                        }
                                                        ?>
                                                        <option <?php echo isset($filter_type) && $filter_type == $kit['id'] ? 'Selected' : ''; ?>  value="<?php echo $kit['id']; ?>"><?php echo $kit['name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <select name="filter_cat" class="form-control">
                                                <option value="">Select Category</option>
                                                <?php
                                                if (!empty($category)) {

                                                    foreach ($category as $cat) {
                                                        if ($filter_cat == $cat['id']) {
                                                            
                                                        }
                                                        ?>
                                                        <option <?php echo isset($filter_cat) && $filter_cat == $cat['id'] ? 'Selected' : ''; ?>  value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
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
                            <span style="font-size:24px;">  Total Item Sales: <i class="fa fa-inr"></i> <?php echo $salestotal != 0 ? $salestotal : 0; ?></span>
                            <br>
                            <span style="font-size:24px;">  Total Bill: <?php echo isset($total_order) ? $total_order : 0; ?></span>

                        </div>
                    </div> 
                    <div class="x_content">
                        <div class="text-center">
                            <h2><?php echo $kithcen_name != "" ? ucfirst($kithcen_name) : ''; ?></h2>
                            <h4>From: <?php echo (isset($from_date) && $from_date != '') ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y'); ?>    To:  <?php echo (isset($to_date) && $to_date != '') ? date('d-m-Y', strtotime($to_date)) : date('d-m-Y'); ?> </h4>
                        </div>
                        <form action="" method="post" name="delchekcobx">
                            <table style="width:100%" id="fkdelivery" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Veg/NonVeg</th>
                                        <th>Rate</th>
                                        <th>Paid Qty</th>
                                        <th>Disc</th>
                                        <th>Paid AMT</th>
                                        <th>Consumed Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (!empty($total_sales)) {
                                        $i = 1;
                                        $tatal_paidqty = $disc = $paidamount = $cusmqty = 0;
                                        foreach ($total_sales as $list) {
                                            $list = (object) $list;
                                            $tatal_paidqty += $list->total_product;
                                            $disc += $list->total_discount;
                                            $paidamount += $list->totalpaid;
                                            $catename = '';
                                            $cat = getRow('category', 'name', array('id' => $list->category_id));
                                            if (!empty($cat)) {
                                                $catename = strtolower($cat->name);
                                            }
                                            ?>  
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo ucfirst($list->name); ?></td>
                                                <td><?php echo ucfirst($catename); ?></td>
                                                <td><?php echo ($list->is_nonveg == 1) ? 'Nonveg' : 'Veg'; ?></td>
                                                <td><?php echo $list->price; ?></td>
                                                <td><?php echo $list->total_product; ?></td>
                                                <td><?php echo $list->total_discount; ?></td>
                                                <td><?php echo $list->totalpaid; ?></td>
                                                <td><?php echo $list->total_product; ?></td>   

                                            </tr>

                                            <?php
                                            $i++;
                                        }
                                        $paidamount = number_format($paidamount, 2);
                                        $disc = number_format($disc, 2);
                                        ?>
                                        <tr style="background-color: #000; color: #fff;">
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td></td>
                                            <td><b> Total:</b></td>
                                            <td><b><?php echo $tatal_paidqty; ?></b></td>
                                            <td><b><?php echo $disc; ?></b></td>
                                            <td><b><?php echo $paidamount; ?></b></td>
                                            <td><b><?php echo $tatal_paidqty; ?></b></td>   
                                        </tr>
                                        <?php
                                    } else {
                                        echo '<tr><th class="text-center" colspan="8"> No Records Available</th></tr>';
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