<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Events\Test;

use Es\Events\Event;
use Es\Events\Trigger;
use Es\Services\Provider;

class TriggerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $listener = 'foo';
        $method   = 'bar';
        $trigger  = new Trigger($listener, $method);
        $this->assertSame($listener, $trigger->getListener());
        $this->assertSame($method,   $trigger->getMethod());
    }

    public function testSerializable()
    {
        $trigger    = new Trigger('foo', 'bar');
        $serialized = serialize($trigger);
        $this->assertEquals($trigger, unserialize($serialized));
    }

    public function testInvoke()
    {
        $f = function ($event) {
            $event->setResult('trigger', true);
        };
        $services = Provider::getServices();
        $services->set('TriggerTest', $f);
        $trigger = new Trigger('TriggerTest', '__invoke');
        $event   = new Event();
        $trigger($event);
        $this->assertTrue($event->getResult('trigger'));
    }

    private function bar(Event $event)
    {
        //
    }

    public function testInvokeThrowExceptionIfListenerMethodIsNotCallable()
    {
        $services = Provider::getServices();
        $services->set('TriggerTest', $this);
        $trigger = new Trigger('TriggerTest', 'bar');
        $event   = new Event();
        $this->setExpectedException('\RuntimeException');
        $trigger($event);
    }
}
