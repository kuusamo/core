<?php

namespace Kuusamo\Vle\Cli;

class FileTools
{
    /**
     * Recursively copy one directory to another.
     *
     * @param string $src Source path.
     * @param string $dst Destination path.
     * @return void
     */
    public static function recursiveCopy(string $src, string $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }

    /**
     * Recursively delete a path.
     *
     * @param string $src Source path.
     * @return void
     */
    public static function recursiveDelete(string $src)
    {
        $dir = opendir($src);
 
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recursiveDelete($src . '/' . $file);
                } else {
                    unlink($src . '/' . $file);
                }
            }
        }

        closedir($dir);
        rmdir($src);
    }
}
