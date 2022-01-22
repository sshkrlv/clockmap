<?php
namespace Main;
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
class ClockController{

    static function getClosest($lat, $long, $count){
        require("db.php");
        if (isset($dbh)) {
            $sql = "SELECT Location, ST_AsText(geodata) as Coord, clock_types.Name as type,
            ST_Distance_Sphere(ST_GeomFromText('POINT(".$lat." ".$long.")'), geodata) AS dist
            FROM clock_registry
            INNER JOIN clock_types ON clock_registry.`type_ID` = clock_types.ID
            ORDER BY dist
            LIMIT ".$count;
            $clocks = array();
            foreach ($dbh->query($sql) as $row) {
                $clocks[] = Clock::fromPDORow($row);
            }
            //echo(json_encode($clocks));

            return($clocks);
        }
        return null;
    }

    static function actionIndex(){
        if(isset($_GET['lat']) && isset($_GET['long'])){
            $clocks = ClockController::getClosest($_GET['lat'], $_GET['long'], (!isset($_GET['count'])) ? 3 : $_GET['count']);
            ClockView::render($clocks);
        }else{
            ClockView::render([]);
        }
    }
}

    if(isset($_GET)){
        ClockController::actionIndex();
        //ClockController::getClosest($_GET['lat'], $_GET['long']);
    }

class ListItem{
        static function render($heading, $placeholder, $hint){
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
            include "static/html/header.html";
            include "views/ClocksIndex.php";

            echo ' </body> </html>';

        }
    }


class Clock{
    public $coords;
    public string $address;
    public float $dist;
    public $type;
    public $friendlyDist;
    public function setDist(float $dist): void
    {
        $this->dist = $dist;
        $this->friendlyDist = ($dist > 1000) ? round($dist/1000, 2)." км" : round($dist, 2)." м";
    }
    public function __construct($address, $coords, $dist, $type){
        $this->address = $address;
        $this->coords = $coords;
        $this->type = $type;
        $this->setDist($dist);
    }
    public static function fromPDORow($row) : static {
        return new static($row['Location'], $row['Coord'], $row['dist'], $row['type']);
    }
}
