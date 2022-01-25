<?php
namespace Main;
use Main\Clock;

class ClockView
{
    static function render($model, $action, $data = null)
    {
        include "static/html/header.html";
        switch ($action){
            case "index":
                require_once ("views/partial/ListItem.php");
                include "ClocksIndex.php";
                break;
            case "details":
                include "ClocksDetails.php";
                break;
        }
        echo ' </body> </html>';
    }
}
