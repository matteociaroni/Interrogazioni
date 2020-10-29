<?php
include_once "util.php";
session_start();
session_destroy();
header('Location: index.php');
?>