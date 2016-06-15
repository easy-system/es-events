<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Events\Test;

use Es\Events\Listeners;
use Es\Services\Provider;
use Es\Services\Services;

class ListenersTraitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'ListenersTraitTemplate.php';
    }

    public function testSetListeners()
    {
        $listeners = new Listeners();
        $template  = new ListenersTraitTemplate();
        $template->setListeners($listeners);
        $this->assertSame($listeners, $template->getListeners());
    }

    public function testGetListeners()
    {
        $listeners = new Listeners();
        $services  = new Services();
        $services->set('Listeners', $listeners);

        Provider::setServices($services);
        $template = new ListenersTraitTemplate();
        $this->assertSame($listeners, $template->getListeners());
    }
}
