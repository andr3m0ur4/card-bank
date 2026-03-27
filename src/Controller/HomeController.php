<?php

namespace CardCollection\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return new Response('Welcome to the Card Collection Application! 😊');
    }
}
