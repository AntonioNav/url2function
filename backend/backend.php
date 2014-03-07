<?php
$path = dirname(__FILE__);

require_once($path.'/resources/functions.php');

/**
 * Parse parms from request and execute called function
 *
 * @param array $parms
 *          [0]: Function to be executed
 *          [...]: Parameters for this function
 *
 * @return array
 */
function processRequest($parms){
    $function = array_shift($parms);

    switch ($function){
        //The name in URL
        case "function":
            return doFunction($parms);
            break;
        default:
            return array(
                "error"     => -1,
                "result"    => "Function not supported!"
            );
    }
}

/**
 * Real Function. An exaple function to format seconds in string format.
 *
 * @param array $parms
 *          - All params that you need for your function
 *
 * @return array
 */
function doFunction($parms){

    //Some validation
    switch (count($parms)) {
        case 1:
            $seconds = is_numeric($parms[0]) ? $parms[0] : 0;
            break;
        default:
            $seconds = 0;
    }

    return array(
        "error"     => 0,
        "result"    => formatSeconds($seconds)
    );
}
