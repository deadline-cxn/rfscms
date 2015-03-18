<?php
$srch=$_REQUEST['srch'];
echo "<h3>APT-CACHE SEARCH</h3>";
echo "<form>Enter search<input name=srch value='$srch'><input type=submit ></form>";
echo "<pre>";
system("apt-cache search $srch");
echo "</pre>";
?>