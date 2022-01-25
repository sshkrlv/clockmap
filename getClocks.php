<?php
namespace Main;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('models/Clock.php');
require_once('views/Clock/main.php');

class ClockController{

    static function getAll($userLat = null, $userLong = null){
        require("db.php");
        if (isset($dbh)) {
            $sql = "SELECT clock_registry.global_id as clock_id, X(geodata) as X, Y(geodata) as Y, Location, ST_AsText(geodata) as Coord, clock_types.Name as type";
            if(isset($userLat) && isset($userLong)){
                $sql .= ",ST_Distance_Sphere(ST_GeomFromText('POINT(".$userLat." ".$userLong.")'), geodata) AS dist";
            }
            $sql .=" FROM clock_registry
            INNER JOIN clock_types ON clock_registry.`type_ID` = clock_types.ID";

            /* @var $dbh \PDO */
            $result = $dbh->query($sql)->fetchAll();
            $clocks = [];
            if(!$result)
                return null;
            else {
                foreach ($result as $row)
                    $clocks[] = Clock::fromPDORow($row);
                return $clocks;
            }
        }
    }

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
        return null;
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

    static function geoJson(){
        /* @var $clocks Clock[] */
        $clocks = ClockController::getAll();
        $features = [];
        foreach ($clocks as $clock){
            $features[] = (object)['type' => 'feature', 'id' => $clock->id, 'geometry' => (object)['type' => 'Point',
                'coordinates' => [$clock->coordX, $clock->coordY]], 'properties' => (object)['balloonContent' => htmlspecialchars($clock->address) ]];
        }
        header('Content-Type: application/json');
        echo json_encode((object)["type" => "FeatureCollection", 'features' => $features],  JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}

    if(isset($_GET)){
        if(isset($_GET['action'])){
            switch ($_GET['action']){
                case "details":
                    ClockController::actionDetails();
                    break;
                case 'geoJson':
                    ClockController::geoJson();
                    break;
            }
        }else{
            ClockController::actionIndex();
        }
   }

