<?php
session_start();
include "../../model/database.php";
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
