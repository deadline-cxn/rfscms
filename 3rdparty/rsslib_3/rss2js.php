<?php
// RSSlib @ 2RSS.com :: RSS directory, RSS scripts, RSS articles, RSS software
// This scripts are distributed for free and are provided "AS IS". Read docs.htm
// Copyright: Ovi Crisan @ www.2RSS.com

error_reporting(0);

print "document.write('<link rel=\"stylesheet\" href=\"rsslib.css\" type=\"text/css\">');\n";
include_once("rsslib.php");
$url=$_GET['rss_url'];
if(!$url) 
	print "document.write('Error: URL missing!');";
else {
	$m=$_GET['rss_items'];
	if(!$m) $m=0;
	$ch=$_GET['rss_chars'];
	if(!$ch) $ch=0;
	$t=$_GET['rss_target'];
	if(!$t) $t="_blank";
	$css=$_GET['rss_css'];
	if(!$css) $css="rsslib";
	rss2js($url,$m,$ch,$t,$css);
}
?>
