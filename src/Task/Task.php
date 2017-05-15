<?php

/**
 * This file is part of rabbitmq-scheduler
 *
 * (c) 1001pharmacies <https://github.com/1001pharmacies/rabbitmq-scheduler>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Task;

use Cron\CronExpression;

/**
 * Task model
 *
 * @author Gilles gilles@1001pharmacies.com
 */
final class Task
{
    /**
     * @var string
     */
    private $name;

    /**
     * Crontab like schedule
     *
     * @var string
     */
    private $schedule;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var array
     */
    private $message;

    /**
     * Create a task
     *
     * @param string $name
     * @param string $schedule
     * @param array  $message
     * @param string $routingKey
     *
     * @return Task
     */
    public static function create($name, $schedule, array $message, $routingKey)
    {
        return new self($name, $schedule, $message, $routingKey);
    }

    /**
     * @param string      $name
     * @param string      $schedule
     * @param array       $message
     * @param string|null $routingKey
     */
    private function __construct($name, $schedule, array $message, $routingKey)
    {
        if (!CronExpression::isValidExpression($schedule)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid expression "%s" ',
                $schedule
            ));
        }

        $this->name       = $name;
        $this->schedule   = $schedule;
        $this->message    = $message;
        $this->routingKey = $routingKey;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Get routingKey
     *
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * Get message
     *
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }
}
