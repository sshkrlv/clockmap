<?php
namespace Main;
mb_internal_encoding("UTF-8");
require("db.php");
include "getClocks.php";
ClockController::actionIndex();
?>
