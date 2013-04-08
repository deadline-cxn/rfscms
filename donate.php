<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
include("rfs/header.php");
$data=getuserdata($_SESSION['valid_user']);
/*

http://www.sethcoder.com/donate.php?
transaction_subject=SethCoder&
txn_type=web_accept&
payment_date=11%3A01%3A08+Dec+03%2C+2012+PST&
last_name=parson&
residence_country=US&
item_name=SethCoder&
payment_gross=0.02&
mc_currency=USD&
business=seth_coder%40hotmail.com&
payment_type=instant&
protection_eligibility=Ineligible&
payer_status=verified&
verify_sign=A--8MSCLabuvN8L.-MHjxC9uypBtAA-rAcgkOkJxpt6HoLIB2LbOOpkp&txn_id=9T5800559Y911943U&
payer_email=sarahparsonphotography%40hotmail.com&
tax=0.00&
first_name=sarah&
receiver_email=seth_coder%40hotmail.com&
quantity=0&
payer_id=GLBTZ9JF8ZJQS&
receiver_id=8FYMBDPP4TMBC&
memo=Thanks%21&
item_number=SethCoder&
payment_status=Completed&
mc_fee=0.02&
payment_fee=0.02&
mc_gross=0.02&
custom=&
charset=windows-1252&
notify_version=3.7&
merchant_return_link=Return+to+SethCoder&
auth=AZRtzvtUEXne2WKjz3cPP-6egRMVU-vuaSNKcc2Ksb4MI0.Fs7cIt5E1pNJMSdea3eLHnmZyqMoPnc.i6fNOPnA
*/

/*

http://www.sethcoder.com/donate.php?
merchant_return_link=Return+to+SethCoder&
auth=A37TSvUhcLSXx-.1jp5JEkP0hScgEVMaIlhyxnJ85sV-N7uFcv5qLgTo2in87r4Vld5Zsp9.FPWSXHEvdAZHl3A

*/
	
if(!empty($mc_gross)){
	if($mc_gross>0) {
        $SESSION['donated']='true';
		if(!empty($data->name)) {            
			mailgo(
			"defectiveseth@gmail.com",
			"PayPal Donation  $data->name ".$mc_gross,
			"PayPal Donation");
			sc_query("update users set `donated`='yes' where name='$data->name'");
			echo "<p> $data->name... Thank you for donating!  ($data->id)</p>";	
            if($item_name="SethCoder") {
                
            }
            if($item_name="Defectiveminds.com"){
                gotopage("http://www.defectiveminds.com/");
            }
		}
        else{
            echo "<p>You may wish to register so that your donation information gets saved.</p>";
            
            
        }
        

	}
}
else {
	echo "<p> But... muh monies... </p>";
}
include("rfs/footer.php");
?>