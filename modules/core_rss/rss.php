<?
if(stristr(getcwd(),"modules")) { chdir("../../"); }
include("header.php");
include("3rdparty/rsslib/rsslib.php");

// table_top("Latest RSS Feeds");

echo "<table width=$site_singletablewidth border=0><tr>";
echo "<td valign=top class=contenttd>";

$result=lib_mysql_query("select * from rss_feeds");
$num_feeds=$result->num_rows;
for($i=0;$i<$num_feeds;$i++)
{
	$feed=$result->fetch_object();
	// echo $feed->feed."<br>";
	echo RSS_display($feed->feed, 3, false);
	// putnews($feed->feed);
}

//echo RSS_display("http://www.wowarmory.com/character-feed.atom?r=Bladefist&cn=Smashed&locale=en_US", 5, true);
//echo RSS_display("http://rss.cnn.com/rss/cnn_topstories.rss", 5, true);

echo "</td></tr></table>";
//table_bottom();
include("footer.php");
?>
