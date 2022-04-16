<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $rootPath = realpath(__DIR__ . '/../..');

    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            // Base path
            'base_path' => '',
        
            // Is debug mode
            'debug' => (getenv('APPLICATION_ENV') != 'production'),

            // 'Temprorary directory
            'temporary_path' => $rootPath . '/temp/tmp',

            // Route cache
            'route_cache' => $rootPath . '/temp/cache/routes',

            // View settings
            'view' => [
                'template_path' => $rootPath . '/src/view',
                'twig' => [
                    'cache' => $rootPath . '/temp/cache/twig',
                    'debug' => (getenv('APPLICATION_ENV') != 'production'),
                    'auto_reload' => true,
                ],
            ],

            // monolog settings
            'logger' => [
                'name' => 'app',
                'path' =>  getenv('docker') ? 'php://stdout' : $rootPath . '/temp/log/app.log',
                'level' => (getenv('APPLICATION_ENV') != 'production') ? Logger::DEBUG : Logger::INFO,
            ]
        ],
    ]);

    if (getenv('APPLICATION_ENV') == 'production') { // Should be set to true in production
        $containerBuilder->enableCompilation($rootPath . '/temp/cache');
    }
};
