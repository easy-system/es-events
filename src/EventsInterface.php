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

/**
 * Interface for event manager.
 */
interface EventsInterface extends \Serializable
{
    /**
     * Attachs an trigger to the specified event.
     *
     * @param TriggerInterface $trigger          The event trigger
     * @param string           $eventNameOrClass The event name or class of event
     * @param int              $priority         Optional; by default 1. The event priority
     *
     * @return EventsInterface
     */
    public function attachTrigger(TriggerInterface $trigger, $eventNameOrClass, $priority = 1);

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
    public function attach($eventNameOrClass, $listenerName, $listenerMethod, $priority = 1);

    /**
     * Detaches trigger from the specified event.
     *
     * @param TriggerInterface $trigger          The event trigger
     * @param string           $eventNameOrClass The event name or class of event
     *
     * @return Events
     */
    public function detachTrigger(TriggerInterface $trigger, $eventNameOrClass);

    /**
     * Clears all triggers for a given event.
     *
     * @param string $eventNameOrClass The event name or class of event
     *
     * @return Events
     */
    public function clearTriggers($eventNameOrClass);

    /**
     * Triggers an event.
     *
     * @param EventInterface $event The instance of EventInterface
     *
     * @return Events
     */
    public function trigger(EventInterface $event);

    /**
     * Merges with other events.
     *
     * @param EventsInterface $source The data source
     *
     * @return null|array If the source was passed returns
     *                    null, source data otherwise
     */
    public function merge(EventsInterface $source = null);

    /**
     * Creates an instance of Event and triggers it.
     *
     * To trigger an event of particular class, use the trigger() method.
     *
     * @param string $eventName The event name
     * @param object $context   Optional; the event conext
     * @param array  $params    Optional; the event parameters
     *
     * @return Event
     */
    public function __invoke($eventName, $context = null, array $params = null);
}
