/* 
// TITLE MANIPULATION...
//////////////////////////////////////////////////////////////////////////////
// Figure out what to put in the title...
// If you're not sure what to put, just leave it alone
// Depends on module
// @include_once("../lib.news.php"); @include_once("lib.news.php"); @include_once("../lib.rfs.php"); @include_once("lib.rfs.php");
 $title=$_GLOBALS['RFS_SITE_NAME'];
	if($_SERVER['PHP_SELF']==$_GLOBALS['site_url'].'/index.php')
            $title=sc_get_news_headline(sc_get_top_news_id());
        if(!empty($description)) $title=$description;
        if(!empty($name)) $title=$name;
        if(!empty($sname)) $title=$sname;
            if(stristr( $_SERVER['PHP_SELF'],"pics.php")) {
                $title="Pictures";
                if(!empty($id)) {
                $p=mfo1("select * from pictures where id='$id'");
                if(!empty($p->sname)){
                    $title=$p->sname;
                }
                else {
                    $c=mfo1("select * from categories where id='$p->category'");
                    if(!empty($c->name)) {
                        $title=$c->name;
                    }
                }
            }
        }
        if(!empty($_GET['nid']))
            $title=sc_get_news_headline($_GET['nid']);
        if(!empty($what)) $title=$what;
*/
