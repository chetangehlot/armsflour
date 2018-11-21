</div>
</div>
<!-- footer content -->
<footer>
    <div class="pull-right">
        Cafe
    </div>
    <div class="clearfix"></div>
</footer>


<!-- Model -->
<div id="ordernew" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background: rgba(253, 70, 62, 0.84);color: #FFF;text-align: center;padding: 5px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-info-circle fa-2x" ></i>  New Order Notice</h4>
                <audio controls   id="video1" >
                    <source src="<?php echo base_url('assets/uploads/'); ?>idea_mp3_tone.mp3" type="audio/mpeg">
                    <p class="alert-danger">your browser does not support the audio element.</p>
                </audio>

            </div>
            <div class="modal-body">
                <p>Please check order list, Some new order is came... <a class="btn btn-dark" href="<?php echo base_url('admin/order'); ?>">Order list</a></p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- /footer content -->
<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo base_url(); ?>assets/build/js/custom.min.js"></script>

<?php
$crt_cls = $this->router->fetch_class();
if ($crt_cls === 'product') {
    ?>
    <script src="<?php echo base_url(); ?>assets/js/datepicker/moment.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datetimepicker.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            });
        });
        $(document).on('click', '.filterfm', function () {
            $('#filter-form').submit();
        });
    </script>
    <?php
}
if ($crt_cls == 'order') {
    ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/order.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/tableexport/tableExport.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tableexport/jquery.base64.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tableexport/jspdf/libs/sprintf.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tableexport/jspdf/libs/base64.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tableexport/jspdf/jspdf.js"></script>

    <script type="text/javascript">
        $('.form_datetime').datepicker({
            format: 'dd-mm-yyyy',
            setStartDate: 'today',
            todayHighlight: true,
            autoclose: true,
        });

        $(document).on('click', '.filterfm', function () {
            $('#filter-form').submit();
        });

    </script>


    <?php
}
if ($crt_cls == 'kitchen') {
    ?>
    <script src="<?php echo base_url(); ?>assets/js/kitchen.js"></script>
    <?php
}
if ($crt_cls == 'locations') {
    ?>
    <script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.filterfm', function () {
            $('#filter-form').submit();
        });
    </script>
    <?php
}
if ($crt_cls == 'category') {
    ?>

    <script type="text/javascript">
        $(document).on('click', '#is_special', function () {
            if ($('#is_special').is(":checked"))
            {
                $("#is-special").fadeIn(500);
                $("#banner_image").attr("required", "");

            } else {
                $("#is-special").fadeOut(500);
                $("#banner_image").removeAttr("required");
            }
        });
    </script>
    <?php
}
if ($crt_cls == 'notification') {
    $ntmc = isset($notification->tmc) ? $notification->tmc : '';
    ?>
    <script src="<?php echo base_url(); ?>assets/js/noti.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/fnt/css/jquery-te-1.4.0.css">
    <script src="<?php echo base_url(); ?>assets/fnt/js/jquery-te-1.4.0.min.js"></script>
    <script type="text/javascript">
        // editor for the textarea
        $(function () {
            $('.jqte-test').jqte();

        });
        $('textarea[name="tmc"]').html('<?php echo $ntmc; ?>');
    </script>


    <?php
}
if ($crt_cls === 'coupon') {

    $is_product = isset($is_product) ? $is_product : '';
    $is_customer = isset($is_customer) ? $is_customer : '';
    $apply_for = isset($coupon->apply_for) ? $coupon->apply_for : '';
    $tmc = isset($coupon->tmc) ? $coupon->tmc : '';
    ?>

    <script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datepicker.min.js"></script>

    <!-- Textarea editor for the wysiwyg plugin attached -->
    <!--    <link href="<?php echo base_url(); ?>assets/fnt/wysiwg_editor/froala_editor.pkgd.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/fnt/wysiwg_editor/froala_style.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/fnt/wysiwg_editor/js/froala_editor.pkgd.min.js"></script>-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/fnt/css/jquery-te-1.4.0.css">
    <script src="<?php echo base_url(); ?>assets/fnt/js/jquery-te-1.4.0.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            var isprodt = '<?php echo $is_product; ?>';
            var iscust = '<?php echo $is_customer; ?>';
            var apply_for = '<?php echo $apply_for; ?>';
            if (isprodt == 1) {
                $("#PRODUCT_selecttype").fadeIn();
            } else if (iscust == 1) {
                $("#CUSTOMER_selecttype").fadeIn();
            }

            if (apply_for != 'ALL') {
                $("#" + apply_for + "_selecttype").fadeIn();
            }
        });
        $(document).on('change', '#apply_for', function () {
            var typeval = $(this).val();
            if (typeval === 'ALL') {
                $(".cmnaply").fadeOut(1000);
            } else {
                $(".cmnaply").fadeOut(800);
                $("#" + typeval + "_selecttype").fadeIn(1500);
            }
        });
        $('.form_datetime').datepicker({
            format: 'dd-mm-yyyy',
            setStartDate: 'today',
            todayHighlight: true,
            autoclose: true,
        });
        // editor for the textarea
        $(function () {

            $('.jqte-test').jqte();
        });
        $('textarea[name="tmc"]').html('<?php echo $tmc; ?>');
    </script>
    <?php
}
if ($crt_cls === 'slider') {
    ?>
    <script type="text/javascript">
        var myfile = "";
        $('#sliderfile').on('change', function () {
            myfile = $(this).val();
            var extension = myfile.replace(/^.*\./, '');
            if (extension == "png" || extension == 'jpeg' || extension == 'jpg') {
                $('#errorcls').fadeOut(500);
            } else {
                $('#errorcls').fadeIn(100);
                $('#errorcls').fadeOut(5000);
            }
        });

        $(document).ready(function () {
            $('#sliderupload').on('submit', function (e) {
                $('#loaderimg').show();
                $(':input[type="submit"]').prop('disabled', true);
            });
        });
    </script>
    <?php
}
if ($crt_cls === 'crm') {
    ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        $('.form_datetime').datepicker({
            format: 'dd-mm-yyyy',
            setStartDate: 'today',
            todayHighlight: true,
            autoclose: true,
        });
    </script>
    <!-- datatables -->

    <link href="<?php echo base_url(); ?>assets/vendors/datatables.net/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendors/datatables.net/css/buttons.dataTables.min.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datatables.net/js/buttons.print.min.js"></script>


    <script>

        $(document).ready(function () {
        $('#fkdelivery').DataTable({
        dom: 'Bfrtip',
                bSort: false,
                buttons: [
                        'csv', 'excel', 'pdf', 'print'
                ]
        });
                $('#fkproduct').DataTable({
        dom: 'Bfrtip',
                bSort: false,
                buttons: [
                        'csv', 'excel', 'pdf', 'print'
                ]
        });
        }
        );
    </script>

    <?php
}
?>
<script type="text/javascript">
            $(document).on('click', '.filterfm', function () {
    $('#filter-form').submit();
    });</script>
<script type="text/javascript">
            base_url = '<?php echo base_url(); ?>';
            setInterval(checkneworder, 30000); //300000 MS == 30 seconds

            function checkneworder() {
            var popupids = 1;
                    $.ajax({
                    url: base_url + 'admin/Dashboard/countOrder',
                            type: 'post',
                            cache: false,
                            data: {ids: popupids},
                            dataType: 'json',
                            success: function (data)
                            {
                            if (data) {

                            $("#ordernew").modal('show');
                                    $('#ordernew').on('shown.bs.modal', function () {
                            $('#video1')[0].play();
                            })
                                    $("#totalorder").html(data);
                                    $("#orderstatus").css("background-color", "black");
                                    alert('New order ');
                            }
                            }
                    });
            }
    $('#ordernew').on('hidden.bs.modal', function () {
    $('#video1')[0].pause();
            location.reload();
    })


</script>

</body>
</html>