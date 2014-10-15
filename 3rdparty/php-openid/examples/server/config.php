<?php
/**
 * Set any extra include paths needed to use the library
 */
set_include_path(get_include_path() . PATH_SEPARATOR . "/var/www/3rdparty/php-openid/");

/**
 * The URL for the server.
 *
 * This is the location of server.php. For example:
 *
 * $server_url = 'http://example.com/~user/server.php';
 *
 * This must be a full URL.
 */
$server_url = "http://area56.sethcoder.com/3rdparty/php-openid/examples/server/server.php";

/**
 * Initialize an OpenID store
 *
 * @return object $store an instance of OpenID store (see the
 * documentation for how to create one)
 */
function getOpenIDStore()
{
    require_once 'Auth/OpenID/MySQLStore.php';
    require_once 'DB.php';

    $dsn = array(
                 'phptype'  => 'mysql',
                 'username' => 'root',
                 'password' => '!QAZ2wsx',
                 'hostspec' => 'localhost'
                 );

    $db = DB::connect($dsn);

    if (PEAR::isError($db)) {
        return null;
    }

    $db->query("USE area56");
        
    $s = new Auth_OpenID_MySQLStore($db);

    $s->createTables();

    return $s;
}

?>
