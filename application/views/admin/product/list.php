<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Product</h2>
                        <a class="pull-right" href="<?php echo $action_url . '/product/add'; ?>"><button type="button" class="btn btn-primary">Add Product</button></a>
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
                        <form role="form" id="filter-form" accept-charset="utf-8" method="get" action="<?php echo $action_url . '/product'; ?>">
                            <div class="filter-bar">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                                <input autocomplete="off" type="text" name="filter_search" class="form-control input-sm" value="<?php echo isset($filter_search) ? $filter_search : ''; ?>" placeholder="Search By Name or Id" />
                                            </div>
                                            <a class="btn btn-primary filterfm" data-toggle="tooltip"  title="Filter"><i class="fa fa-filter"></i></a>
                                            <a class="btn btn-danger" href="<?php
                                            echo $action_url . '/product';
                                            ?>" data-toggle="tooltip" title="Cancle"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">Product list</p>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Id</th>
                                    <th>Name</th>
                                     <th>Image</th>
                                    <th>Desc</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (!empty($plist)) {
                                    foreach ($plist as $product) {
                                        $pricerate = 0;
                                        $product = (object) $product;

                                        $servsprice = getRow('product_attribute', 'pavalue', array('product_id' => $product->pid), array('column' => 'id', 'orderby' => 'ASC'));
                                        if (!empty($servsprice)) {
                                            $pricerate = $servsprice->pavalue;
                                        }
                                        $image='';
                                          if (@getimagesize(base_url() . 'assets/products/thumbs/' . $product->image)) {
                                $image = base_url() . 'assets/products/thumbs/' . $product->image;
                            } else {
                                $image = base_url() . 'assets/images/no_imge.png';
                                
                            }
                                        
                                        
                                        ?> <tr>

                                            <td><?php echo $product->pid; ?></td>
                                            <td><?php echo ucfirst($product->pname); ?></td>
                                            <td><img width="100" height="100" src="<?php echo $image;  ?>"></td>
                                            <td><?php echo $product->description != null ? ucfirst($product->description) : '---'; ?></td>
                                            <td><?php echo $product->price != null ? number_format($product->price, 2) : '---'; ?></td>
                                            <td><?php echo $product->is_active == 1 ? 'Enabled' : 'Disabled'; ?></td>
                                            <td>
                                                <?php if ($action_url == base_url('admin')) {
                                                    ?>
                                                    <a href="<?php echo $action_url . '/product/del/' . base64_encode($product->pid); ?>" onclick="return confirm('Are you sure? You want to delete this!')"><i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                <?php }
                                                ?>
                                                <a href="<?php echo $action_url . '/product/add/' . base64_encode($product->pid); ?>">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><th class="text-center" colspan="9"> No Records Available</th></tr>';
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