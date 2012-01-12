<?php
session_start();
include('../../geoInc/db.php');
include('class.user.php');
?>
<conf>
<userlogged><?= ( !isset($_SESSION['USER_ID']) || !is_numeric($_SESSION['USER_ID']) ) ? 'false' : 'true' ?></userlogged>
<userlogin><?= ( !isset($_SESSION['USER_LOGIN']) ) ? '' : $_SESSION['USER_LOGIN'] ?></userlogin>
</conf>