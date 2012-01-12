<?php
session_start();
include('../../geoInc/db.php');
include('class.user.php');
?>
<conf>

<? if( !isset($_SESSION['USER_ID']) || !is_numeric($_SESSION['USER_ID']) ){ ?>
	<userlogged>false</userlogged>
	<userlogin></userlogin>
	<externalXmlConf>
		<file size="1">${default_feed}</file>
	</externalXmlConf>
<? }else{ ?>
	<userlogged>true</userlogged>
	<userlogin><?=$_SESSION['USER_LOGIN']?></userlogin>
	<externalXmlConf>
		<file size="1">${logged_feed}</file>
	</externalXmlConf>
<?php  }

?>
</conf>