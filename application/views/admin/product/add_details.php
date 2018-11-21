<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo $page_title; ?> Product </h2>
                        <a class="pull-right" href="<?php echo $action_url . '/product'; ?>"><button type="button" class="btn btn-primary">Product List</button></a>
                        <div class="clearfix"></div>

                        <?php
                        if ($this->session->flashdata('success')) {
                            echo ' <div class = "alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                            <strong>Success!</strong>' . $this->session->flashdata('success') . '
                            </div>';
                        }
                        if ($this->session->flashdata('error')) {
                            echo '<div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                            <strong>Danger!</strong> ' . $this->session->flashdata('error') . '
                                ' . print_r(validation_errors()) . '
                            </div>';
                        }
                        if (!empty(validation_errors())) {
                            echo '<div class = "alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                            <strong>Danger!</strong> ' . $this->session->flashdata('error') . '
                                ' . validation_errors() . '
                            </div>';
                        }
                        // ' . validation_errors() . '
                        ?>
                    </div>
                    <?php //echo form_open_multipart('admin/product/add', ['class' => 'form-horizontal form-label-left','novalidate', 'id' => 'servicesid'])    ?>
                    <form  onsubmit="return addproduct();"action="" class="form-horizontal form-label-left" novalidate id="frmproduct" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="tab-container">
                            <div class="row wrap-vertical">
                                <ul id="nav-tabs" class="nav nav-tabs">
                                    <li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">Product</a></li>
                                    <li class=""><a href="#product-options" data-toggle="tab" aria-expanded="false">Options </a></li>
                                </ul>
                            </div>


                            <div class="tab-content">
                                <div id="general" class="tab-pane row wrap-all active">
                                                                        <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Product Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php echo form_input(['name' => 'name', 'value' => isset($productlist->pname) ? $productlist->pname : set_value('name'), 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'data-validate-length-range' => "6", 'data-validate-words' => "2", 'placeholder' => 'Enter Product Name']) ?>
                                            <?php echo form_error('name', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Product Description</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <?php echo form_input(['name' => 'description', 'value' => isset($productlist->description) ? $productlist->description : set_value('description'), 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Enter Description']) ?>

                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Product Price <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <?php echo form_input(['name' => 'price', 'type'=>'number','value' => isset($productlist->price) ? $productlist->price : set_value('price'), 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Enter price']) ?>

                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Category  <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if (!empty($pcategory)) { ?>
                                                <select class="form-control" name="categoryid[]" multiple="" required="required">
                                                    <!--<option value="">Choose Category</option>-->
                                                    <?php
                                                    foreach ($pcategory as $pcat) {
                                                        ?>
                                                        <?php
                                                        if (isset($categoryproduct)) {
                                                            ?> 
                                                            <option value="<?php echo $pcat['id']; ?>" <?php echo in_array($pcat['id'], $categoryproduct) ? " selected " : ''; ?> ><?php echo $pcat['name']; ?></option>
                                                            <?php ?>

                                                        <?php } else { ?>
                                                            <option value="<?php echo $pcat['id']; ?>" <?php echo set_select('categoryid[]', $pcat['id']); ?> ><?php echo $pcat['name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>

                                                        <!--                                                        <option  <?php
                                                        if (isset($category->parent_id) && ($category->parent_id == $pcat['id'])) {
                                                            echo 'selected="selected" ';
                                                        }
                                                        ?>  value="<?php echo $pcat['id']; ?>"><?php echo ucfirst($pcat['name']); ?>
                                                                                                                </option>-->
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            } else {
                                                echo '<span class="text-danger"> There is no category. Please add category click on plus icon</span>';
                                                ?>

                                            <?php }
                                            ?>
                                            <?php echo form_error('categoryid[]', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                        <?php if (empty($pcategory)) { ?>
                                            <div class="col-md-3 col-sm-3 col-xs-12"><a href="<?php echo $action_url . '/category/add'; ?>"><i class="fa fa-plus-square fa-2x text-danger"></i></div>
                                        <?php } ?>
                                    </div>




                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12 text-left">Product Image<span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="file" name="image" />
                                            <?php
                                            if (isset($productlist->image)) {

                                                $imagepath = base_url('assets/products/thumbs/') . $productlist->image;
                                                if (@getimagesize($imagepath)) {
                                                    ?>
                                                    <img src="<?php echo $imagepath; ?>" class="img-thumbnail img-responsive" />
                                                    <?php
                                                }
                                            }
                                            ?>

                                            <label class="text-info">Image with and height should be more then 500 pixel</label>
                                        </div>
                                    </div>

                                    <?php if (!empty($productlist->pid)) {
                                        ?> 
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control" name="status">
                                                    <option <?php echo $productlist->is_active == 1 ? 'selected="selected"' : ''; ?> value="1">enable</option>
                                                    <option <?php echo $productlist->is_active == 0 ? 'selected="selected"' : ''; ?> value="0">disable</option>
                                                </select>

                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>

                                <!-- Product attribute with multiple option -->
                                <div id="product-options" class="tab-pane row wrap-all field_wrapper">
                                    <?php
                                    if ((!empty($productlist->pid)) && (!empty($productattribute))) {
                                        $i = 1;
                                        foreach ($productattribute as $attr) {
                                            ?>

                                            <div>
                                                <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left" value="<?php echo $attr['paname']; ?>" name="optionname[]" id="inputSuccess2" placeholder="Serves Name">
                                                    <span class="fa fa-circle-o form-control-feedback left" aria-hidden="true"></span>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                    <input type="text" class="form-control has-feedback-left" value="<?php echo $attr['description']; ?>" name="des[]" id="inputSuccess1" placeholder="Serves Description">
                                                    <span class="fa fa-circle-o form-control-feedback left" aria-hidden="true"></span>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                    <input type="number" class="form-control has-feedback-left" name="optionprice[]" value="<?php echo $attr['pavalue']; ?>" id="inputSuccess3" placeholder="Price">
                                                    <span class="fa fa fa-rupee form-control-feedback left" aria-hidden="true"></span>

                                                </div>

                                                <?php if ($i === 1) { ?>
                                                    <a href="javascript:;" class="col-md-3 col-sm-3 col-xs-12" id="add_more" ><i class="fa fa-plus-square fa-2x text-success"></i></a>
                                                <?php } else {
                                                    ?>
                                                    <a href="javascript:void(0);" class="remove_button col-md-3 col-sm-3 col-xs-12" title="Remove field"><i class="fa fa-minus-square fa-2x text-danger"></i></a>
                                                <?php }
                                                ?>
                                                <div class="clearfix"></div>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>

                                        <div>
                                            <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                <input type="text" class="form-control has-feedback-left" name="optionname[]" id="inputSuccess2" placeholder="Serves Name">
                                                <span class="fa fa-circle-o form-control-feedback left" aria-hidden="true"></span>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                <input type="text" class="form-control has-feedback-left" name="des[]" id="inputSuccess1" placeholder="Serves Description">
                                                <span class="fa fa-tint form-control-feedback left" aria-hidden="true"></span>
                                            </div>

                                            <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                                                <input type="number" class="form-control has-feedback-left" name="optionprice[]" id="inputSuccess3" placeholder="Price">
                                                <span class="fa fa fa-rupee form-control-feedback left" aria-hidden="true"></span>

                                            </div>
                                            <a href="javascript:;" class="col-md-3 col-sm-3 col-xs-12" id="add_more" ><i class="fa fa-plus-square fa-2x text-success"></i></a>
                                            <?php echo form_error('optionname[]', '<span class="text-danger">', '</span>'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php echo form_error('optionname[]', '<span class="text-danger">', '</span>'); ?>

                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <?php echo form_submit(['name' => 'submit', 'id' => 'send', 'value' => $page_title . ' Product', 'class' => 'btn btn-primary']) ?>
                                        <a href="<?php echo $action_url . '/product'; ?>" class="btn btn-danger">Cancel</a>
                                        <input name="productid" type="hidden"value="<?php echo isset($productlist->pid) ? base64_encode($productlist->pid) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>