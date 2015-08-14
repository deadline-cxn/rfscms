<?php
chdir("..");
include("include/lib.all.php");

if(!empty($goback)) $_SESSION['goback']=$goback;
	
include("fb.login.php");
echo "<html><head>";
echo "<title>$RFS_SITE_NAME</title></head>
<body style='background-color: #ffffff;'>";
$data=lib_users_get_data($_SESSION['valid_user']);
if($data->id) {
    // echo "$data->name<BR>";
    lib_domain_gotopage($RFS_SITE_URL);
}

?>

