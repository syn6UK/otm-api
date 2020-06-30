<?php

namespace App\Controllers;

use App\Models\Item;
use App\Modules\SocketIO;
use Exception;
use Psr\Log\LoggerInterface;
use App\Models\Users\User as User;
use Slim\Http\Request;
use Slim\Http\Response;

class _Item_Controller{

	public $table;
	public $socket;

	public function __construct($logger, $db) {
		$this->table = $db;
		$this->logger = $logger;
		$this->socket = new SocketIO('localhost', 3000);
	}

	public function deleteItem(Request $request, Response $response, $args){

        $destroy = Item::destroy($args['id']);

        // TELL THE OTHER CLIENTS IT HAS UPDATED
        $this->socket->emit('deleted', $args['id']);

        return $response->withJson((object)[
            "deleted" => intval($args['id'])
        ], 200);

	}

	public function getAllItems(Request $request, Response $response, $args){

        $items = Item::all();

        return $response->withJson($items, 200);

	}

	public function addItem(Request $request, Response $response, $args){

	    $item = Item::create($request->getParsedBody());

	    return $response->withJson($item, 200);

    }


	public function editItem(Request $request, Response $response, $args){

        $item = Item::find($args['id']);

        foreach($request->getParsedBody() as $key => $value){
            $item->{$key} = $value;
        }

        // TELL THE OTHER CLIENTS THERE IS AN UPDATE TO AN ITEM
        $this->socket->emit( 'edited', $item);

        $item->save();

        return $response->withJson($item, 200);

	}


}