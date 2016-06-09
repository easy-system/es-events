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
 * Representation of the Event.
 *
 * The event name, context and parameters must be set using a constructor.
 * These values can not be changed afterwards without breaking the logical
 * integrity of the Event.
 */
interface EventInterface
{
    /**
     * Gets event name.
     *
     * @return string The event name
     */
    public function getName();

    /**
     * Gets event context.
     *
     * @return mixed The event context
     */
    public function getContext();

    /**
     * Gets parameter.
     *
     * If the parameter does not exist, the $default value will be returned.
     *
     * @param string $name    The parameter name
     * @param mixed  $default Optional; the default value
     *
     * @return mixed The parameter value if present, $default otherwise
     */
    public function getParam($name, $default = null);

    /**
     * Gets parameters.
     *
     * @return array The event parameters
     */
    public function getParams();

    /**
     * Sets event result.
     *
     * Any listener can store in instance of event some result.
     * If the result is not named, it is added to the results stack.
     *
     * @param string $name   Optional; the name of result
     * @param mixed  $result The result value
     *
     * @return AbstractEvent
     */
    public function setResult($name = null, $result = null);

    /**
     * Gets event result.
     *
     * If the result name is not specified, returns the last result.
     * If the result with specified name does not exist, the $default value
     * will be returned.
     *
     * @param string $name    Optional; the name of result
     * @param mixed  $default Optional; the default result value
     *
     * @return mixed The result of event
     */
    public function getResult($name = null, $default = null);

    /**
     * Gets all event results.
     *
     * @return array The event results
     */
    public function getResults();

    /**
     * Stops further event propagation.
     *
     * @param bool $flag Optional; by default true.
     *
     * @return EventInterface
     */
    public function stopPropagation($flag = true);

    /**
     * Is propagation stopped?
     *
     * @return bool
     */
    public function propagationIsStopped();

    /**
     * Returns event name.
     *
     * @return string The event name
     */
    public function __toString();
}
