<?php

return [
    'tasks' => [],
    'rabbitmq' => [
        'host'          => 'localhost',
        'port'          => 5672,
        'user'          => 'guest',
        'password'      => 'guest',
        'vhost'         => '/',
        'exchange'      => 'sheduler',
        'exchange_type' => 'direct',
    ],
];
