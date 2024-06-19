<?php

namespace Redux\Modular\Utils;


Class Utils {
    public static $module_path;
    public static $module_folers = [];

    public function __construct(){
        self::$module_folers = [
            'App' => [
                'Http' => [
                    'Controllers',
                    'Requests',
                    'Resources',
                    'Middlewares'
                ],
                'Models',
                'Exports',
                'Imports',
                'Rules',
                'Providers',
                'Traits',
                'Services',
                'Interfaces',
                'Policies'
            ],
            'Database' => [
                'Factories',
                'Migrations',
                'Seeders'
            ],
            'routes',
            'tests',
            'views'
        ];

        self::$module_path = config('redux-modular.modulePath');
    }
}
?>
