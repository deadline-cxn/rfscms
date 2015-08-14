<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
include("header.php");
if(!empty($mc_gross)){
	if($mc_gross>0) {
		lib_paypal_record_transaction();
        $SESSION['donated']='true';
		if(!empty($data->name)) {
			if(lib_rfs_bool_check($RFS_SITE_PAYPAL_EMAILS)) {
				mailgo( $RFS_SITE_PAYPAL_EMAIL, "PayPal Donation  $data->name ".$mc_gross, "PayPal Donation");
			}
			lib_mysql_query("update `users` set `donated`='yes' where name='$data->name'");
			echo "<p> $data->name... Thank you for donating!  ($data->id)</p>";
		}
		else {
			echo "<p>You may wish to register so that your donation information gets saved.</p>";
        }
	}
}
else {
	echo "<p> But... muh monies... </p>";
}
include("footer.php");
?>