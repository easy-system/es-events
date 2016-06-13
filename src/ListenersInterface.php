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

use Es\Services\ServiceLocatorInterface;

/**
 * The interface for the Collection of listeners.
 */
interface ListenersInterface extends ServiceLocatorInterface
{
    /**
     * Merges with other listeners.
     *
     * @param ListenersInterface $source The data source
     *
     * @return self
     */
    public function merge(ListenersInterface $source);
}
