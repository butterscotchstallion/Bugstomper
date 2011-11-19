<?php
ob_start();
session_start();
error_reporting(-1);
ini_set('display_errors', 1);

require '../Autoloader.php';
require '../application/config.php';
require '../application/Routes.php';

