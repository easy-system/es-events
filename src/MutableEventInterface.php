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
 * Interface of mutable event.
 *
 * The event name, context and parameters can be changed at runtime.
 * Their change does not violate the logical integrity of the Event.
 */
interface MutableEventInterface extends EventInterface
{
    /**
     * Sets event name.
     *
     * @param string $name The event name
     *
     * @return MutableEventInterface
     */
    public function setName($name);

    /**
     * Sets event context.
     *
     * @param mixed $context The event context
     *
     * @return MutableEventInterface
     */
    public function setContext($context);

    /**
     * Sets parameters.
     *
     * @param array $params The event parameters
     *
     * @return MutableEventInterface
     */
    public function setParams(array $params);

    /**
     * Adds parameters.
     *
     * @param array $params The event parameters
     *
     * @return MutableEventInterface
     */
    public function addParams(array $params);

    /**
     * Sets parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return MutableEventInterface
     */
    public function setParam($name, $value);

    /**
     * Sets event name and optionaly context and adds parameters.
     *
     * @see Event::setName
     * @see Event::setContext
     * @see Event::addParams
     *
     * @param string $name    Event name
     * @param mixed  $context Optional; the event context
     * @param array  $params  Optional; the event parameters
     *
     * @return MutableEventInterface
     */
    public function __invoke($name, $context = null, array $params = null);
}
