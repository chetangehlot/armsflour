</div>
</div>
<!-- footer content -->
<footer>
    <div class="pull-right">
        Cafe
    </div>
    <div class="clearfix"></div>
</footer>

<!-- /footer content -->
<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo base_url(); ?>assets/admin/js/custom.min.js"></script>

<?php
$crt_cls = $this->router->fetch_class();
if ($crt_cls === 'product') {
    ?>
 <!--   <script src="<?php echo base_url(); ?>assets/js/datepicker/moment.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datetimepicker.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet">-->
    <script src="<?php echo base_url(); ?>assets/admin/js/admin.js"></script>
<!--    <script type="text/javascript">
        $(function () {
            $('.datetimepicker3').datetimepicker({
                format: 'LT'
            });
        });
        $(document).on('click', '.filterfm', function () {
            $('#filter-form').submit();
        });
    </script>-->
    <?php
}
if ($crt_cls == 'order') {
    ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datepicker.min.js"></script>
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
    <?php } ?>

</body>
</html>