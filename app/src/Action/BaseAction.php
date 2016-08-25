<?php

namespace App\Action;


use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

class BaseAction
{
    protected $view;
    protected $logger;
    protected $settings = [];

    public function __construct(Twig $view, LoggerInterface $logger, $app)
    {
        $this->settings = $app->get('settings');
        $this->view = $view;
        $this->logger = $logger;
    }
}