<?php
namespace Main;

class ClockController{

    static function getClosest($lat, $long){
        require("db.php");
        if (isset($dbh)) {
            $sql = "SELECT Location, ST_AsText(geodata) as Coord, clock_types.Name as type,
            ST_Distance_Sphere(ST_GeomFromText('POINT(".$lat." ".$long.")'), geodata) AS dist
            FROM clock_registry
            INNER JOIN clock_types ON clock_registry.`type_ID` = clock_types.ID
            ORDER BY dist
            LIMIT 3";
            $clocks = array();

            foreach ($dbh->query($sql) as $row) {
                $clock = new Clock();
                $clock->coord = $row['Coord'];
                $clock->seDist($row['dist']);
                $clock->location = $row['Location'];
                $clock->type = $row['type'];

                $clocks[] = $clock;
            }
            echo(json_encode($clocks));

            return($clocks);
        }
        return null;
    }

    static function actionIndex(){
        if(isset($_GET['lat'])){
            $clocks = ClockController::getClosest($_GET['lat'], $_GET['long']);
        }
        $clocks = "";
        ClockView::render($clocks);
    }
}

    if(isset($_GET)){
        ClockController::actionIndex();
        //ClockController::getClosest($_GET['lat'], $_GET['long']);
    }

class ListItem{
        static function show($heading, $placeholder, $hint){
            echo '
            <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <img src="https://github.com/twbs.png" alt="twbs" width="32" height="32" class="rounded-circle flex-shrink-0">
                <div class="d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">'.$heading.'</h6>
                        <p class="mb-0 opacity-75">'.$placeholder.'</p>
                    </div>
                    <small class="opacity-50 text-nowrap">'.$hint.'</small>
                </div>
            </a>';
        }
}

/* @var $model Clock[] */
    class ClockView
    {
        static function render($model)
        {
            echo '<!doctype html>
<html lang="ru">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="static/js/main.js"></script>
    <style>
        .list-group {
            width: auto;
            max-width: 460px;
            margin: 4rem auto;
        }
    </style>
    <title>Часы</title>
  </head>
  <body>
     <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>;
            
            <button onclick="navigator.geolocation.getCurrentPosition(sendlocation)">ближайшие</button>';
            echo '<div class="list-group">';

            foreach ($model as $item) {
                ListItem::show($item->location, $item->type, $item->friendlyDist);
            }

            echo '</div>  </body> </html>';

        }
    }


class Clock{
    public $coord;
    public $location;
    public $dist;
    public $type;
    public $friendlyDist;
    public function seDist(float $dist): void
    {
        $this->dist = $dist;
        $this->friendlyDist = ($dist > 1000) ? round($dist/1000, 2)." км" : round($dist, 2)." м";
    }
}
