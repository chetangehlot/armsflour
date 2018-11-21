<!-- page content -->
<?php
$actnurl = $actionurl != '' ? $actionurl : '';
$actionform = $action_url . '/crm/' . $actnurl;
?>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="fkm">
                    <div class="x_title">
                        <h2>Popular Product</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body panel-filter">
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $actionform; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12 pull-right text-right">
                                            <div class="col-md-11 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Product Name" />
                                            </div>
                                            <a   class="btn btn-primary filterfm" data-toggle="tooltip"  title="Search"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <?php //if (!$user_strict_location) { ?>
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

                                    </div>

                                    <div class="row" style="margin-top:10px;">

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
                    <!--                    <div class="row" style="margin-top:10px;">
                                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                <span style="font-size:24px;">  Total Sales: <i class="fa fa-inr"></i> <?php echo isset($total_sales->total) ? $total_sales->total : 0; ?></span>
                                                <span style="font-size:24px;">  Total Product: <?php echo isset($total_sales->total_product) ? $total_sales->total_product : 0; ?></span>
                                            </div>
                    
                                        </div> -->
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">Product order list</p>
                        <form action="" method="post" name="delchekcobx">
                            <table id="fkproduct" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total Product</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (!empty($total_sales)) {
                                        $i = 1;
                                        foreach ($total_sales as $list) {
                                            $list = (object) $list;
                                            ?>  <tr>
                                                <td><?php echo $list->name; ?></td>
                                                <td><?php echo ucfirst($list->total_product); ?></td>
                                            </tr>

                                            <?php
                                            $i++;
                                        }
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