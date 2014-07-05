<?php
ini_set ( "display_errors", 1 );
error_reporting ( E_ALL & ~ E_NOTICE );
date_default_timezone_set ( 'Asia/Ho_Chi_Minh' );

$db ['host'] = '192.168.0.12';
$db ['db'] = 'hoaqua';
$db ['user'] = 'toan';
$db ['pass'] = 'FbC82W';
define ( 'DOMAIN', 'http://' . $_SERVER ['SERVER_NAME'] . '/' );
define ( 'LOCALIZER', 'en' ); //en|vi (en)
define ( 'TRANSLATION_LOG', true );
define ( 'TRANSLATION_CACHE', true );
define ( 'CACHER', 'FILE' ); //FILE|MEMCACHE (FILE)
define ( 'MEMCACHE_SERVER', '192.168.0.12' );
define ( 'ROUTES_CACHE', true );
define ( 'ROUTES_EXCLUDE_ADMIN', true ); //Exclude /admin directory