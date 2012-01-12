<?php
session_start();
include('../../geoInc/db.php');
include('class.user.php');

/* Check login */
if( !isset($_SESSION['USER_ID']) || !is_numeric($_SESSION['USER_ID']) ){ ?>
<conf>
	<result>
		<id>0</id>
		<message>KO</message>
	</result>
</conf>
<?php die(); }

$user = new user($_SESSION['USER_ID']);

$method = ( isset($_GET['debug']) && $_GET['debug']=='on' ) ? $_GET : $_POST;

$source = ( !isset($method['source']) || $method['source']=='' ) ? '' : $method['source'];

echo $user->save($source);
?>