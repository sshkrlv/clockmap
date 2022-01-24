<?php
namespace Main;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
class ClockController{

    static function getOne($id, $userLat = null, $userLong = null){
        require("db.php");
        if (isset($dbh)) {
            $sql = "SELECT clock_registry.global_id as clock_id, X(geodata) as X, Y(geodata) as Y, Location, ST_AsText(geodata) as Coord, clock_types.Name as type";

            if(isset($userLat) && isset($userLong)){
                $sql .= ",ST_Distance_Sphere(ST_GeomFromText('POINT(".$userLat." ".$userLong.")'), geodata) AS dist";
            }
            $sql .=" FROM clock_registry
            INNER JOIN clock_types ON clock_registry.`type_ID` = clock_types.ID
            WHERE clock_registry.global_id = ".$id;

            $result = $dbh->query($sql)->fetch();
            if(!$result)
                return null;
            else
                return Clock::fromPDORow($result);
        }
    }

    static function getClosest($lat, $long, $count, $page = 1){
        require("db.php");
        /* @var $dbh \PDO */
        if (isset($dbh)) {
            $totalResults = $dbh->query("SELECT count(*) FROM clock_registry")->fetchColumn();
            $totalPages = $totalResults/$count;

            $sql = "SELECT clock_registry.global_id as clock_id, X(geodata) as X, Y(geodata) as Y, Location, ST_AsText(geodata) as Coord, clock_types.Name as type,
            ST_Distance_Sphere(ST_GeomFromText('POINT(".$lat." ".$long.")'), geodata) AS dist
            FROM clock_registry
            INNER JOIN clock_types ON clock_registry.`type_ID` = clock_types.ID
            ORDER BY dist
            LIMIT ".($page-1)*$count.",".$count;
            $clocks = array();
            foreach ($dbh->query($sql) as $row) {
                $clocks[] = Clock::fromPDORow($row);
            }
            //echo(json_encode($clocks));

            return(['clocks'=> $clocks, 'totalPages' => $totalPages, 'page' => $page]);
        }
        return null;
    }

    static function actionIndex(){
        if(isset($_GET['lat']) && isset($_GET['long'])){
            $model = ClockController::getClosest($_GET['lat'], $_GET['long'], (!isset($_GET['count'])) ? 3 : $_GET['count'], (!isset($_GET['page'])) ? 1 : $_GET['page']);
            ClockView::render($model, "index");
        }else{
            ClockView::render(['clocks'=> []], "index");
        }
    }

    static function actionDetails(){
        if(!isset($_GET['id']))
            http_response_code(404);
        else{
            if(isset($_GET['lat']) && isset($_GET['long']))
                $clock = self::getOne($_GET['id'], $_GET['lat'], $_GET['long']);
            else
                $clock = self::getOne($_GET['id'], null, null);
            if($clock == null)
                http_response_code(404);
            else
                ClockView::render($clock, "details");
        }

    }
}

    if(isset($_GET)){
        if(isset($_GET['action'])){
            switch ($_GET['action']){
                case "details":
                    ClockController::actionDetails();
                    break;
            }
        }else{
            ClockController::actionIndex();
        }

        //ClockController::getClosest($_GET['lat'], $_GET['long']);
    }

class ListItem{
        static function render($heading, $placeholder, $hint, $link = "#"){
            echo '
            <a href="'.$link.'" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
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
        static function render($model, $action, $data = null)
        {
            include "static/html/header.html";
            switch ($action){
                case "index":
                    include "views/ClocksIndex.php";
                    break;
                case "details":
                    include "views/ClocksDetails.php";
                    break;
            }
            echo ' </body> </html>';

        }
    }


class Clock{
    public $id;
    public $coords;
    public string $address;
    public $dist ;
    public $type;
    public $friendlyDist;

    public $coordX;
    public $coordY;

    public function setDist(float $dist)
    {
        $this->dist = $dist;
        $this->friendlyDist = ($dist > 1000) ? round($dist/1000, 2)." км" : round($dist, 2)." м";
    }
    public function __construct($id, $address, $coords, $X, $Y, $dist, $type)
    {
        $this->id = $id;
        $this->address = $address;
        $this->coords = $coords;
        $this->coordX = $X;
        $this->coordY = $Y;
        $this->type = $type;

        ($dist != null) ? $this->setDist($dist) : false;
    }
    public static function fromPDORow($row): static
    {
        return new static($row['clock_id'],$row['Location'], $row['Coord'], $row['X'], $row['Y'],$row['dist']?? null, $row['type']);
    }
}
