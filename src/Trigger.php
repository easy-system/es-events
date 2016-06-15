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

use RuntimeException;

/**
 * The trigger of listeners.
 */
class Trigger implements TriggerInterface
{
    use ListenersTrait;

    /**
     * The listener name.
     *
     * @var string
     */
    protected $listener = '';

    /**
     * The method of listener.
     *
     * @var string
     */
    protected $method = '';

    /**
     * Constructor.
     *
     * @param string $listener The listener name
     * @param string $method   The listener method name
     */
    public function __construct($listener, $method)
    {
        $this->setListener($listener);
        $this->setMethod($method);
    }

    /**
     * Sets the listener name.
     *
     * @param string $name The listener name
     *
     * @return Trigger
     */
    public function setListener($name)
    {
        $this->listener = (string) $name;

        return $this;
    }

    /**
     * Gets the listener name.
     *
     * @return string The listener name
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * Sets the method of listener.
     *
     * @param string $name The method name
     *
     * @return Trigger
     */
    public function setMethod($name)
    {
        $this->method = (string) $name;

        return $this;
    }

    /**
     * Gets the method of listener.
     *
     * @return string The method name
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Trigger listener.
     *
     * @param EventInterface $event The event
     *
     * @throws \RuntimeException If method of listener is not callable
     */
    public function __invoke(EventInterface $event)
    {
        $listeners = static::getListeners();
        $listener  = $listeners->get($this->listener);
        if (! is_callable([$listener, $this->method])) {
            throw new RuntimeException(
                sprintf(
                    'The method "%s" of listener "%s" is not callable.',
                    $this->method,
                    $this->listener
                )
            );
        }
        $listener->{$this->method}($event);
    }

    /**
     * Serializes the trigger.
     *
     * @return string The string representation of object
     */
    public function serialize()
    {
        return serialize([
            $this->listener,
            $this->method,
        ]);
    }

    /**
     * Constructs trigger.
     *
     * @param  string The string representation of object
     */
    public function unserialize($serialized)
    {
        list(
            $this->listener,
            $this->method
        ) = unserialize($serialized);
    }
}
