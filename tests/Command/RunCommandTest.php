<?php

/**
 * This file is part of 1001 Pharmacies rabbitmq-scheduler
 *
 * (c) 1001pharmacies <https://github.com/1001pharmacies/rabbitmq-scheduler>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Command;

use Command\RunCommand;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Task\Task;

/**
 * Tests for RunCommand
 *
 * @author Gilles <gilles@1001pharmacies.com>
 */
class RunCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testWillRunScheduledTasks()
    {
        $producer = $this->prophesize(ProducerInterface::class);

        $producer
            ->publish('{"my":"message"}', 'routing-key')
            ->shouldBeCalled()
        ;

        $producer
            ->publish('{"my":"message-2"}', 'routing-key-2')
            ->shouldNotBeCalled()
        ;

        $command = new RunCommand(
            [
                Task::create('Name', '0 0 * * *', ['my' => 'message'], 'routing-key'),
                Task::create('Name 2', '1 0 0 * *', ['my' => 'message-2'], 'routing-key-2'),
            ],
            $producer->reveal(),
            null,
            new \DateTime('2016-12-15 00:00:00')
        );

        $tester = new CommandTester($command);
        $tester->execute([]);
    }

    public function testWillLogScheduledTasks()
    {
        $producer = $this->prophesize(ProducerInterface::class);
        $logger   = $this->prophesize(LoggerInterface::class);

        $logger
            ->info('Task "Name" launched')
            ->shouldBeCalled()
        ;

        $command = new RunCommand(
            [
                Task::create('Name', '0 0 * * *', ['my' => 'message'], 'routing-key'),
                Task::create('Name 2', '1 0 0 * *', ['my' => 'message-2'], 'routing-key-2'),
            ],
            $producer->reveal(),
            $logger->reveal(),
            new \DateTime('2016-12-15 00:00:00')
        );

        $tester = new CommandTester($command);
        $tester->execute([]);
    }
}
