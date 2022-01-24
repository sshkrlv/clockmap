<?php

$dsn = 'mysql:dbname=admin_clockmap;host=krlv.ml;charset=utf8mb4';
$user = 'admin_clockuser';
$password = 'u2avOfgcGf';

try{ $dbh = new PDO($dsn, $user, $password);}
catch (Exception $e) {echo "ошибка соединения с БД"; exit();}

?>