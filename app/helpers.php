<?php

function formatBytes($size, $precision = 1)
{
    if (empty($size)) return '0 byte';

    $base = log($size, 1024);
    $suffixes = array('byte', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
