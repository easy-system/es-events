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
 * Abstract event. Inherit this class if the event name, event parameters and
 * event context must be set using event constructor and should not be changed
 * during the program.
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * The event name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The event context.
     *
     * @var object
     */
    protected $context;

    /**
     * The event parameters.
     *
     * @var array
     */
    protected $params = [];

    /**
     * The event results.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Whether or not to stop propagation.
     *
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * Gets event name.
     *
     * @return string The event name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets event context.
     *
     * @return mixed The event context
     */
    public function getContext()
    {
        return $this->context;
    }

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
    public function getParam($name, $default = null)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }

        return $default;
    }

    /**
     * Gets parameters.
     *
     * @return array The event parameters
     */
    public function getParams()
    {
        return $this->params;
    }

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
    public function setResult($name = null, $result = null)
    {
        if (null === $result) {
            $result = $name;
            array_push($this->results, $result);

            return $this;
        }

        $this->results[(string) $name] = $result;

        return $this;
    }

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
    public function getResult($name = null, $default = null)
    {
        if (null === $name) {
            return end($this->results);
        }
        if (! isset($this->results[$name])) {
            return $default;
        }

        return $this->results[$name];
    }

    /**
     * Gets all event results.
     *
     * @return array The event results
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Stops further event propagation.
     *
     * @param bool $flag Optional; by default true.
     *
     * @return AbstractEvent
     */
    public function stopPropagation($flag = true)
    {
        $this->stopPropagation = (bool) $flag;

        return $this;
    }

    /**
     * Is propagation stopped?
     *
     * @return bool
     */
    public function propagationIsStopped()
    {
        return $this->stopPropagation;
    }

    /**
     * Returns event name.
     *
     * @return string The event name
     */
    public function __toString()
    {
        return $this->getName();
    }
}
