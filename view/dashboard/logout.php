<?php
session_start();
include "../../model/database.php";
session_unset();
session_destroy();    // Destroy the session
header("Location: login.php");
exit;
?>
