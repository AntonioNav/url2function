<?php

/**
 * Return transforms a bunch of seconds in HH:MM:SS format
 *
 * @param integer $total_seconds seconds
 *
 * @return string
 */
function formatSeconds($total_seconds)
{
    $hours              = floor($total_seconds/3600);
    $minutes            = (($total_seconds/60)%60);
    $seconds            = ($total_seconds%60);

    $time['hours']      = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $time['minutes']    = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    $time['seconds']    = str_pad($seconds, 2, "0", STR_PAD_LEFT);

    $time               = implode(':', $time);

    return $time;
}
