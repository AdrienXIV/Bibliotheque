<?php
$db = new PDO('mysql:host=localhost;dbname=bibliotheque; charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

require "session_expire.php";
    