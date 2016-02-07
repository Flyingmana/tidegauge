<?php

function bytes($bytes)
{
    $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
    if($bytes == 0)
        return sprintf('%.2f '.$symbols[0], 0);
    $exp = floor(log(abs($bytes)) / log(1024));
    return sprintf('%.2f '.$symbols[$exp], $bytes/pow(1024, floor($exp)));
}


function writeLine($text)
{
    echo $text.
        "\t(".bytes(memory_get_usage()).")".
        "\t(".bytes(memory_get_peak_usage()).")".
        PHP_EOL;
}
