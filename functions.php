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
    $status_title = ($ports_status[$port]) ? "Open" : "Closed";
    $status_class = ($ports_status[$port]) ? 'status-open' : 'status-close';
    echo "<span class='status $status_class' title='$status_title'>$port</span>";
}

