<?php
/**
 * Bugstomper - a rudimentary bug tracker just for fun
 * @author PrgmrBill <bill@prgmrbill.com>
 *
 */
ob_start();
session_start();
error_reporting(-1);
ini_set('display_errors', 1);

require '../Autoloader.php';
require '../application/config.php';
require '../application/Routes.php';

