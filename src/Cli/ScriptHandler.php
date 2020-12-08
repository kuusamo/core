<?php

namespace Kuusamo\Vle\Cli;

use Composer\Script\Event;

class ScriptHandler
{
    public static function postInstall()
    {
        self::installAssets();
        echo "Operation complete!\n";
    }

    public static function postUpdate()
    {
        self::installAssets();
        echo "Operation complete!\n";
    }

    /**
     * Install the public assets.
     *
     * @return void
     */
    private static function installAssets()
    {
        self::installAssetDirectory('components');
        self::installAssetDirectory('images');
        self::installAssetDirectory('js');
        self::installAssetDirectory('styles');
    }

    /**
     * Install a specific directory of assets.
     *
     * @param string $path Relative path.
     * @return void
     */
    private static function installAssetDirectory(string $path)
    {
        FileTools::recursiveCopy(
            __DIR__ . '/../../public/' . $path,
            './public/' . $path
        );
    }
}
