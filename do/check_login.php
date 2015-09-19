<?php
include_once('_config.php');
$re["sResult"] = isLogin() ? true : false;
echo json_encode($re);
