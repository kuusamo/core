<?php

namespace Kuusamo\Vle\Service\Templating;

use Kuusamo\Vle\Entity\Theme;

class TemplatingFactory
{
    private static $theme;

    /**
     * Create a templating service.
     *
     * @return Templating
     */
    public static function create(): Templating
    {
        $templating = new Templating(self::$theme);

        $templating->addHelper('date', [
            'iso' => function ($value) {
                return ($value) ? $value->format('Y-m-d') : null;
            },
            'long' => function ($value) {
                return ($value) ? $value->format('j F Y') : null;
            }
        ]);

        return $templating;
    }

    /**
     * Set a custom theme.
     *
     * @param Theme $theme Custom theme.
     * @return void
     */
    public static function setTheme(Theme $theme)
    {
        self::$theme = $theme;
    }
}
