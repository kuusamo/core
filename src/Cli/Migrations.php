<?php

namespace Kuusamo\Vle\Cli;

class Migrations
{
    public static function config()
    {
        return [
            'migrations_paths' => [
                'Kuusamo\Vle\Migration' => __DIR__ . '/../Migration'
            ],
            'all_or_nothing' => true,
            'check_database_platform' => true
        ];
    }
}
