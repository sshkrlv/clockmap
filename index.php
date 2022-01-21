<?php
mb_internal_encoding("UTF-8");
require("db.php");

echo "<script type='text/javascript' src='static/js/main.js'></script>";

echo "<button onclick='navigator.geolocation.getCurrentPosition(sendlocation)'>ближайшие</button>";

//var_dump($_GET);
//echo  isset($_GET['lat']);
//var_dump($dbh);
if(isset($_GET['lat'])){
    $sql = "SELECT Location, ST_AsText(geodata) as Coord,
            ST_Distance_Sphere(ST_GeomFromText('POINT(".$_GET['lat']." ".$_GET['long'].")'), geodata) AS dist
            FROM clock_registry
            ORDER BY dist
            LIMIT 3";
    //var_dump($sql);
    if (isset($dbh)) {
        foreach ($dbh->query($sql) as $row) {
            echo $row['Location'] . "\t";
            echo $row['geodata'] . "\t";
            echo $row['dist'] . "<br>";
        }
    }
}