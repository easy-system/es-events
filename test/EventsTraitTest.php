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
use Es\Services\Provider;
use Es\Services\Services;

class EventsTraitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'EventsTraitTemplate.php';
    }

    public function testSetEvents()
    {
        $events   = new Events();
        $template = new EventsTraitTemplate();
        $template->setEvents($events);
        $this->assertSame($events, $template->getEvents());
    }

    public function testGetEvents()
    {
        $events   = new Events();
        $services = new Services();
        $services->set('Events', $events);

        Provider::setServices($services);
        $template = new EventsTraitTemplate();
        $this->assertSame($events, $template->getEvents());
    }
}
