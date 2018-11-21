
$(document).on('keyup', '.autoprice', function () {

    var discountp = $("#discount").val();
    var fprice = $("#price").val();
    var hprice = $("#halfprice").val();
    if (fprice != '' && hprice != '') {
        if (parseInt(fprice) <= parseInt(hprice)) {
            alert('Full Price should be greater then Half Price');
            $("#halfprice").val(0);
            $('#discount_halfprice').val(0);
            hprice = 0;
        }
    }

    if (fprice) {
        if (discountp) {
            var dfull = (fprice * discountp) / 100;
            fprice = fprice - dfull;
            fprice = fprice.toFixed(2);
            $('#discount_price').val(fprice);
        } else {
            $('#discount_price').val(fprice);
        }

    }
    if (hprice) {
        if (discountp) {
            var dishalf = (hprice * discountp) / 100;
            hprice = hprice - dishalf;
            hprice = hprice.toFixed(2);
            $('#discount_halfprice').val(hprice);
        } else {
            $('#discount_halfprice').val(hprice);
        }
    }
});
// this for multipal location added

$(document).ready(function () {
    var locationmaxfiled = 20; //Input fields increment limitation
    var addButton12 = $('#add_more_location'); //Add button selector
    var wrapper12 = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="name[]" value="" class="form-control col-md-7 col-xs-12" required="" placeholder="Enter Location Name"></div><a href="javascript:void(0);" class="remove_button col-md-3 col-sm-4 col-xs-12" data-toggle="tooltip" title="Remove field"><i class="fa fa-minus-square fa-2x text-danger"></i></a></div>';
    var x = 1; //Initial field counter is 1
    $(addButton12).click(function () { //Once add button is clicked
        if (x < locationmaxfiled) { //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper12).append(fieldHTML); // Add field html
        }
    });
    $(wrapper12).on('click', '.remove_button', function (e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});

//function addproduct() {
//    pnam = $("#inputSuccess2").val();
//    pvalue = $("#inputSuccess3").val();
//    if (pnam == '') {
//        alert('Please enter Serves and Price');
//        return false;
//    }
//    if (pvalue == '') {
//        alert('Please enter Serves Price');
//        return false;
//    }
//
//    if (pnam != '' && pvalue != '') {
//        return true;
//    }
//
//}

$(document).ready(function () {
    var maxField = 20; //Input fields increment limitation
    var addButton = $('#add_more'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="clearfix"></div><div><div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"><input type="text" class="form-control has-feedback-left" name="optionname[]"  placeholder="Serves Name"><span class="fa fa-circle-o form-control-feedback left" aria-hidden="true"></span></div><div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"><input type="text" class="form-control has-feedback-left" name="des[]"  placeholder="Serves Description"> <span class="fa fa-tint form-control-feedback left" aria-hidden="true"></span></div><div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback"> <input type="number" class="form-control has-feedback-left" name="optionprice[]" placeholder="Price"><span class="fa fa fa-rupee form-control-feedback left" aria-hidden="true"></span> </div> <a href="javascript:void(0);" class="remove_button col-md-2 col-sm-2 col-xs-12" title="Remove field"><i class="fa fa-minus-square fa-2x text-danger"></i></a></div>';
    var x = 1; //Initial field counter is 1
    $(addButton).click(function () { //Once add button is clicked
        if (x < maxField) { //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});