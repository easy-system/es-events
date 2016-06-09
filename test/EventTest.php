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

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $eventName    = 'foo';
        $eventContext = new \stdClass();
        $eventParams  = ['bar' => 'baz'];
        $event        = new Event($eventName, $eventContext, $eventParams);
        $this->assertSame($eventName,    $event->getName());
        $this->assertSame($eventContext, $event->getContext());
        $this->assertSame($eventParams,  $event->getParams());
    }

    public function testAddParams()
    {
        $firstParams  = ['foo' => 'bar', 'bar' => 'baz'];
        $secondParams = ['quu' => 'baz', 'bar' => 'foo'];
        $event        = new Event();
        $event->addParams($firstParams);
        $event->addParams($secondParams);
        $this->assertSame(
            array_merge($firstParams, $secondParams),
            $event->getParams()
        );
    }

    public function testSetParam()
    {
        $paramName  = 'foo';
        $paramValue = 'bar';
        $event      = new Event();
        $event->setParam($paramName, $paramValue);
        $this->assertSame($paramValue, $event->getParam($paramName));
    }

    public function testInvoke()
    {
        $eventName    = 'foo';
        $eventContext = new \stdClass();
        $eventParams  = ['bar' => 'baz'];
        $event        = new Event();
        $event($eventName, $eventContext, $eventParams);
        $this->assertSame($eventName,    $event->getName());
        $this->assertSame($eventContext, $event->getContext());
        $this->assertSame($eventParams,  $event->getParams());
    }
}
