<?php

/**
 * Check port status
 */
function open_port($host, $port) {
    $fp = @fsockopen($host, $port, $errno, $errstr, 0.1);
    @fclose($fp);
    return ($errno === 0);
}

/**
 * Get all ports status
 */
function ports_status($ports, $host) {
    $ports_status = array();
    foreach($ports as $key => $port) {
        if (open_port($host, $port) != "1") {
            $ports_status[$port] = FALSE;
        } else {
            $ports_status[$port] = TRUE;
        }
    }
    return $ports_status;
}

/**
 * Return html status for a specific port
 */
function display_status($port, $ports_status) {
    if ($ports_status[$port] != 1) {
        echo "<img src='img/reddot.png' height='20px'>";
    } else {
        echo "<img src='img/greendot.png' height='20px'>";
    }
    echo "$port, ";
}

