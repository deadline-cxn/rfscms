<?
include("header.php");
include("3rdparty/rsslib/rsslib.php");

// table_top("Latest RSS Feeds");

echo "<table width=$site_singletablewidth border=0><tr>";
echo "<td valign=top class=contenttd>";

$result=sc_query("select * from rss_feeds");
$num_feeds=mysql_num_rows($result);
for($i=0;$i<$num_feeds;$i++)
{
	$feed=mysql_fetch_object($result);
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
