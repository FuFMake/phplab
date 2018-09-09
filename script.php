<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $validated = false;
    $filtrated = false;
    $err = "";
    $start = microtime(true);

    date_default_timezone_set('Europe/Moscow');

    if ($_GET["x"] == "") {
        $err = "Пустое значение х";
        response();
        return;
    } 
    if (!is_numeric($_GET["x"]))  {
        $err = "Х не является числом";
        response();
        return;
    }
    $x = intval($_GET["x"]);

    if ($x < -5 || $x > 3) {
        $err =  "Х не соответстует диапазону";
        response();
        return;
    }

    if ($_GET["y"] == "") {
        $err =  "Пустое значение Y";
        response();
        return;
    } 
    if (!is_numeric($_GET["y"]))  {
        $err =  "Y не является числом";
        response();
        return;
    }
    $y = intval($_GET["y"]);

    if ($y < -5 || $y > 3) {
        $err =  "Y не соответствует диапазону";
        response();
        return;
    }

    if ($_GET["z"] == "") {
        $err =  "Пустое значение R";
        response();
        return;
    } 
    if (!is_numeric($_GET["z"]))  {
        $err =  "R не является числом";
        response();
        return;
    }
    $z = intval($_GET["z"]);

    if ($z < 1 || $z > 5) {
        $err = "R не соответствует диапазону";
        response();
        return;
    }

    $filtrated = true;

    if ($x < 0 && $y < 0) {
        $validated = false;
        response();
        return;
    }

    if ($x == 0 && $y == 0) {
        $validated = true;
        response();
        return;
    }

    if ($x >= 0 && $y >= 0) {
        if ($x <= $z && $y <= $z/2) {
            $validated = true;
            response();
            return;
        } 
    }

    if ($x >= 0 && $y <= 0) {
        $expectedx = 2*($y + ($z/2));
        $expectedy = (1/2)*$x - ($z/2);
        if($x <= $expectedx && $y >= $expectedy) {
            $validated = true;
            response();
            return;
        }
    }
    
    if ($x <= 0 && $y >= 0) {
        $distance = $x*$x + $y*$y;
        if ($distance <= ($z/2)*($z/2)) {
            $validated = true;
            response();
            return;
        } 
    }

    response();
    return;

    function response() {

        global $validated, $err, $filtrated;

        global $x, $y, $z;

        if ($x == NULL) {
            $x = 0;
        }

        if ($y == NULL) {
            $y = 0;
        }
        
        if ($z == NULL) {
            $z = 0;
        }
        

        global $start;

        $end = round(microtime(true) - $start, 4);
        $time = date("H:m:s");

        $validatedstr = $validated ? 'true' : 'false';

        $jsonstring = "{\"validated\":".$validatedstr.",\"error\":"."\"$err\""
            .",\"x\":".$x.",\"y\":".$y.",\"r\":".$z.",\"scripttime\":".$end.",\"time\":"."\"$time\""."}";

        // $jsonarray = array("validated" => $validated,
        //                     "error" => $err,
        //                 "x" => $x,
        //                 "y" => $y,
        //                 "r" => $z,
        //                 "scripttime" => $end,
        //                 "time" => $time);

        // echo json_encode($jsonarray);

        echo $jsonstring;
        // echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
        if ($filtrated) {
            $fd = fopen("history.json", 'a+') or die("zaebalo");
            fwrite($fd, $jsonstring);
            fwrite($fd, ",");
            fclose($fd);
        }
       }
 ?>