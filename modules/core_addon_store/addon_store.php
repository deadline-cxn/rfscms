<?

chdir("../../");
include("header.php");


function addon_store_action_() {
	eval(lib_rfs_get_globals());
	echo "<div class=forum_box>";
	echo "<h1>Addon Store</h1>";

	$r=lib_mysql_query("select * from addon_database where core!='1'");
	while($addon=$r->fetch_object()) {
		echo "<hr>";
		echo "<div class=forum_message>";
		echo "<h3>$addon->name</h3>";
		echo "Category: $addon->category<br>";
		echo "Description: $addon->description<br>";
		echo "Version: $addon->version<br>";
		echo "Sub-Version: $addon->sub_version<br>";
		echo "Release: $addon->release<br>";
		echo "License: $addon->license<br>";
		echo "Rating: $addon->rating<br>";
		echo "Requirements: $addon->requirements<br>";
		echo "Dependencies: $addon->dependencies<br>";
		echo "Cost: $addon->cost<br>";
		echo "Author: $addon->author<br>";
		echo "Email: $addon->author_email<br>";
		echo "Website: $addon->author_website<br>";
		echo "File URL: $addon->file_url<br>";
		echo "GIT Repository: $addon->git_repository<br>";
		
		$ix=explode(",",$addon->images);
		for($x=0;$x<count($ix);$x++) {
			echo "<img src=\"$ix[$x]\">";
		}
		
		
		echo "</div>";
		
	}
	
	
	echo "</div>";
	

}

include("footer.php");

?>

