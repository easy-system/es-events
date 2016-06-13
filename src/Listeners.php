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

use Es\Services\ServiceLocator;

/**
 * The Collection of listeners. Provides listeners on demand.
 */
class Listeners extends ServiceLocator implements ListenersInterface
{
    /**
     * The class of exception, which should be raised if the requested listener
     * is not found.
     */
    const NOT_FOUND_EXCEPTION = 'Es\Events\Exception\ListenerNotFoundException';

    /**
     * The message of exception, that thrown when unable to find the requested
     * listener.
     *
     * @var string
     */
    const NOT_FOUND_MESSAGE = 'Not found; the Listener "%s" is unknown.';

    /**
     * The message of exception, that thrown when unable to build the requested
     * listener.
     *
     * @var string
     */
    const BUILD_FAILURE_MESSAGE = 'Failed to create the Listener "%s".';

    /**
     * The message of exception, that thrown when added of invalid
     * listener specification.
     *
     * @var string
     */
    const INVALID_ARGUMENT_MESSAGE = 'Invalid specification of Listener "%s"; expects string, "%s" given.';

    /**
     * Merges with other listeners.
     *
     * @param ListenersInterface $source The data source
     *
     * @return self
     */
    public function merge(ListenersInterface $source)
    {
        $this->registry  = array_merge($this->registry, $source->getRegistry());
        $this->instances = array_merge($this->instances, $source->getInstances());

        return $this;
    }
}
