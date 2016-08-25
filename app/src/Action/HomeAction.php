<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;

class HomeAction extends  BaseAction
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'home.twig', ['api_key' =>  $this->settings['google']['api_key']]);
        return $response;
    }
}