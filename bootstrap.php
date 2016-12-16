<?php

require_once __DIR__.'/vendor/autoload.php';

use Command\RunCommand;
use Knp\Provider\ConsoleServiceProvider;
use fiunchinho\Silex\Provider\RabbitServiceProvider;

$parameters = require 'parameters.php';

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
    'rabbitmq.producers' => [
        'scheduler_producer' => [
            'connection'        => 'default',
            'exchange_options'  => [
                'name' => $parameters['rabbitmq']['exchange'],
                'type' => $parameters['rabbitmq']['exchange_type'],
            ],
        ],
    ]
]);

$app['console']->add(new RunCommand(
    $parameters['tasks'],
    $app['rabbit.producer']['scheduler_producer']
));

return $app;
