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

use Es\Events\Exception\ListenerNotFoundException;
use Es\Events\Listeners;

class ListenersTest extends \PHPUnit_Framework_TestCase
{
    public function testMergeRegistry()
    {
        $targetConfig = [
            'foo' => 'foo',
            'bar' => 'foo',
        ];
        $target = new Listeners();
        $target->add($targetConfig);

        $sourceConfig = [
            'bar' => 'bar',
            'baz' => 'baz',
        ];
        $source = new Listeners();
        $source->add($sourceConfig);

        $return = $target->merge($source);
        $this->assertSame($return, $target);

        $expected = [
            'foo' => $targetConfig['foo'],
            'bar' => $sourceConfig['bar'],
            'baz' => $sourceConfig['baz'],
        ];
        $this->assertSame($expected, $target->getRegistry());
    }

    public function testMergeInstances()
    {
        $targetConfig = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
        ];
        $target = new Listeners();
        foreach ($targetConfig as $key => $item) {
            $target->set($key, $item);
        }

        $sourceConfig = [
            'bar' => new \stdClass(),
            'baz' => new \stdClass(),
        ];
        $source = new Listeners();
        foreach ($sourceConfig as $key => $item) {
            $source->set($key, $item);
        }

        $return = $target->merge($source);
        $this->assertSame($return, $target);

        $expected = [
            'foo' => $targetConfig['foo'],
            'bar' => $sourceConfig['bar'],
            'baz' => $sourceConfig['baz'],
        ];
        $this->assertSame($expected, $target->getInstances());
    }

    public function testExteptionClassWhenControllerNotFound()
    {
        $listeners = new Listeners();
        $this->setExpectedException(ListenerNotFoundException::CLASS);
        $listeners->get('foo');
    }
}
