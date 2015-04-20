<?php
function adm_action_f_paypal_config_go() {
	eval(lib_rfs_get_globals());
	echo "<h1>Paypal Configuration Update</h1>";
	echo "$ppemailnotice<br>";
	echo "$ppemail<br>";
	echo "$ppbutton1<br>";
	echo "$ppbutton2<br>";
}

function adm_action_paypal_config() {
	eval(lib_rfs_get_globals());
	echo "<h1>Paypal Configuration</h1>";
	echo "<form method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php?action=f_paypal_config_go\">";
	echo "Send transaction notifications:<select name=ppemailnotice><option>$RFS_SITE_PAYPAL_EMAILS<option>yes<option>no</select> <BR>";
	echo "Email to send transaction notifications: <input name=ppemail value=\"$RFS_SITE_PAYPAL_EMAIL\"><br>";
	echo "Paypal Button 1 ID: <input name=ppbutton1 value=\"$RFS_SITE_PAYPAL_BUTTON1\"><br>";
	echo "Paypal Button 2 ID: <input name=ppbutton2 value=\"$RFS_SITE_PAYPAL_BUTTON2\"><br>";
	echo "<input type=submit value=\"Update\">";
	echo "</form>";
}

function lib_paypal_record_transaction() {
	eval(lib_rfs_get_globals());
	lib_mysql_query("
insert into transactions
(	`transaction_subject`,
	`txn_type`,
	`payment_date`,
	`last_name`,
	`residence_country`,
	`item_name`,
	`payment_gross`,
	`mc_currency`,
	`business`,
	`payment_type`,
	`protection_eligibility`,
	`payer_status`,
	`verify_sign`,
	`payer_email`,
	`tax`,
	`first_name`,
	`receiver_email`,
	`quantity`,
	`payer_id`,
	`receiver_id`,
	`memo`,
	`item_number`,
	`payment_status`,
	`mc_fee`,
	`payment_fee`,
	`mc_gross`,
	`custom`,
	`charset`,
	`notify_version`,
	`merchant_return_link`,
	`auth` )
	
VALUES (
	`$transaction_subject`,
	`$txn_type`,
	`$payment_date`,
	`$last_name`,
	`$residence_country`,
	`$item_name`,
	`$payment_gross`,
	`$mc_currency`,
	`$business`,
	`$payment_type`,
	`$protection_eligibility`,
	`$payer_status`,
	`$verify_sign`,
	`$payer_email`,
	`$tax`,
	`$first_name`,
	`$receiver_email`,
	`$quantity`,
	`$payer_id`,
	`$receiver_id`,
	`$memo`,
	`$item_number`,
	`$payment_status`,
	`$mc_fee`,
	`$payment_fee`,
	`$mc_gross`,
	`$custom`,
	`$charset`,
	`$notify_version`,
	`$merchant_return_link`,
	`$auth`
				);");
}

