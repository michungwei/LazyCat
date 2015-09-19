<?php
session_start();
//session_destroy();
unset($_SESSION['madmin']);
unset($_SESSION['userid']);

echo "<script>window.open('login.html','_parent');</script>";
/*END PHP*/

