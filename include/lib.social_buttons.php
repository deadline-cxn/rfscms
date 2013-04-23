<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function sc_stumble_upon($url) {
echo " <!-- Place this tag where you want the su badge to render -->
		<su:badge layout='2'></su:badge>
		<!-- Place this snippet wherever appropriate -->
		<script type='text/javascript'>
		(function() {
		var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
		li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
		})();
		</script>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_donate_button_small() {
	sc_donate_button();
/*	echo ' <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="CPGYAFC78SMUY">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>' ; */
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_donate_button() { eval(scg());
if(empty($RFS_SITE_PAYPAL_BUTTON1)) return;
	echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=';
	echo $RFS_SITE_PAYPAL_BUTTON1;
	echo '" target=_blank> ';
	$vr=$RFS_SITE_URL;
	echo "<img src='$vr/images/icons/Paypal.png' border='0' height='32' alt='$RFS_SITE_PAYPAL_BUTTON1_MSG'></a>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_donate_button2() {
	echo " <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
			<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
			<input type=\"hidden\" name=\"hosted_button_id\" value=\"$RFS_SITE_PAYPAL_BUTTON2\">
			<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif\" border=\"0\" name=\"submit\" alt=\"$RFS_SITE_PAYPAL_BUTTON2_MSG\">
			<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">
			</form>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_socials_content($u,$t) { eval(scg());
	if(!sc_yes($RFS_SITE_SHOW_SOCIALS)) return;
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
		<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
		<script type="text/javascript" 
		src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$RFS_SITE_ADDTHIS_ACCT.'"></script>
		<!-- AddThis Button END --> ';
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_socials_content2($u,$t) {
	echo "<table border=0 cellspacing=0 width=100%><tr><td width=10%>";
	echo "</td><td>";
	sc_tweet($u,"",$t);
	echo "</td><td>";
	sc_facebook_like($u);
	echo "</td><td>";
	sc_google_plus($u);
	echo "</td><td>";
	sc_stumble_upon($u);
	echo "</td><td width=70%>";
	echo "</td></tr></table>";	
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_social_buttons(){
	echo "<table border=0><tr><td>";
	sc_facebook_like_little($RFS_SITE_URL);
	echo "</td><td>";
    sc_stumble_upon($RFS_SITE_URL);
	echo "</td><td>";
	sc_google_plus($RFS_SITE_URL);
	echo "</td></tr></table>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_adsense_2(){
    global $RFS_SITE_GOOGLE_ADSENSE;
	if(!empty($RFS_SITE_GOOGLE_ADSENSE)) {
		echo ' <script type="text/javascript">
				<!--	
				google_ad_client = "'.$RFS_SITE_GOOGLE_ADSENSE.'";
				google_ad_slot = "2325338978";
				google_ad_width = 160;
				google_ad_height = 90; //-->
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_adsense($x){
	global $RFS_SITE_GOOGLE_ADSENSE;
	if(!empty($RFS_SITE_GOOGLE_ADSENSE)) {		
		sc_div("GOOGLE ADSENSE");
		echo "<script type=\"text/javascript\"><!--
				google_ad_client = \"$RFS_SITE_GOOGLE_ADSENSE\";
				google_ad_slot = \"9276856171\";
				google_ad_width = 728;
				google_ad_height = 90;
				//-->
				</script>
				<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>";
		
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_analytics(){
	sc_div("GOOGLE ANALYTICS");
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
/////////////////////////////////////////////////////////////////////////////////////////
function sc_reddit() { eval(scg());
echo '<script type="text/javascript" src="http://www.reddit.com/buttonlite.js?i=1"></script>';
    /*
echo "<script>";
echo "(function() {";
echo "var styled_submit = \"<a style='color: #369; text-decoration: none;' href='http://www.reddit.com/submit?title=' target='_parent'>\"; ";
echo "var unstyled_submit = \"<a href='http://www.reddit.com/submit?title=' target='http://www.reddit.com/submit?title='> \";";
echo "var write_string=\"<span class='reddit_button' style='color: grey;' >\";";
echo "write_string += unstyled_submit + \"<img style='height: 2.3ex; vertical-align:top; margin-right: 1ex' src='http://www.redditstatic.com/spreddit1.gif'></a>\";";
echo " write_string += styled_submit + 'submit';
    write_string += '</a>';
    write_string += '</span>';
document.write(write_string);
})()
</script>';
";    $RFS_SITE_ADDTHIS_ACCT		= "ra-50b82f7c63926ef6";
*/
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_plus_badge(){
	echo '<!-- Place this code where you want the badge to render. -->
			<a href="//plus.google.com/111679666163937087116?prsrc=3" rel="publisher" style="text-decoration:none;">
			<img src="//ssl.gstatic.com/images/icons/gplus-16.png" alt="Google+" style="border:0;width:16px;height:16px;"/>
			</a>';
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_plus_badge2(){
	echo "	<!-- Place this code where you want the badge to render. -->
			<a href=\"//plus.google.com/111679666163937087116?prsrc=3\" rel=\"publisher\" style=\"text-decoration:none;\">
			<img src=\"//ssl.gstatic.com/images/icons/gplus-32.png\" alt=\"Google+\" style=\"border:0;width:32px;height:32px;\"/>
			</a>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_google_plus($url){
echo " <!-- Place this tag where you want the +1 button to render. -->
		<div class=\"g-plusone\" data-size=\"medium\" href=\"$url\"></div>
		<!-- Place this tag after the last +1 button tag. -->
		<script type=\"text/javascript\">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script> ";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_tweet($url,$hash,$text){	
	$url=urlencode($url);
	echo "<a href='https://twitter.com/share?text=$text&url=$url'
			class='twitter-share-button'			
			data-via='sethcoder'
			data-lang='en'> Tweet </a>";
	echo "<script>
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
				if(!d.getElementById(id)){
					js=d.createElement(s);
					js.id=id;
					js.src='https://platform.twitter.com/widgets.js';
					fjs.parentNode.insertBefore(js,fjs);
					}
				}
				(document,'script','twitter-wjs');
				</script>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_likebox($url){
echo "	<div id=\"fb-root\"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = \"//connect.facebook.net/en_GB/all.js#xfbml=1\";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class=\"fb-like-box\"
			data-href=\"$url\"
			data-width=\"292\"
			data-show-faces=\"true\"
			data-stream=\"true\"
			data-header=\"false\">
		</div>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_like($url){
	$url=rawurlencode($url);
	echo "	<div id=\"fb-root\"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = \"//connect.facebook.net/en_US/all.js#xfbml=1\";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			
			<div class=\"fb-like\"
				data-send=\"true\"
				data-layout=\"button_count\"
				data-width=\"200\"
				data-show-faces=\"false\"
				data-font=\"verdana\"
				data-href=\"$url\">
			</div>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_like_little($url){    
	$url=rawurlencode($url);	
	echo " <div id=\"fb-root\"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = \"//connect.facebook.net/en_GB/all.js#xfbml=1&appId=357980654298886\";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			
			<div class=\"fb-like\"
				data-send=\"false\"
				data-layout=\"button_count\"
				data-width=\"450\"
				data-show-faces=\"true\"
				data-colorscheme=\"dark\">
			</div>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_like2(){
	echo "<iframe src='https://www.facebook.com/plugins/like.php?href=".sc_canonical_url()."'
			scrolling='no' frameborder='0' style='border:none; height:25px '>";
	echo "</iframe> ";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_twitter_follow() {	
	echo "<a href=\"https://twitter.com/sethcoder\"
				class=\"twitter-follow-button\"
				data-show-count=\"true\"
				data-show-screen-name=\"false\">
				</a>
			<script>!function(d,s,id){
				var js,fjs=d.getElementsByTagName(s)[0];
				if(!d.getElementById(id)){
					js=d.createElement(s);
					js.id=id;
					js.src=\"//platform.twitter.com/widgets.js\";
					fjs.parentNode.insertBefore(js,fjs);
					}
				}
			(document,\"script\",\"twitter-wjs\");
			</script>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_login() {
	if(!empty($GLOBALS['RFS_SITE_FACEBOOK_APP_ID'])) {
		if(!empty($GLOBALS['RFS_SITE_FACEBOOK_SECRET'])) {
			$page=urlencode(sc_canonical_url());
			echo "<a href=\"$RFS_SITE_URL/facebook/fb.login.php?goback=1";
			if(!empty($page)) 
				echo "&retpage=$page";
			echo "\">";
			
			echo "<img src=\"$RFS_SITE_URL/facebook/facebook_login.gif\" border=\"0\" 
					alt=\"Connect with facebook\" text=\"Connect with facebook\">";
			echo "</a>\n";
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_facebook_comments($page) {
	$RFS_SITE_FACEBOOK_APP_ID=$GLOBALS['RFS_SITE_FACEBOOK_APP_ID'];
	if(!empty($RFS_SITE_FACEBOOK_APP_ID)) {
		echo "<div id=\"fb-root\"></div>
				<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
						js.src = \"//connect.facebook.net/en_GB/all.js#xfbml=1&appId=$RFS_SITE_FACEBOOK_APP_ID\";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>";

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
/////////////////////////////////////////////////////////////////////////////////////////
function sc_follow_vertical_small() {
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
function sc_follow_vertical() {
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

function sc_follow_horizontal_small() {
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
function sc_follow_horizontal() {
	
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
?>