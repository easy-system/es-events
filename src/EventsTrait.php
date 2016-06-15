<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Events;

use Es\Services\Provider;

/**
 * The accessors of Events.
 */
trait EventsTrait
{
    /**
     * Sets the events.
     *
     * @param EventsInterface $events The events
     */
    public function setEvents(EventsInterface $events)
    {
        Provider::getServices()->set('Events', $events);
    }

    /**
     * Gets the events.
     *
     * @return EventsInterface The events
     */
    public function getEvents()
    {
        return Provider::getServices()->get('Events');
    }
}
