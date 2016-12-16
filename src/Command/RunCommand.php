<?php

/**
 * This file is part of 1001 Pharmacies rabbitmq-scheduler
 *
 * (c) 1001pharmacies <https://github.com/1001pharmacies/rabbitmq-scheduler>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Command;

use Cron\CronExpression;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Task;

/**
 * Command for running tasks
 *
 * @author Gilles <gilles@1001pharmacies.com>
 */
class RunCommand extends Command
{
    /**
     * @var array<Task>
     */
    private $tasks;

    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var \DateTime
     */
    private $currentDate;

    /**
     * @param array<Task>       $tasks
     * @param ProducerInterface $producer
     * @param \DateTime|null    $currentDate
     */
    public function __construct($tasks, ProducerInterface $producer, \DateTime $currentDate = null)
    {
        parent::__construct('rabbitmq-scheduler:run');

        $this->tasks       = $tasks;
        $this->producer    = $producer;
        $this->currentDate = $currentDate ?: new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->tasks as $task) {
            if ($this->isDue($task)) {
                $this->producer->publish(
                    json_encode($task->getMessage()),
                    $task->getRoutingKey()
                );
            }
        }
    }

    private function isDue(Task $task)
    {
        $expression = CronExpression::factory($task->getSchedule());

        return $expression->isDue($this->currentDate);
    }
}
