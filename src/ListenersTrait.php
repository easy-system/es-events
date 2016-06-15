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
 * The accessors of Listeners.
 */
trait ListenersTrait
{
    /**
     * Sets the listeners.
     *
     * @param ListenersInterface $listeners The listeners
     */
    public function setListeners(ListenersInterface $listeners)
    {
        Provider::getServices()->set('Listeners', $listeners);
    }

    /**
     * Gets the listeners.
     *
     * @return ListenersInterface The listeners
     */
    public function getListeners()
    {
        return Provider::getServices()->get('Listeners');
    }
}
