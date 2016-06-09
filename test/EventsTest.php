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

use Es\Events\Events;
use Es\Events\Test\FakeEvents\AnimalEvent;
use Es\Events\Test\FakeEvents\MammalEvent;
use Es\Events\Test\FakeEvents\TigerEvent;
use Es\Events\Trigger;
use Es\Services\Provider;

class EventsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $f = function ($event) {
            $event->setResult('processed', true);
        };
        $services = Provider::getServices();
        $services->set('EventsTest', $f);

        $fakeEventsDir = __DIR__
                       . DIRECTORY_SEPARATOR
                       . 'FakeEvents'
                       . DIRECTORY_SEPARATOR;

        require_once $fakeEventsDir . 'AnimalEvent.php';
        require_once $fakeEventsDir . 'MammalEvent.php';
        require_once $fakeEventsDir . 'TigerEvent.php';
    }

    public function testAttachTrigger()
    {
        $trigger = $this->getMockBuilder(Trigger::CLASS)
                        ->disableOriginalConstructor()
                        ->getMock();

        $events = new Events();
        $events->attachTrigger($trigger, 'fooEvent');
        $trigger->expects($this->once())->method('__invoke');
        $events('fooEvent');
    }

    public function testAttach()
    {
        $events = new Events();
        $events->attach('Foo.Event', 'EventsTest', '__invoke');
        $event = $events('Foo.Event');
        $this->assertTrue($event->getResult('processed'));
    }

    public function testInvokeCreateEventAndTriggerIt()
    {
        $events = new Events();
        $events->attach('Foo.Event', 'EventsTest', '__invoke');
        $event = $events('Foo.Event');
        $this->assertTrue($event->getResult('processed'));
    }

    public function testInvokeCreateEventAndSetsContext()
    {
        $events  = new Events();
        $context = new \stdClass();
        $event   = $events('Foo', $context);
        $this->assertSame($context, $event->getContext());
    }

    public function testInvokeCreateEventAndSetsParams()
    {
        $events = new Events();
        $params = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $event = $events('Foo', null, $params);
        $this->assertSame($params, $event->getParams());
    }

    public function invalidEventNameDataProvider()
    {
        return [
            [null],
            [true],
            [false],
            [100],
            [[]],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidEventNameDataProvider
     */
    public function testInvokeRaiseExceptionIfInvalidEventNameProvided($eventName)
    {
        $events = new Events();
        $this->setExpectedException('InvalidArgumentException');
        $events($eventName);
    }

    public function testDetachTrigger()
    {
        $events = new Events();
        //
        $events->attach('Foo.Event', 'EventsTest', '__invoke');
        $event = $events('Foo.Event');
        $this->assertTrue($event->getResult('processed'));
        //
        $trigger = new Trigger('EventsTest', '__invoke');
        $events->detachTrigger($trigger, 'Foo.Event');
        $event = $events('Foo.Event');
        $this->assertNull($event->getResult('processed'));
    }

    public function testClearTriggers()
    {
        $events = new Events();
        //
        $events->attach('Foo.Event', 'EventsTest', '__invoke');
        $event = $events('Foo.Event');
        $this->assertTrue($event->getResult('processed'));
        //
        $events->clearTriggers('Foo.Event');
        $event = $events('Foo.Event');
        $this->assertNull($event->getResult('processed'));
    }

    public function testTriggerEventsWithInheritance()
    {
        $triggerTeenager = $this->getMockBuilder(Trigger::CLASS)
                                ->disableOriginalConstructor()
                                ->getMock();

        $triggerProfessor = $this->getMockBuilder(Trigger::CLASS)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $triggerHunter = $this->getMockBuilder(Trigger::CLASS)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $events = new Events();
        $events->attachTrigger($triggerTeenager,  AnimalEvent::CLASS);
        $events->attachTrigger($triggerProfessor, MammalEvent::CLASS);
        $events->attachTrigger($triggerHunter,    TigerEvent::CLASS);

        $triggerTeenager->expects($this->once())->method('__invoke');
        $triggerProfessor->expects($this->once())->method('__invoke');
        $triggerHunter->expects($this->once())->method('__invoke');

        $events->trigger(new TigerEvent('Migration.Event'));
    }

    public function testSerializable()
    {
        $events = new Events();
        $events->attach('FooListener', 'BarMethod', 'BazEvent');
        $serialized = serialize($events);
        $this->assertEquals($events, unserialize($serialized));
    }

    public function testMerge()
    {
        $target = new Events();
        $source = new Events();
        $source->attach('Test.Event', 'EventsTest', '__invoke');
        $target->merge($source);
        $event = $target('Test.Event');
        $this->assertTrue($event->getResult('processed'));
    }

    public function testSignalOrder()
    {
        $events = new Events();

        $foo = function ($event) { $event->setResult('foo'); };
        $bar = function ($event) { $event->setResult('bar'); };
        $baz = function ($event) { $event->setResult('baz'); };
        $bat = function ($event) { $event->setResult('bat'); };
        $services = Provider::getServices();
        $services->set('foo', $foo)->set('bar', $bar)->set('baz', $baz)->set('bat', $bat);
        $config = [
            ['Test.Event', 'foo', '__invoke', 1],
            ['Test.Event', 'bar', '__invoke', 3],
            ['Test.Event', 'baz', '__invoke', 4],
            ['Test.Event', 'bat', '__invoke', 2],
        ];
        foreach ($config as $item) {
            call_user_func_array([$events, 'attach'], $item);
        }
        $event   = $events('Test.Event');
        $results = $event->getResults();
        $this->assertEquals('baz', $results[0], 'signal with priority 4');
        $this->assertEquals('bar', $results[1], 'signal with priority 3');
        $this->assertEquals('bat', $results[2], 'signal with priority 2');
        $this->assertEquals('foo', $results[3], 'signal with priority 1');
    }

    public function testStopPropagation()
    {
        $events = new Events();
        $foo    = function ($event) { $event->setResult('foo'); };
        $bar = function ($event) { $event->setResult('bar'); $event->stopPropagation(); };
        $baz = function ($event) { $event->setResult('baz'); };
        $bat = function ($event) { $event->setResult('bat'); };
        $services = Provider::getServices();
        $services->set('foo', $foo)->set('bar', $bar)->set('baz', $baz)->set('bat', $bat);
        $config = [
            ['Test.Event', 'foo', '__invoke', 4],
            ['Test.Event', 'bar', '__invoke', 3],
            ['Test.Event', 'baz', '__invoke', 2],
            ['Test.Event', 'bat', '__invoke', 1],
        ];
        foreach ($config as $item) {
            call_user_func_array([$events, 'attach'], $item);
        }
        $event   = $events('Test.Event');
        $results = $event->getResults();
        $this->assertTrue(count($results) == 2);
    }
}
