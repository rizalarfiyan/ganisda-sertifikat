<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeController extends BaseController
{
    public function index(Request $request, Response $response, array $args = []): Response
    {
        $this->logger->info("Home page action dispatched");

        return $this->render($request, $response, 'index.twig');
    }
}
