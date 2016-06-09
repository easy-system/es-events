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
 * Trigger interface.
 */
interface TriggerInterface extends \Serializable
{
    /**
     * Sets the listener name.
     *
     * @param string $name The listener name
     *
     * @return TriggerInterface
     */
    public function setListener($name);

    /**
     * Gets the listener name.
     *
     * @return string The listener name
     */
    public function getListener();

    /**
     * Sets the method of listener.
     *
     * @param string $name The method name
     *
     * @return TriggerInterface
     */
    public function setMethod($name);

    /**
     * Sets the method of listener.
     *
     * @return string The method name
     */
    public function getMethod();

    /**
     * Trigger listener.
     *
     * @param EventInterface $event The event
     *
     * @throws \RuntimeException If method of listener is not callable
     */
    public function __invoke(EventInterface $event);
}
