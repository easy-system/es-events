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

use InvalidArgumentException;

/**
 * The event manager.
 */
class Events implements EventsInterface
{
    /**
     * The events and their triggers.
     *
     * @var array
     */
    protected $events = [];

    /**
     * Attachs an trigger to the specified event.
     *
     * @param TriggerInterface $trigger          The event trigger
     * @param string           $eventNameOrClass The event name or class of event
     * @param int              $priority         Optional; by default 1. The event priority
     *
     * @return Events
     */
    public function attachTrigger(TriggerInterface $trigger, $eventNameOrClass, $priority = 1)
    {
        $event = ltrim((string) $eventNameOrClass, '\\');

        $this->events[$event][(int) $priority . '.0'][] = $trigger;

        return $this;
    }

    /**
     * Attachs an listener to the specified event.
     *
     * @param string $eventNameOrClass The event name or class of event
     * @param string $listenerName     The name of listener
     * @param string $listenerMethod   The listener method name
     * @param int    $priority         Optional; by default 1. The event priority
     *
     * @return Events
     */
    public function attach($eventNameOrClass, $listenerName, $listenerMethod, $priority = 1)
    {
        $trigger = new Trigger($listenerName, $listenerMethod);
        $this->attachTrigger($trigger, $eventNameOrClass, $priority);

        return $this;
    }

    /**
     * Detaches trigger from the specified event.
     *
     * @param TriggerInterface $trigger          The event trigger
     * @param string           $eventNameOrClass The event name or class of event
     *
     * @return Events
     */
    public function detachTrigger(TriggerInterface $trigger, $eventNameOrClass)
    {
        $event = (string) $eventNameOrClass;

        if (isset($this->events[$event])) {
            foreach ($this->events[$event] as $priority => $triggers) {
                if (false !== ($key = array_search($trigger, $triggers, false))) {
                    unset($this->events[$event][$priority][$key]);
                }
            }
        }

        return $this;
    }

    /**
     * Clears all triggers for a given event.
     *
     * @param string $eventNameOrClass The event name or class of event
     *
     * @return Events
     */
    public function clearTriggers($eventNameOrClass)
    {
        if (isset($this->events[$eventNameOrClass])) {
            unset($this->events[$eventNameOrClass]);
        }

        return $this;
    }

    /**
     * Triggers an event.
     *
     * @param EventInterface $event The instance of EventInterface
     *
     * @return Events
     */
    public function trigger(EventInterface $event)
    {
        $this->pullTriggers($event);

        return $this;
    }

    /**
     * Merges with other events.
     *
     * @param EventsInterface $source The data source
     *
     * @return null|array If the source was passed returns
     *                    null, source data otherwise
     */
    public function merge(EventsInterface $source = null)
    {
        if (null === $source) {
            return $this->events;
        }
        $this->events = array_merge($this->events, $source->merge());
    }

    /**
     * Serializes the Events.
     *
     * @return string The string representation of object
     */
    public function serialize()
    {
        return serialize($this->events);
    }

    /**
     * Constructs Events.
     *
     * @param  string The string representation of object
     */
    public function unserialize($serialized)
    {
        $this->events = unserialize($serialized);
    }

    /**
     * Creates an instance of Event and triggers it.
     *
     * To trigger an event of particular class, use the trigger() method.
     *
     * @param string $eventName The event name
     * @param object $context   Optional; the event conext
     * @param array  $params    Optional; the event parameters
     *
     * @throws \InvalidArgumentException If the specified event name is not string
     *
     * @return Event
     */
    public function __invoke($eventName, $context = null, array $params = null)
    {
        if (! is_string($eventName)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid event name provided; must be an string, "%s" received.',
                is_object($eventName) ? get_class($eventName) : gettype($eventName)
            ));
        }

        $event = new Event($eventName);
        if (null !== $context) {
            $event->setContext($context);
        }
        if (null !== $params) {
            $event->setParams($params);
        }

        return $this->pullTriggers($event);
    }

    /**
     * Pull the triggers.
     *
     * @param EventInterface $event The event
     *
     * @return EventInterface
     */
    protected function pullTriggers(EventInterface $event)
    {
        $event->stopPropagation(false);

        foreach ($this->getTriggers($event) as $trigger) {
            $trigger($event);

            if ($event->propagationIsStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Gets the triggers for the currently event.
     *
     * @param EventInterface $event The event
     *
     * @return array
     */
    protected function getTriggers(EventInterface $event)
    {
        $eventName  = $event->getName();
        $eventClass = get_class($event);

        $search = [];

        if (isset($this->events[$eventName])) {
            $search[] = $this->events[$eventName];
        }
        if (isset($this->events[$eventClass])) {
            $search[] = $this->events[$eventClass];
        }

        $parent = $event;
        while ($parent = get_parent_class($parent)) {
            if (isset($this->events[$parent])) {
                $search[] = $this->events[$parent];
            }
        }

        if (empty($search)) {
            return $search;
        }

        $triggers = call_user_func_array('array_merge_recursive', $search);

        krsort($triggers);

        return call_user_func_array('array_merge', $triggers);
    }
}
