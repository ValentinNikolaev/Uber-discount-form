<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class HomeAction
{
    private $view;
    private $logger;
    private $viewParams = [];

    public function __construct(Twig $view, LoggerInterface $logger, $app)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->viewParams = ['api_key' => $app->get('settings')['google']['api_key']];
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        $this->view->render($response, 'home.twig', $this->viewParams);
        return $response;
    }
}