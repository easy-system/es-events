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
 * The mutable event.
 *
 * The event name, context and parameters can be changed at
 * runtime. Their change does not violate the logical integrity of the Event.
 * Encapsulates the target context, parameters passed and results of signal
 * handlers if they have provided results.
 */
class Event extends AbstractEvent implements MutableEventInterface
{
    /**
     * Constructor.
     *
     * @param string $name    Optional; the event name
     * @param object $context Optional; the event context
     * @param array  $params  Optional; the event parameters
     */
    public function __construct($name = null, $context = null, array $params = null)
    {
        if (null !== $name) {
            $this->setName($name);
        }
        if (null !== $context) {
            $this->setContext($context);
        }
        if (null !== $params) {
            $this->setParams($params);
        }
    }

    /**
     * Sets event name.
     *
     * @param string $name The event name
     *
     * @return Event
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * Sets event context.
     *
     * @param mixed $context The event context
     *
     * @return Event
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Sets parameters.
     *
     * @param array $params The event parameters
     *
     * @return Event
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Adds parameters.
     *
     * @param array $params The event parameters
     *
     * @return Event
     */
    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Sets parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return Event
     */
    public function setParam($name, $value)
    {
        $this->params[(string) $name] = $value;

        return $this;
    }

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
     * @return Event
     */
    public function __invoke($name, $context = null, array $params = null)
    {
        $this->setName($name);

        if (null !== $context) {
            $this->setContext($context);
        }
        if (null !== $params) {
            $this->addParams($params);
        }

        return $this;
    }
}
