<?php

use Task\Task;

return [
    'tasks' => [
        Task::create('Product all update', '0 2 * * 1-6', [], 'product_update_all'),
        Task::create('Product all update', '0 2 * * 0', ['with-picture' => true], 'product_update_all'),
    ],
    'rabbitmq' => [
        'host'          => 'localhost',
        'port'          => 5671,
        'user'          => 'guest',
        'password'      => 'guest',
        'vhost'         => '/',
        'exchange'      => 'scheduler',
        'exchange_type' => 'direct',
    ],
];
