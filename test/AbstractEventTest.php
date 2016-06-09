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

use Es\Events\AbstractEvent;
use ReflectionProperty;

class AbstractEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $eventName  = 'Foo';
        $event      = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $reflection = new ReflectionProperty($event, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($event, $eventName);
        $this->assertSame($eventName, $event->getName());
    }

    public function testGetContext()
    {
        $eventContext = new \stdClass();
        $event        = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $reflection   = new ReflectionProperty($event, 'context');
        $reflection->setAccessible(true);
        $reflection->setValue($event, $eventContext);
        $this->assertSame($eventContext, $event->getContext());
    }

    public function testGetParam()
    {
        $eventParams = [
            'foo' => 'bar',
        ];
        $event      = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $reflection = new ReflectionProperty($event, 'params');
        $reflection->setAccessible(true);
        $reflection->setValue($event, $eventParams);
        $this->assertSame('bar', $event->getParam('foo'));
    }

    public function testGetParamReturnDefaultValue()
    {
        $default = 'foo';
        $event   = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $this->assertSame($default, $event->getParam('baz', $default));
    }

    public function testGetParams()
    {
        $eventParams = [
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
        ];
        $event      = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $reflection = new ReflectionProperty($event, 'params');
        $reflection->setAccessible(true);
        $reflection->setValue($event, $eventParams);
        $this->assertSame($eventParams, $event->getParams());
    }

    public function testGetResultReturnsLastResult()
    {
        $results = ['foo', 'bar', 'baz'];
        $event   = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        foreach ($results as $item) {
            $event->setResult($item);
        }
        $this->assertSame('baz', $event->getResult());
    }

    public function testGetResultReturnsNamedResult()
    {
        $results = ['foo', 'bar' => 'baz', 'bat'];
        $event   = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        foreach ($results as $name => $item) {
            $event->setResult($name, $item);
        }
        $this->assertSame('baz', $event->getResult('bar'));
    }

    public function testGetResultReturnsDefault()
    {
        $default = 'foo';
        $event   = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $this->assertSame($default, $event->getResult('bar', $default));
    }

    public function testGetResults()
    {
        $results = ['foo', 'bar', 'baz'];
        $event   = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        foreach ($results as $item) {
            $event->setResult($item);
        }
        $this->assertSame($results, $event->getResults());
    }

    public function testStopPropagation()
    {
        $event = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $event->stopPropagation();
        $this->assertTrue($event->propagationIsStopped());

        $event->stopPropagation(false);
        $this->assertFalse($event->propagationIsStopped());
    }

    public function testToString()
    {
        $eventName  = 'Foo';
        $event      = $this->getMockForAbstractClass(AbstractEvent::CLASS);
        $reflection = new ReflectionProperty($event, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($event, $eventName);
        $this->assertSame($eventName, (string) $event);
    }
}
