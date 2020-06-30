<?php

use App\Controllers\_Item_Controller;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\_Auth_Controller;
use App\Controllers\_User_Controller;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
	// Render index view - Typically an API welcome page or JSON message.
	return $this->renderer->render($response, 'index.phtml', $args);
});

$app->group('/api', function(){

//	$this->group('/oAuth', function(){
//		$this->post('/token',  _Auth_Controller::class.':processTokenRequest');
//	});

	// EXAMPLE OF AN AUTH PROTECTED ROUTE
	$this->group('/items', function(){
		$this->get('', _Item_Controller::class.':getAllItems');
		$this->post('', _Item_Controller::class.':addItem');
		$this->post('/{id}', _Item_Controller::class.':editItem');
		$this->get('/{id}/delete', _Item_Controller::class.':deleteItem');
	});

	// IN SLIM YOU CAN PROTECT A ROUTE BY USING ' ->add ' AS SHOWN ABOVE
	// YOU CAN ALSO PROTECT A WHOLE GROUP, OR EVEN THE WHOLE APP!

});

$app->options('/{routes:.+}', function ($request, $response, $args) {
	return $response;
});

$app->add(function ($req, $res, $next) {
	$response = $next($req, $res);
	return $response
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
	$handler = $this->notFoundHandler; // handle using the default Slim page not found handler
	return $handler($req, $res);
});