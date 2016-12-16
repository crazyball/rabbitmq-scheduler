<?php

/**
 * This file is part of 1001 Pharmacies rabbitmq-scheduler
 *
 * (c) 1001pharmacies <https://github.com/1001pharmacies/rabbitmq-scheduler>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Task;

use Task\Task;

/**
 * Tests for Task
 *
 * @author Gilles <gilles@1001pharmacies.com>
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $task = Task::create('name', '* * * * *', ['foo' => 'bar'], 'routingKey');

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('name', $task->getName());
        $this->assertEquals('* * * * *', $task->getSchedule());
        $this->assertEquals('routingKey', $task->getRoutingKey());
        $this->assertEquals(['foo' => 'bar'], $task->getMessage());
    }

    public function testThrowInvalidArgumentIfNotACorrectSchedule()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        Task::create('name', 'fooooooo', ['foo' => 'bar'], 'routingKey');
    }
}
