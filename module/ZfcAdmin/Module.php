<?php
/**
 * Copyright (c) 2012 Jurian Sluiman.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     ZfcAdmin
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Jurian Sluiman.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://zf-commons.github.com
 */

namespace ZfcAdmin;

use Zend\ModuleManager\Feature;
use Zend\Loader;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ModuleManager\ModuleManager;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Module class for ZfcAdmin
 *
 * @package ZfcAdmin
 */
class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\BootstrapListenerInterface
{
    /**
     * @{inheritdoc}
     */
	  protected $whitelist = array('/admin','/admin/index','/admin/login','/admin/upload-images','/admin/packages-list');
    public function getAutoloaderConfig()
    {
        return array(
			'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            Loader\AutoloaderFactory::STANDARD_AUTOLOADER => array(
                Loader\StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @{inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @{inheritdoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'admin_navigation' => 'ZfcAdmin\Navigation\Service\AdminNavigationFactory',
            	'ZfcAdmin\Model\AdminReportsFactory'=>'ZfcAdmin\Factory\Model\AdminReportsTableFactory'			
            ),
        );
    }

    /**
     * @{inheritdoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $em  = $app->getEventManager();
		 $eventManager        = $e->getApplication()->getEventManager();
        //$result=$eventManager->attach('route', array($this, 'loadConfigurations'), 2);
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));
		$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute1'));
    }
	 public function loadConfigurations(MvcEvent $e)
    {
        $application   = $e->getApplication();
		$sm            = $application->getServiceManager();
		$sharedManager = $application->getEventManager()->getSharedManager();
     
        $router = $sm->get('router');
    $request = $sm->get('request');
	$list = $this->whitelist;
      if(!in_array($request->getrequestUri(),$list))
	 {
		$matchedRoute = $router->match($request);
		if (null !== $matchedRoute) {
			   $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
					function($e) use ($sm) {
			   $sm->get('ControllerPluginManager')->get('Adminplugin')
						  ->doAuthorization($e); //pass to the plugin...   
			   },2
			   );
			}
		}
		
    }
    /**
     * Select the admin layout based on route name
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function selectLayoutBasedOnRoute(MvcEvent $e)
    {
        $app    = $e->getParam('application');
        $sm     = $app->getServiceManager();
        $config = $sm->get('config');
	
        if (false === $config['zfcadmin']['use_admin_layout']) {
            return;
        }

        $match      = $e->getRouteMatch();
        $controller = $e->getTarget();
        if (!$match instanceof RouteMatch
            || 0 !== strpos($match->getMatchedRouteName(), 'zfcadmin')
            || $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }

        $layout     = $config['zfcadmin']['admin_layout_template'];
        $controller->layout($layout);
    }
	 public function selectLayoutBasedOnRoute1(MvcEvent $e)
    {
        $app    = $e->getParam('application');
        $sm     = $app->getServiceManager();
        $config = $sm->get('config');
	
        if (false === $config['myadmin']['use_admin_layout']) {
            return;
        }

        $match      = $e->getRouteMatch();
        $controller = $e->getTarget();
        if (!$match instanceof RouteMatch
            || 0 !== strpos($match->getMatchedRouteName(), 'myadmin')
            || $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }

        $layout     = $config['myadmin']['admin_layout_template'];
        $controller->layout($layout);
    }
}
