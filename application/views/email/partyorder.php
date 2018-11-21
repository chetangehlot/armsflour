<!DOCTYPE html>

<html>
    <body>
        <p><a href=<?php echo base_url(); ?>><img src=<?php echo base_url('assets/images/ffc24.png'); ?>></a></p>
        <h1 style="text-align:left">Hi, Admin</h1>
        <p>Please find following information of the customer who want to party order </p>
        <table  cellpadding="1" cellspacing="1" style="width:500px;">
            <tbody>
                <tr>
                    <td><strong>Name</strong></td>
                    <td><?php echo $name; ?></td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td><strong><span style="color:#27ae60;"><?php echo $email; ?></span></strong></td>
                </tr>
                <tr>
                    <td><strong>Mobile</strong></td>
                    <td>
                        <p><span style="color:#27ae60;"><strong><?php echo $mobile; ?></strong></span></p>
                    </td>
                </tr>
                <tr>
                    <td><strong>Event Name &nbsp;</strong></td>
                    <td><?php echo $eventname; ?></td>
                </tr>
                <tr>
                    <td><strong>Party Date&nbsp;</strong></td>
                    <td><?php echo $party_date; ?></td>
                </tr>
                <tr>
                    <td><strong>No of peoples</strong></td>
                    <td><?php echo $no_of_people; ?></td>
                </tr>
<!--                <tr>
                    <td><strong>Company</strong></td>
                    <td><?php echo $company_name; ?></td>
                </tr>-->
            </tbody>
        </table>
    </body>
</html>
