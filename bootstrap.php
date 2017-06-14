<?php

require_once __DIR__.'/vendor/autoload.php';

use Command\RunCommand;
use Ivoba\Silex\Provider\ConsoleServiceProvider;
use fiunchinho\Silex\Provider\RabbitServiceProvider;
use Silex\Provider\MonologServiceProvider;

$parameters = require __DIR__.'/config/parameters.php';

$app = new Silex\Application();

$app->register(new ConsoleServiceProvider(), [
    'console.name'              => 'RabbitMq Scheduler',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__,
]);

$app->register(new RabbitServiceProvider(), [
    'rabbit.connections' => [
        'default' => [
            'host'      => $parameters['rabbitmq']['host'],
            'port'      => $parameters['rabbitmq']['port'],
            'user'      => $parameters['rabbitmq']['user'],
            'password'  => $parameters['rabbitmq']['password'],
            'vhost'     => $parameters['rabbitmq']['vhost'],
        ]
    ],
    'rabbit.producers' => [
        'scheduler_producer' => [
            'connection'        => 'default',
            'auto_setup_fabric' => false,
            'exchange_options'  => [
                'name' => $parameters['rabbitmq']['exchange'],
                'type' => $parameters['rabbitmq']['exchange_type'],
            ],
        ],
    ]
]);

if (array_key_exists('monolog', $parameters)) {
    $app->register(new MonologServiceProvider(), [
        'monolog.logfile' => $parameters['monolog']['logfile'],
        'monolog.level'   => $parameters['monolog']['level'],
        'monolog.name'    => 'rabbitmq-scheduler',
    ]);
}

$app['console']->add(new RunCommand(
    $parameters['tasks'],
    $app['rabbit.producer']['scheduler_producer'],
    $app['monolog'] ?: null
));

return $app;
