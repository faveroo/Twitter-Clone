<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'index'
		);
		
		$routes['subscribe'] = array(
			'route' => '/subscribe',
			'controller' => 'IndexController',
			'action' => 'subscribe'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'IndexController',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);
		
		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);


		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);

		$routes['remover'] = array(
			'route' => '/remover',
			'controller' => 'AppController',
			'action' => 'remover'
		);
		
		$routes['wfollow'] = array(
			'route' => '/wfollow',
			'controller' => 'AppController',
			'action' => 'wfollow'
		);

		$this->setRoutes($routes);
	}

}

?>