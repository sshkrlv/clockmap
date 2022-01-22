<?php
mb_internal_encoding("UTF-8");
require("db.php");

echo "<script type='text/javascript' src='static/js/main.js'></script>";

echo "<button onclick='navigator.geolocation.getCurrentPosition(sendlocation)'>ближайшие</button>";
