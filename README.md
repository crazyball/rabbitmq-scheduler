# RabbitMq Scheduler

Allows to send simple RabbitMq message periodically.

[![Build Status](https://travis-ci.org/1001Pharmacies/rabbitmq-scheduler.svg?branch=master)](https://travis-ci.org/1001Pharmacies/rabbitmq-scheduler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/1001Pharmacies/rabbitmq-scheduler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/1001Pharmacies/rabbitmq-scheduler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/1001Pharmacies/rabbitmq-scheduler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/1001Pharmacies/rabbitmq-scheduler/?branch=master)

## Installation

```bash
git clone https://github.com/1001Pharmacies/rabbitmq-scheduler.git
cd rabbitmq-scheduler
make install
```

Add crontab (`crontab -e`)

```bash
* * * * * cd /path/to/rabbitmq-scheduler && php bin/console rabbitmq-scheduler:run
```

## Deploy

```bash
make install -e ENV=prod
```

## Settings

Settings are stored in `parameters.php`.

### Tasks

You can setup planned tasks in `parameters.php` file :

```php
<?php

use Task\Task;

return [
    'tasks' => [
        Task::create('Name', '0 0 * * *', ['my' => 'message'], 'routing-key'),
        Task::create('Name 2', '@daily', ['my' => 'message'], 'routing-key-2'),
    ],
];
```

### RabbitMq

Setup your RabbitMq connection in `parameters.php` file :

```php
<?php

return [
    'tasks' => [],
    'rabbitmq' => [
        'host'          => 'localhost',
        'port'          => 5672,
        'user'          => 'guest',
        'password'      => 'guest',
        'vhost'         => '/',
        'exchange'      => 'scheduler',
        'exchange_type' => 'direct',
    ],
];
```

The scheduler will put every message in the same exchange, use the task routing key for dispatching.
