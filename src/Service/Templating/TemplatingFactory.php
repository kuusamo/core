<?php

namespace Kuusamo\Vle\Service\Templating;

class TemplatingFactory
{
    /**
     * Create a templating service.
     *
     * @return Templating
     */
    public static function create(): Templating
    {
        $templating = new Templating;
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
}
