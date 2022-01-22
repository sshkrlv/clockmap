<?php
mb_internal_encoding("UTF-8");
require("db.php");
if (isset($dbh)){
    if(isset($_GET)){
        $sql = "SELECT Location, ST_AsText(geodata) as Coord,
            ST_Distance_Sphere(ST_GeomFromText('POINT(".$_GET['lat']." ".$_GET['long'].")'), geodata) AS dist
            FROM clock_registry
            ORDER BY dist
            LIMIT 3";
        //var_dump($sql);
        $clocks = array();

        foreach ($dbh->query($sql) as $row) {
            $clock = new Clock();
            $clock->coord = $row['Coord'];
            $clock->dist = $row['dist'];
            $clock->location = $row['Location'];

            $clocks[] = $clock;
        }
        //var_dump($clocks);
        echo(json_encode($clocks));
        return(json_encode($clocks));

    }
}

class Clock{
    public $coord;
    public $location;
    public $dist;
}
