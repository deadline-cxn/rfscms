<?php
// RSSlib @ 2RSS.com :: RSS directory, RSS scripts, RSS articles, RSS software
// This scripts are distributed for free and are provided "AS IS". Read docs.htm
// Copyright: Ovi Crisan @ www.2RSS.com


// parameters used for caching

$use_cache=false;
$cache_folder="tmp";
$cache_valid=60; 	// minutes
$display_channel_name=true;		// set to false to hide channel name & link

$channel[]=array("","","","","","");

// ***********************************

function rss2html($url,$m=0,$w=0,$target="_blank",$cssprefix="rsslib") {
global $channel,$items,$display_channel_name;

getrss($url);

if($display_channel_name) {
  if($channel[1]) 
	print "<a href='{$channel[1]}' target='{$target}' class='{$cssprefix}channel'>";
  else 
	print "<span class='{$cssprefix}channel'>";
  print $channel[0];
  if($channel[1]) 
	print "</a><br>";
  else 
	print "</span><br>";
}

if($m==0) $m=count($items);
for($i=0;$i<$m;$i++) {
	// display items
	if($items[$i][1]) 
		print "<a href='{$items[$i][1]}' target='{$target}' class='{$cssprefix}item'>";
	else 
		print "<div class='{$cssprefix}item'>";
	print $items[$i][0];
	if($items[$i][1]) 
		print "</a><br>";
	else 
		print "</div>";
	if($items[$i][2]) {
		print "<div class='{$cssprefix}desc'>";
		if(($w)&&($w<strlen($items[$i][2])))
			print substr($items[$i][2],0,strpos($items[$i][2]," ",$w))." ...";
		else
			print $items[$i][2];
		print "</div>\n";
	}

}

if($channel[4]) print "<div class='{$cssprefix}editor'>By {$channel[4]}</div>\n";
if($channel[5]) print "<div class='{$cssprefix}date'>{$channel[5]}</div>\n";
if($channel[3]) print "<div class='{$cssprefix}copyright'>{$channel[3]}</div>\n";
print "<div class='{$cssprefix}copyright'>Powered by <a href='http://www.2RSS.com' target='_blank' class='{$cssprefix}copyright'>RSSlib</a></div><br>\n";

} //end function rss2html

// ***********************************

function rss2js($url,$m=0,$w=0,$target="_blank",$cssprefix="rsslib") {
global $channel,$items,$display_channel_name;

getrss($url);

if($display_channel_name) {
  if($channel[1]) 
	print "document.write(\"<a href='{$channel[1]}' target='{$target}' class='{$cssprefix}channel'>";
  else 
	print "document.write(\"<span class='{$cssprefix}channel'>";
  print addslashes($channel[0]);
  if($channel[1]) 
	print "</a><br>\");\n";
  else 
	print "</span><br>\");\n";
}

if($m==0) $m=count($items);
for($i=0;$i<$m;$i++) {
print "//".$i."\n";
	// display items
	if($items[$i][1]) 
		print "document.write(\"<a href='{$items[$i][1]}' target='{$target}' class='{$cssprefix}item'>";
	else 
		print "document.write(\"<div class='{$cssprefix}item'>";
	print str_replace("\n","\\n",addslashes($items[$i][0]) );
	if($items[$i][1])
		print "</a><br>\");\n";
	else 
		print "</div>\");\n";
	if($items[$i][2]) {
		print "document.write(\"<div class='{$cssprefix}desc'>";
		if(($w)&&($w<strlen($items[$i][2])))
			print str_replace("\n","\\n",addslashes(substr($items[$i][2],0,strpos($items[$i][2]," ",$w))) )." ...";
		else
			print str_replace("\n","\\n",addslashes($items[$i][2]) );
		print "</div>\");\n";
	}

}

if($channel[4]) print "document.write(\"<div class='{$cssprefix}editor'>By ".addslashes($channel[4])."</div>\");\n";
if($channel[5]) print "document.write(\"<div class='{$cssprefix}date'>".addslashes($channel[5])."</div>\");\n";
if($channel[3]) print "document.write(\"<div class='{$cssprefix}copyright'>".addslashes($channel[3])."</div>\");\n";
print "document.write(\"<div class='{$cssprefix}copyright'>Powered by <a href='http://www.2RSS.com' target='_blank' class='{$cssprefix}copyright'>RSSlib</a></div><br>\");\n";

} //end function rss2js

// ***********************************

function getrss($url) {
global $channel,$items;
global $use_cache,$cache_folder,$cache_valid;

if($use_cache) {
	$cache_filename=$cache_folder."/".md5($url).".rss";
	if(file_exists($cache_filename)) {
		$t=filemtime($cache_filename);
		$cache_create=((!$t)||($t<strtotime("now")-60*$cache_valid)); }
	else
		$cache_create=true;
	
	if($cache_create) {
		//cache not valid - create it again
		$simple = file($url);
		$f=fopen($cache_filename,"w");
		for($i=0;$i<count($simple);$i++)
			fwrite($f,$simple[$i]);
		fclose($f);
		$simple=implode('',$simple);
		}
	else
		$simple = implode('',file($cache_filename));
}
else
	$simple = implode('',file($url));

$p = xml_parser_create();
xml_parse_into_struct($p,$simple,$vals,$index);
xml_parser_free($p);
$type=0;
$tmp[]=array("","","");
$id=0;
for($i=0;$i<count($vals);$i++) {

	if(($vals[$i]['tag']=="CHANNEL")&&($vals[$i]['type']=="open")) $id=$vals[$i]['level']+1;
	if(($type==0)&&($id==$vals[$i]['level']))
		switch($vals[$i]['tag']) {
		case "TITLE": $channel[0]=$vals[$i]['value']; break;
		case "LINK": $channel[1]=$vals[$i]['value']; break;
		case "DESCRIPTION":
        case "CONTENT:ENCODED": $channel[2]=$vals[$i]['value']; break;
		case "COPYRIGHT":
		case "DC:RIGHTS": $channel[3]=$vals[$i]['value']; break;
		case "MANAGINGEDITOR":
		case "DC:PUBLISHER": $channel[4]=$vals[$i]['value']; break;
		case "PUBDATE":
		case "DC:DATE": $channel[5]=$vals[$i]['value']; break;
		}

	else switch($vals[$i]['tag']) {
		case "TITLE": $tmp[0]=$vals[$i]['value']; break;
		case "LINK": $tmp[1]=$vals[$i]['value']; break;
		case "DESCRIPTION":
        case "CONTENT:ENCODED": $tmp[2]=$vals[$i]['value']; break;
		}

	if($vals[$i]['tag']=="ITEM") {
		if(($vals[$i]['type']=="open")&&($type==0)) $type=1;
		if($vals[$i]['type']=="close") {
			$items[]=$tmp;
			$tmp[0]="";
			$tmp[1]="";
			$tmp[2]="";
		}
	}

}

//print_r($channel);
//print_r($items);
} // end function getrss

?>
