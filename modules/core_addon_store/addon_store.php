<?

chdir("../../");
include("header.php");


function addon_store_action_icon_list() {
	$r=lib_mysql_query("select * from addon_database where core!='1'");
	while($addon=$r->fetch_object()) {
		echo "<div style='
		float:left;
		border: 1px solid #000000;
		margin: 5px;
		padding:5px 10px;
		background:#a5a5a5;
		border-radius:12px;
		width: 150px;
		height: 150px;
		text-align: center;
		'>";			
		echo "<h3>$addon->name</h3><hr>";
		$ix=explode(",",$addon->images);
		if(count($ix)==1) $ix[0]="$RFS_SITE_URL/images/icons/Box.png";
		echo "<a href=\"$RFS_ADDON_URL?action=details&id=$addon->id\">";
		echo "<img src=\"$ix[0]\" width=128 height=128></a><br>";
		echo "</div>";		
	}
}
function addon_store_action_details() {
	$id=$_REQUEST['id'];
	$r=lib_mysql_query("select * from addon_database where id='$id'");
	$addon=$r->fetch_object();
	

	echo "<div class=forum_message>";

	echo "<h3>$addon->name</h3>";
	
	echo "<br>";
	$ix=explode(",",$addon->images);
	if(count($ix)==1) $ix[0]="$RFS_SITE_URL/images/icons/Box.png";
	for($x=0;$x<count($ix);$x++) {
		if(!empty($ix[$x]))
		echo "<img src=\"$ix[$x]\">";
	}
		
	echo "<br>";	
	
	echo "<br>";
	echo " ADD INSTALL BUTTON HERE <br>";
	echo "<br>";
	

	
	
	if(empty($addon->category)) $addon->category="Uncategorized";
	echo "Category: $addon->category<br>";		
	echo "Description: $addon->description<br>";
	echo "Version: $addon->version<br>";
	if(!empty($addon->sub_version)) 
	echo "Sub-Version: $addon->sub_version<br>";
	if(empty($addon->release)) $addon->release="Alpha";
	echo "Release: $addon->release<br>";
	if(empty($addon->license)) $addon->license="No license";
	echo "License: $addon->license<br>";
	if(empty($addon->rating)) $addon->rating="*****";
	echo "Rating: $addon->rating<br>";
	if(empty($addon->requirements)) $addon->requirements="No requirements";
	echo "Requirements: $addon->requirements<br>";
	if(empty($addon->dependencies)) $addon->dependencies="No dependencies";
	echo "Dependencies: $addon->dependencies<br>";
	if( empty($addon->cost) || $addon->cost==0 ) $addon->cost="Free";
	echo "Cost: $addon->cost<br>";
	if(empty($addon->author)) $addon->author="Unknown";
	echo "Author: $addon->author<br>";
	if(empty($addon->author_email)) $addon->author_email="Unknown";
	echo "Email: $addon->author_email<br>";
	if(empty($addon->author_website)) $addon->author_website="Unknown";
	echo "Website: $addon->author_website<br>";
	if(empty($addon->file_url)) $addon->file_url=" ? ";
	echo "File URL: $addon->file_url<br>";
	if(!empty($addon->git_repository)) echo "GIT Repository: <a href=\"$addon->git_repository\" target=\"_blank\">$addon->git_repository</a><br>";
	

	
	echo "</div>";
		
	
}
function addon_store_action_detail_list() {

	$r=lib_mysql_query("select * from addon_database where core!='1'");
	while($addon=$r->fetch_object()) {
		
		
		
		echo "<div class=forum_message>";
		echo "<h3>$addon->name</h3>";		
		if(empty($addon->category)) $addon->category="Uncategorized";
		echo "Category: $addon->category<br>";		
		echo "Description: $addon->description<br>";
		echo "Version: $addon->version<br>";
		if(!empty($addon->sub_version)) 
		echo "Sub-Version: $addon->sub_version<br>";
		if(empty($addon->release)) $addon->release="Alpha";
		echo "Release: $addon->release<br>";
		if(empty($addon->license)) $addon->license="No license";
		echo "License: $addon->license<br>";
		if(empty($addon->rating)) $addon->rating="*****";
		echo "Rating: $addon->rating<br>";
		if(empty($addon->requirements)) $addon->requirements="No requirements";
		echo "Requirements: $addon->requirements<br>";
		if(empty($addon->dependencies)) $addon->dependencies="No dependencies";
		echo "Dependencies: $addon->dependencies<br>";
		if( empty($addon->cost) || $addon->cost==0 ) $addon->cost="Free";
		echo "Cost: $addon->cost<br>";
		if(empty($addon->author)) $addon->author="Unknown";
		echo "Author: $addon->author<br>";
		if(empty($addon->author_email)) $addon->author_email="Unknown";
		echo "Email: $addon->author_email<br>";
		if(empty($addon->author_website)) $addon->author_website="Unknown";
		echo "Website: $addon->author_website<br>";
		if(empty($addon->file_url)) $addon->file_url=" ? ";
		echo "File URL: $addon->file_url<br>";
		if(!empty($addon->git_repository)) echo "GIT Repository: <a href=\"$addon->git_repository\" target=\"_blank\">$addon->git_repository</a><br>";
		
		$ix=explode(",",$addon->images);
		for($x=0;$x<count($ix);$x++) {
			if(!empty($ix[$x]))
			echo "<img src=\"$ix[$x]\">";
		}
		
		
		echo "</div>";
		
	}
	
}

function addon_store_action_() {
	eval(lib_rfs_get_globals());
	echo "<div class=forum_box>";
	echo "<h1>Addon Store</h1>";

	addon_store_action_icon_list();
	
	echo "</div>";
	

}

include("footer.php");

?>

