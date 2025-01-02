<?php

require_once '../model/db_connect.php';

require_once '../model/USER.php';


$c = new DATABASE();
$conn = $c->getConnection();
$logout = new User($conn);

$logout-> logout();
?>