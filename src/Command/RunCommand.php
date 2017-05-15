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
use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var \DateTime|null
     */
    private $currentDate;

    /**
     * @param array<Task>          $tasks
     * @param ProducerInterface    $producer
     * @param \DateTime|null       $currentDate
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        array $tasks,
        ProducerInterface $producer,
        LoggerInterface $logger = null,
        \DateTime $currentDate = null
    ) {
        parent::__construct('rabbitmq-scheduler:run');

        $this->tasks       = $tasks;
        $this->producer    = $producer;
        $this->logger      = $logger;
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

                $this->log($task);
            }
        }
    }

    private function isDue(Task $task)
    {
        $expression = CronExpression::factory($task->getSchedule());

        return $expression->isDue($this->currentDate);
    }

    protected function log(Task $task)
    {
        if (null !== $this->logger) {
            $this->logger->info(sprintf(
                'Task "%s" launched',
                $task->getName()
            ));
        }
    }
}
