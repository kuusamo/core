<?php

namespace Kuusamo\Vle\Cli;

use Composer\Script\Event;

class ScriptHandler
{
    public static function postInstall()
    {
        self::installSymlinks();
        echo "Operation complete!\n";
    }

    public static function postUpdate()
    {
        self::installSymlinks();
        echo "Operation complete!\n";
    }

    /**
     * Install all of the symlinks.
     *
     * @return void
     */
    private static function installSymlinks()
    {
        self::installSymlink('components');
        self::installSymlink('images');
        self::installSymlink('js');
        self::installSymlink('styles');
    }

    /**
     * Install a specific symlink.
     *
     * @param string $path Relative path.
     * @return void
     */
    private static function installSymlink(string $path)
    {
        if (!is_link('./public/' . $path)) {
            symlink(__DIR__ . '/../../public/' . $path, './public/' . $path);
        }
    }
}
