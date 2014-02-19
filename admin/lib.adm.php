<?
 
function sc_ajax_callback_query_list() { eval( scg()) ;
	if(array_pop(explode("/",getcwd()))=="admin") chdir("..");
	include_once("include/lib.all.php");
	if(!sc_access_check("admin","access")) exit();
	if( empty( $theme ) )               $theme=$RFS_SITE_DEFAULT_THEME;
	if( !empty( $data->theme ) )        $theme=$data->theme;
	if( sc_yes( $RFS_SITE_FORCE_THEME ) ) $theme=$RFS_SITE_FORCED_THEME;
	echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	
	adm_db_query( "SELECT name,email,donated FROM users" );
	adm_db_query( "SELECT * FROM users" );
	adm_db_query( "SHOW FULL COLUMNS FROM users" );
	
	sc_query( " CREATE TABLE db_queries2 like db_queries; " );
	sc_query( " INSERT db_queries2 SELECT * FROM db_queries GROUP BY query;" );
	sc_query( " RENAME TABLE `db_queries`  TO `db_goto_hell`; " );
	sc_query( " RENAME TABLE `db_queries2` TO `db_queries`; " );
	sc_query( " DROP TABLE db_goto_hell; " );

	sc_query(" CREATE TABLE IF NOT EXISTS `db_queries` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`query` text COLLATE utf8_unicode_ci NOT NULL,
				`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=350 ; ");

	$r=sc_query("select distinct query from `db_queries` order by `time`");
    if($r) {
        $n=mysql_num_rows($r);
        for( $i=0; $i<$n; $i++ ) {
            $dq=mysql_fetch_object( $r );
            adm_db_query( $dq->query );
        }
    }
	exit();
}

?>