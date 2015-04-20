<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_social_javascripts() {
	
	/*
<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
		js.src = \"https://connect.facebook.net/en_GB/all.js#xfbml=1&appId=$RFS_SITE_FACEBOOK_APP_ID\";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

		
*/	
	eval(lib_rfs_get_globals());
	echo "
<div id=\"fb-root\"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=$RFS_SITE_FACEBOOK_APP_ID&version=v2.0\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<script type=\"text/javascript\">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script> 

<script>
 !function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0];
	if(!d.getElementById(id)) {
	js=d.createElement(s);
	js.id=id;
	js.src='https://platform.twitter.com/widgets.js';
	fjs.parentNode.insertBefore(js,fjs);
	}
} (document,'script','twitter-wjs'); </script> 

<script type='text/javascript'>
	(function() {
	var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
	li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
	})();
</script>

<script type=\"text/javascript\" src=\"//assets.pinterest.com/js/pinit.js\">
</script>
		

	";
		
}
function module_share_bar() {
	echo '
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_reddit"></a>
<a class="addthis_button_stumbleupon"></a>
<a class="addthis_button_delicious"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" 
src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$RFS_SITE_ADDTHIS_ACCT.'"></script>
<!-- AddThis Button END --> ';	
}

function lib_social_stumble_upon_badge($u) {
	
	// $u=urlencode($u);
	// echo $u;
	echo " <su:badge layout='2' location='$u'></su:badge> ";
}
function lib_social_paypal() { eval(lib_rfs_get_globals());
if(empty($RFS_SITE_PAYPAL_BUTTON1)) return;
	echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=';
	echo $RFS_SITE_PAYPAL_BUTTON1;
	echo '" target=_blank> ';
	$vr=$RFS_SITE_URL;
	echo "<img src='$vr/images/icons/Paypal.png' border='0' height='32' alt='$RFS_SITE_PAYPAL_BUTTON1_MSG'></a>";
}
function lib_social_paypal2() { eval(lib_rfs_get_globals());
if(empty($RFS_SITE_PAYPAL_BUTTON2)) return;
	echo " <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
			<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
			<input type=\"hidden\" name=\"hosted_button_id\" value=\"$RFS_SITE_PAYPAL_BUTTON2\">
			<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif\" border=\"0\" name=\"submit\" alt=\"$RFS_SITE_PAYPAL_BUTTON2_MSG\">
			<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">
			</form>";
}


function lib_social_share_bar4($u,$t,$i) {
	eval(lib_rfs_get_globals());
	if(lib_rfs_bool_true($RFS_SITE_NO_SHARING)) return;
	if(!empty($RFS_SITE_ADDTHIS_ACCT)) {
		echo "
<!-- AddThis Button BEGIN -->
<div class=\"addthis_toolbox addthis_default_style addthis_32x32_style\">
<a class=\"addthis_button_preferred_1\"></a>
<a class=\"addthis_button_preferred_2\"></a>
<a class=\"addthis_button_preferred_3\"></a>
<a class=\"addthis_button_preferred_4\"></a>
<a class=\"addthis_button_compact\"></a>
<a class=\"addthis_counter addthis_bubble_style\"></a>
</div>
<script type=\"text/javascript\">
var addthis_config = {\"data_track_addressbar\":true};
var addthis_share = { \"url\":'.$u.', title:'.$t.'};
</script>
<script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid='.$RFS_SITE_ADDTHIS_ACCT\"></script>

<!-- AddThis Button END -->
";		
		
	}
}
function lib_social_share_bar1($u,$t,$i) {
	eval(lib_rfs_get_globals());
	if(lib_rfs_bool_true($RFS_SITE_NO_SHARING)) return;		
	if(!empty($RFS_SITE_ADDTHIS_ACCT)) {
echo '
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<table border=0><tr><td>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
</td><td>
<a class="addthis_button_tweet"></a>
</td><td>
<a class="addthis_button_reddit"></a>
</td><td>
<a class="addthis_button_stumbleupon"></a>
</td><td>
<a class="addthis_button_delicious"></a>
</td><td>
<a class="addthis_button_pinterest_pinit"></a>
</td><td>
<a class="addthis_counter addthis_pill_style"></a>
</td></tr></table>
</div>
<script type="text/javascript">
var addthis_config = {
	data_track_addressbar:false
};
var addthis_share = {
	url:'.$u.',
	title:'.$t.'
	
};
</script>
<script type="text/javascript" 
src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$RFS_SITE_ADDTHIS_ACCT.'"></script>
<!-- AddThis Button END --> ';
	}
}
function lib_social_share_bar2($u,$i,$t) {
	//$u=urlencode($u);
	echo "<table border=0 cellpadding=4 cellspacing=0> <tr>";
	// echo "<div style='float:left;'> ";	
	echo "<td width=80>";	
	lib_pin_it_button($u,$i,$t);
	echo "</td>";
	echo "<td width=50>";	
	// echo "</div> ";
	// echo "<div style='float:left;'> ";	
	lib_social_stumble_upon_badge($u);
	echo "</td>";
	echo "<td width=30>";	
	//echo "</div> ";
	//echo "<div style='float:left;'> ";	
	lib_social_google_plus($u);
	echo "</td>";
	echo "<td width=60>";	
	//echo "</div> ";
	//echo "<div style='float:left;'> ";
	lib_social_reddit($u,$t);
	
	echo "</td>";
	echo "<td width=30>";	
	// cho "</div> ";
	// echo "<div style='float:left;'> ";
	
	
	echo "</td>";
	echo "<td width=20>";	
	lib_social_tweet($u,"",$t);
	
	echo "</td>";
	
	echo "<td width=150>";	
	//echo "</div> ";	
	//echo "<div style='float:left;'> ";
	lib_social_facebook_like($u);
	echo "</td>";

	echo "</tr>";
	// echo "</div>";
	echo "</table>";
}
function lib_social_buttons(){
	echo "<table border=0><tr><td>";
	lib_social_facebook_like_little($RFS_SITE_URL);
	echo "</td><td>";
    lib_social_stumble_upon_badge($RFS_SITE_URL);
	echo "</td><td>";
	lib_social_google_plus($RFS_SITE_URL);
	echo "</td></tr></table>";
}
function lib_social_google_adsense($x){
	if(stristr(lib_domain_phpself(),"adm.php")) return;
	global $RFS_SITE_GOOGLE_ADSENSE;
    global $RFS_SITE_GOOGLE_ADSENSE_AD;
	if(!empty($RFS_SITE_GOOGLE_ADSENSE)) {
		lib_div("GOOGLE ADSENSE");
        
        echo "
        
<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
<!-- hey -->
<ins class=\"adsbygoogle\"
     style=\"display:inline-block;width:728px;height:90px\"
     data-ad-client=\"$RFS_SITE_GOOGLE_ADSENSE\"
     data-ad-slot=\"$RFS_SITE_GOOGLE_ADSENSE_AD\"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script> ";

/*
		echo "<script type=\"text/javascript\"><!--
				google_ad_client = \"\";
				google_ad_slot = \"9276856171\";
				google_ad_width = 728;
				google_ad_height = 90;
				//-->
				</script>
				<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>";
*/
		
	}
}
function lib_social_google_adsense_2(){
	global $RFS_SITE_GOOGLE_ADSENSE;
    global $RFS_SITE_GOOGLE_ADSENSE_AD;
	if(!empty($RFS_SITE_GOOGLE_ADSENSE)) {
		echo ' <script type="text/javascript">
				<!--	
				google_ad_client = "'.$RFS_SITE_GOOGLE_ADSENSE.'";
				google_ad_slot = "'.$RFS_SITE_GOOGLE_ADSENSE_AD.'";
				google_ad_width = 160;
				google_ad_height = 90; //-->
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
	}
}
function lib_social_google_analytics(){
	lib_div("GOOGLE ANALYTICS");
    global $RFS_SITE_GOOGLE_ANALYTICS;
	if(!empty($RFS_SITE_GOOGLE_ANALYTICS)) {
			echo "<script type=\"text/javascript\">
				var _gaq = _gaq || [];  
				_gaq.push(['_setAccount', '$RFS_SITE_GOOGLE_ANALYTICS']);
				_gaq.push(['_trackPageview']);
				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>";
	}
}

function lib_pin_it_button($u,$i,$t) {
$u=urlencode($u);
$t=urlencode($t);
if(empty($i)) $i="null";
echo "
<a href=\"//www.pinterest.com/pin/create/button/?
url=$u&
media=$i&
description=$t\" 
data-pin-do=\"buttonPin\" 
data-pin-config=\"beside\"
>
<img src=\"//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png\" /></a>
";
/*

<a class=\"pin-it-button\" 
href=\"http://www.pinterest.com/pin/create/button/?url=$url&description=$desc\"
data-pin-do=\"buttonBookmark\"
data-pin-color=\"white\" >
<img src=\"http://assets.pinterest.com/images/pidgets/pinit_fg_en_rect_white_20.png\" /></a>";

/******************************************	
Pinterest Variables:
Supported fields:
url	String, canonical URL for the page (for example "http://www.etsy.com/listing/83934917/chocolate-raspberry-drizzle-body-lotion")	Y
title	String, product name. May be truncated, all formatting and HTML tags will be removed.	Y
price	Number (float), product price (without currency sign, for example "6.50").	Y
currency_code	String, currency code as defined in http://www.xe.com/iso4217.php (for example "USD").	Y
provider_name	String, store name (for example "Etsy").	N
description	String, product description. May be truncated, all line breaks and HTML tags will be removed.	N
brand	String, brand name (for example "Lucky Brand").	N
product_id	String, ID that uniquely identifies the product within your site.	N
availability	Case-insensitive string, possible values: "in stock", "preorder", "backorder" (will be back in stock soon), “out of stock” (may be back in stock some time), “discontinued.” Discontinued items won’t be part of a daily scrape and marking them will decrease the load on your servers.	N
quantity	Number (int). Positive value is interpreted as "in stock", "out of stock" otherwise.	N
standard_price	Number (float). Product's original price if it's on sale (without currency sign, for example "10.00").	N
sale_start_date	If the product is on sale, the start date in ISO 8601 date format.	N
sale_end_date	If the product is on sale, the end date in ISO 8601 date format.	N
product_expiration	Product expiration date in ISO 8601 date format.	N
gender	Gender property of this product can only be 'male', 'female' and 'unisex'.	N
geographic_availability	The list of product available geographic areas in ISO 3166 Country Code format. Can be 'All' if applies for all countries.	N
color	List of color specs (in JSON format) if the product has different colors. you can specify the color's name, detail image, and standard color map name(must be one of 'beige', 'black', 'blue', 'bronze', 'brown', 'gold', 'green', 'gray', 'metallic', 'multicolored', 'off-white', 'orange', 'pink', 'purple', 'red', 'silver', 'transparent', 'turquoise', 'white', or 'yellow'). Please see sample oEmbed response below for full format.	N
images	List of URLs of high resolution images for this product. Up to 6 images can be provided.	N
related_items	List of URLs (must be the same domain) representing the related productsi.	N
referenced_items	List of URLs representing the referenced other products.	N
rating	Number (float), rating of this product (for example, 4.6).	N
rating_scale	Number (int), maximum value of the ratings scale. Required if rating provided (e.g. 5).	N
rating_count	Number (int), rating count of which the product is rated(e.g. 113).	N
Supported fields:
Field	Description	Required?
url	String, canonical URL for the page (for example "http://www.etsy.com/listing/83934917/chocolate-raspberry-drizzle-body-lotion")	Y
products	JSON list of dictionaries with product fields as defined below.	Y
provider_name	String, store name (for example "Etsy")	N
Product fields:
Field	Description	Required?
title	String, product name. May be truncated, all formatting and HTML tags will be removed.	Y
offers	JSON list of dictionaries with offer fields as defined below.	Y
description	String, product description. May be truncated, all line breaks and HTML tags will be removed.	N
brand	String, brand name (for example "Lucky Brand").	N
product_id	String, ID that uniquely identifies the product within your site.	N
images	List of URLs of high resolution images for this product. Up to 6 images can be provided.	N
gender	Gender property of this product can only be 'male', 'female' and 'unisex'.	N
product_expiration	Product expiration date in ISO 8601 date format.	N
color	List of color specs (in JSON format) if the product has different colors. you can specify the color's name, detail image, and standard color map name(must be one of 'beige', 'black', 'blue', 'bronze', 'brown', 'gold', 'green', 'gray', 'metallic', 'multicolored', 'off-white', 'orange', 'pink', 'purple', 'red', 'silver', 'transparent', 'turquoise', 'white', or 'yellow'). Please see below sample oEmbed response for full format.	N
related_items	List of URLs (must be the same domain) representing the related products.	N
referenced_items	List of URLs representing the referenced other products.	N
rating	Number (float), rating of this product (for example, 4.6).	N
rating_scale	Number (int), maximum value of the ratings scale. Required if rating provided (e.g. 5).	N
rating_count	Number (int), rating count of which the product is rated(e.g. 113).	N
Offer fields:
Field	Description	Required?
price	Number (float), product price (without currency sign, for example "6.50").	Y
currency_code	String, currency code as defined in http://www.xe.com/iso4217.php (for example "USD").	Y
title	String, product name. May be truncated, all formatting and HTML tags will be removed.	N
description	String, product description. May be truncated, all line breaks and HTML tags will be removed.	N
offer_id	String, ID that uniquely identifies the offer within your site.	N
availability	Case-insensitive string, possible values: "in stock", "preorder", "backorder" (will be back in stock soon), “out of stock” (may be back in stock some time), “discontinued.” Discontinued items won’t be part of a daily scrape and marking them will decrease the load on your servers.	N
quantity	Number (int). Positive value is interpreted as "in stock", "out of stock" otherwise.	N
standard_price	Number (float). Product's original price if it's on sale (without currency sign, for example "10.00").	N
sale_start_date	If the product is on sale, the start date in ISO 8601 date format.	N
sale_end_date	If the product is on sale, the end date in ISO 8601 date format.	N
geographic_availability	The list of product available geographic areas in ISO 3166 Country Code format. Can be 'All' if applies for all countries.	N
<a href=\"http://www.pinterest.com/pin/create/button/
        ?
        data-pin-do=\"buttonPin\"
        data-pin-config=\"beside\"
		
		>
        <img src=\"//assets.pinterest.com/images/pidgets/pin_it_button.png\" />
    </a>


<a href=\"//www.pinterest.com/pin/create/button/?url=$url&data-pin-do='buttonBookmark'&data-pin-shape='round'\" >
<img src=\"//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_16.png\" />


*******************************************/
}

function lib_social_reddit($u,$t) { //	eval(lib_rfs_get_globals());
/******************************************************
customizing the look of your buttons the buttons with points have three additional options.
styled=off no styles will be added, so you can style it yourself
url=[URL] specify a url to use instead of the current url
newwindow=1 opens links in a new window
*******************************************************/
$u=urlencode($u);
$t=urlencode($t);

echo "
<a href=\"http://www.reddit.com/submit\"
onclick=\"window.location='http://www.reddit.com/submit?url=$u&title=$t'; return false;\"
>
<img src=\"http://www.reddit.com/static/spreddit7.gif\" alt=\"submit to reddit\" border=\"0\" /> </a>
";
/*
echo "<script type=\"text/javascript\">reddit_url='$u'</script>";
echo "<script type=\"text/javascript\">reddit_title='$t'</script>";
echo "<script type=\"text/javascript\">reddit_newwindow='1'</script>";
echo"<script type=\"text/javascript\" src=\"http://www.reddit.com/buttonlite.js\"></script>";
*/
}
function lib_social_google_plus_badge(){
echo '<!-- Place this code where you want the badge to render. -->
<a href="//plus.google.com/111679666163937087116?prsrc=3" rel="publisher" style="text-decoration:none;">
<img src="//ssl.gstatic.com/images/icons/gplus-16.png" alt="Google+" style="border:0;width:16px;height:16px;"/>
</a>';
}
function lib_social_google_plus_badge2(){
echo "	<!-- Place this code where you want the badge to render. -->
<a href=\"//plus.google.com/111679666163937087116?prsrc=3\" rel=\"publisher\" style=\"text-decoration:none;\">
<img src=\"//ssl.gstatic.com/images/icons/gplus-32.png\" alt=\"Google+\" style=\"border:0;width:32px;height:32px;\"/>
</a>";
}
function lib_social_google_plus($url){
echo "<div class=\"g-plusone\"
			data-size=\"medium\"
			data-href=\"$url\"></div>";
}
function lib_social_tweet($url,$hash,$text){
/*******************************************
TWITTER BUTTON VARIABLES:
url	URL of the page to share
via	Screen name of the user to attribute the Tweet to
text	Default Tweet text
related	Related accounts
count	Count box position
lang	The language for the Tweet Button
counturl	URL to which your shared URL resolves
hashtags	Comma separated hashtags appended to tweet text
size	The size of the rendered button
dnt	See this section for information
*********************************************/	
	$url=urlencode($url);
	$text=urlencode($text);
echo "<a href='https://twitter.com/share?text=$text&url=$url&hashtags=$hash'
class='twitter-share-button'
data-lang='en'";
if(!empty($RFS_SITE_TWITTER)) {
	echo " data-via='$RFS_SITE_TWITTER' ";
}
echo "> Tweet </a>";
}

function lib_social_facebook_likebox($url){ // <div id=\"fb-root\"></div>
echo "
<div class=\"fb-like-box\"
	data-href=\"$url\"
	data-width=\"292\"
	data-show-faces=\"true\"
	data-stream=\"true\"
	data-header=\"false\">
</div>";
}
function lib_social_facebook_like($url){ // $url=urldecode($url);
echo "
<div class=\"fb-like\"
data-href=\"$url\"
data-layout=\"standard\"
data-action=\"like\"
data-show-faces=\"false\"
data-share=\"true\"></div>
";
/*
<div class=\"fb-share-button\"
data-href=\"$url\"
data-type=\"button_count\"></div>

<div class=\"fb-like-box\"
data-href=\"$url\"
data-colorscheme=\"light\"
data-show-faces=\"true\"
data-header=\"true\"
data-stream=\"false\"
data-show-border=\"true\"></div>

echo "	<div id=\"fb-root\"></div>
<div 
class=\"fb-like\"
data-send=\"true\"
data-layout=\"button_count\"
data-width=\"200\"
data-show-faces=\"false\"
data-font=\"verdana\"
data-href=\"$url\">
</div>";
*/

}
function lib_social_facebook_like_little($url){    
	$url=rawurlencode($url);	
	echo " <div id=\"fb-root\"></div>
<div class=\"fb-like\"
	data-send=\"false\"
	data-layout=\"button_count\"
	data-width=\"450\"
	data-show-faces=\"true\"
	data-colorscheme=\"dark\">
</div>";
}
function lib_social_facebook_like2(){
	echo "<iframe src='https://www.facebook.com/plugins/like.php?href=".lib_domain_canonical_url()."'
			scrolling='no' frameborder='0' style='border:none; height:25px '>";
	echo "</iframe> ";
}
function lib_social_twitter_follow($username) {	
	echo "<a href=\"https://twitter.com/$username\"
				class=\"twitter-follow-button\"
				data-show-count=\"true\"
				data-show-screen-name=\"false\">
				</a>";
}
function lib_social_facebook_login() { echo lib_social_facebook_login_r(); }
function lib_social_facebook_login_r() {
	if(!empty($GLOBALS['RFS_SITE_FACEBOOK_APP_ID'])) {
		if(!empty($GLOBALS['RFS_SITE_FACEBOOK_SECRET'])) {
			$page=urlencode(lib_domain_canonical_url());
			$r="<a href=\"$RFS_SITE_URL/facebook/fb.login.php?goback=1";
			if(!empty($page)) 
				$r.="&retpage=$page";
			$r.="\">";
			
			$r.="<img src=\"$RFS_SITE_URL/facebook/facebook_login.gif\" border=\"0\" alt=\"Connect with facebook\" text=\"Connect with facebook\">";
			$r.="</a>\n";
		}
	}
	return $r;
}
function lib_social_facebook_comments($page) {
	$RFS_SITE_FACEBOOK_APP_ID=$GLOBALS['RFS_SITE_FACEBOOK_APP_ID'];
	if(!empty($RFS_SITE_FACEBOOK_APP_ID)) {
		echo "<div id=\"fb-root\"></div>";
		echo "	<div
				class=\"fb-comments\"
				data-url=\"$page\"
				data-title=\"$page\"
				data-href=\"$page\"
				data-width=\"600\"
				data-num-posts=\"10\"> 
			</div>";
	}
}
function lib_social_follow_vertical_small() {
/*		<!-- AddThis Follow BEGIN -->
<p>Follow Us</p>
<div class="addthis_toolbox addthis_vertical_style">
<a class="addthis_button_facebook_follow" addthis:userid="seth.parson"></a>
<a class="addthis_button_twitter_follow" addthis:userid="YOUR-USERNAME"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test" addthis:usertype="company"></a>
<a class="addthis_button_google_follow" addthis:userid="test"></a>
<a class="addthis_button_youtube_follow" addthis:userid="test"></a>
<a class="addthis_button_flickr_follow" addthis:userid="test"></a>
<a class="addthis_button_vimeo_follow" addthis:userid="test"></a>
<a class="addthis_button_pinterest_follow" addthis:userid="test"></a>
<a class="addthis_button_instagram_follow" addthis:userid="test"></a>
<a class="addthis_button_foursquare_follow" addthis:userid="test"></a>
<a class="addthis_button_tumblr_follow" addthis:userid="test"></a>
<a class="addthis_button_rss_follow" addthis:userid="test"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b82f7c63926ef6"></script>
<!-- AddThis Follow END -->
*/
	
}
function lib_social_follow_vertical() {
	/*
<!-- AddThis Follow BEGIN -->
<p>Follow Us</p>
<div class="addthis_toolbox addthis_32x32_style addthis_vertical_style">
<a class="addthis_button_facebook_follow" addthis:userid="seth.parson"></a>
<a class="addthis_button_twitter_follow" addthis:userid="YOUR-USERNAME"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test" addthis:usertype="company"></a>
<a class="addthis_button_google_follow" addthis:userid="test"></a>
<a class="addthis_button_youtube_follow" addthis:userid="test"></a>
<a class="addthis_button_flickr_follow" addthis:userid="test"></a>
<a class="addthis_button_vimeo_follow" addthis:userid="test"></a>
<a class="addthis_button_pinterest_follow" addthis:userid="test"></a>
<a class="addthis_button_instagram_follow" addthis:userid="test"></a>
<a class="addthis_button_foursquare_follow" addthis:userid="test"></a>
<a class="addthis_button_tumblr_follow" addthis:userid="test"></a>
<a class="addthis_button_rss_follow" addthis:userid="test"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b82f7c63926ef6"></script>
<!-- AddThis Follow END -->

	*/
}
function lib_social_follow_horizontal_small() {
	/*
	<!-- AddThis Follow BEGIN -->
<p>Follow Us</p>
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_follow" addthis:userid="seth.parson"></a>
<a class="addthis_button_twitter_follow" addthis:userid="YOUR-USERNAME"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test" addthis:usertype="company"></a>
<a class="addthis_button_google_follow" addthis:userid="test"></a>
<a class="addthis_button_youtube_follow" addthis:userid="test"></a>
<a class="addthis_button_flickr_follow" addthis:userid="test"></a>
<a class="addthis_button_vimeo_follow" addthis:userid="test"></a>
<a class="addthis_button_pinterest_follow" addthis:userid="test"></a>
<a class="addthis_button_instagram_follow" addthis:userid="test"></a>
<a class="addthis_button_foursquare_follow" addthis:userid="test"></a>
<a class="addthis_button_tumblr_follow" addthis:userid="test"></a>
<a class="addthis_button_rss_follow" addthis:userid="test"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b82f7c63926ef6"></script>
<!-- AddThis Follow END -->

	*/
}
function lib_social_follow_horizontal() {
	
	/*
	<!-- AddThis Follow BEGIN -->
<p>Follow Us</p>
<div class="addthis_toolbox addthis_32x32_style addthis_default_style">
<a class="addthis_button_facebook_follow" addthis:userid="seth.parson"></a>
<a class="addthis_button_twitter_follow" addthis:userid="YOUR-USERNAME"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test"></a>
<a class="addthis_button_linkedin_follow" addthis:userid="test" addthis:usertype="company"></a>
<a class="addthis_button_google_follow" addthis:userid="test"></a>
<a class="addthis_button_youtube_follow" addthis:userid="test"></a>
<a class="addthis_button_flickr_follow" addthis:userid="test"></a>
<a class="addthis_button_vimeo_follow" addthis:userid="test"></a>
<a class="addthis_button_pinterest_follow" addthis:userid="test"></a>
<a class="addthis_button_instagram_follow" addthis:userid="test"></a>
<a class="addthis_button_foursquare_follow" addthis:userid="test"></a>
<a class="addthis_button_tumblr_follow" addthis:userid="test"></a>
<a class="addthis_button_rss_follow" addthis:userid="test"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b82f7c63926ef6"></script>
<!-- AddThis Follow END -->

	*/
}


