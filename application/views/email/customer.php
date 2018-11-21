<!DOCTYPE html>

<html>
    <body>
        <p><a href=<?php echo base_url(); ?>><img src=<?php echo base_url('assets/images/ffc24.png'); ?>></a></p>
        <h1 style="text-align:left">Hi, <?php echo $c_name; ?> </h1>
        <p>Thank you for your interest in Order delivery. Your order has been received and will be delivered ASAP </p>
        <p>To view your order click on the link below: <br>
            <a href="<?php echo base_url('profile/orderdetails/') . $orderid; ?>"> Click Order Details</a>
        </p>


        <table  rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px;" cellpadding="10">
            <tr >
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222' colspan='2'>
                    <b>Order Details</b>
                </td>
            </tr>
            <tr>
                <td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'>
                    <b>Order Id: </b><?php echo $last_id; ?>
                    <br> 
                    <b>Date Added:</b> <?php echo date('d-m-Y h:i:s', strtotime($date)); ?> <br> 
                    <b>Payment Method:</b> <?php echo ucfirst($codorwallet); ?> <br> <b>Payment Status:</b> Pending 
                </td>
                <td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'>
                    <b>Email:</b><?php echo $postemail; ?>  <br> 
                    <b>Mobile:</b> <?php echo $postsmobile; ?> <br> 
                    <b>Order Status:</b> Received 
                </td>
            </tr>
        </table>
        <table rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px" cellpadding="10">';
            <tr>
                <td style='font-size:12px;border:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b> Sender Details </b>
                </td>
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b> Delivery Address </b>
                </td>
            </tr>
            <tr>
                <td style='font-size:12px;border:1px solid #dddddd;text-align:left;padding:7px'>
                    <b>Name: </b><?php echo $c_name; ?>  <br> 
                    <b>Email: </b>
                    <?php echo $postemail; ?>  
                    <br> 
                    <b>Mobile:</b> 
                    <?php echo $postsmobile; ?></td>
                <td style='border-collapse:collapse;width:100%;border:1px solid #dddddd;margin-bottom:20px'>
                    <?php echo $fulladdress; ?>
                </td>
            </tr>
        </table>
        <!-- comment for the latest -->
        <table rules="all" style="border-collapse:collapse;width:100%;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px" cellpadding="10">';
            <tr>
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b>Product </b>
                </td>
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b>Quantity </b>
                </td>
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b>Price </b>
                </td>
                <td style='font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222'>
                    <b>Total </b>
                </td>
            </tr>
            <?php $productorderhtml .= '<tr style="border-top:2px solid #cacaca;">
                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Sub-Total:</b></td>
                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $sum . '</td>
            </tr><tr>
                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Shipping:</b></td>
                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $shipcharge . '</td>
            </tr>';
            ?>
            <?php if ($paidwallet > 0) { ?>
                <tr>
                    <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Wallet:</b></td>
                    <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">- <?php echo $paidwallet; ?></td>
                </tr>
                <?php
            }

            if (isset($tax_gst_shipping) && ($tax_gst_shipping['gst_amount'] > 0)) {
                $productorderhtml .= '<tr>
                                    <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>' . $tax_gst_shipping['sgst_cgst'] . ':</b></td>
                                    <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $tax_gst_shipping['gst_amount'] . '</td>
                                  </tr>';
            }

            $productorderhtml .= '<tr style="border-top:2px solid #cacaca;">
                                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px" colspan="3"><b>Total:</b></td>
                                <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:right;padding:7px">' . $trackpayment . '</td>
                                </tr>';
            echo $productorderhtml;
            ?>

            <p>Please reply to this e-mail if you have any questions.</p>
        </table>
    </body>
</html>