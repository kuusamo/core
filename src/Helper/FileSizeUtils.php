<?php

namespace Kuusamo\Vle\Helper;

class FileSizeUtils
{
    /**
     * Convert a size in bytes to a human readable format.
     *
     * @param int $bytes File size in bytes.
     * @param int $dec   Number of decimal places.
     * @return string
     */
    public static function humanReadable($bytes, $dec = 1)
    {
        $size   = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }
}
