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
use Es\Events\Listeners;
use Es\Events\Trigger;
use Es\Services\Provider;
use Es\Services\Services;

class TriggerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'TriggerTestHelper.php';
    }

    public function testSetListeners()
    {
        $listeners = new Listeners();
        Trigger::setListeners($listeners);
        $this->assertSame($listeners, Trigger::getListeners());
    }

    public function testGetListeners()
    {
        TriggerTestHelper::resetListeners();
        $listeners = new Listeners();
        $services  = new Services();
        $services->set('Listeners', $listeners);
        Provider::setServices($services);

        $trigger = new Trigger('foo', 'bar');
        $this->assertSame($listeners, $trigger->getListeners());
    }

    public function testConstruct()
    {
        $listener = 'foo';
        $method   = 'bar';
        $trigger  = new Trigger($listener, $method);
        $this->assertSame($listener, $trigger->getListener());
        $this->assertSame($method,   $trigger->getMethod());
    }

    public function testSetListener()
    {
        $trigger = new Trigger('foo', 'bar');
        $trigger->setListener('baz');
        $this->assertSame('baz', $trigger->getListener());
    }

    public function testSetMethod()
    {
        $trigger = new Trigger('foo', 'bar');
        $trigger->setMethod('baz');
        $this->assertSame('baz', $trigger->getMethod());
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
        $listeners = new Listeners();
        $listeners->set('TriggerTest', $f);
        Trigger::setListeners($listeners);

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
        $listeners = new Listeners();
        $listeners->set('TriggerTest', $this);
        Trigger::setListeners($listeners);

        $trigger = new Trigger('TriggerTest', 'bar');
        $event   = new Event();
        $this->setExpectedException('\RuntimeException');
        $trigger($event);
    }
}
