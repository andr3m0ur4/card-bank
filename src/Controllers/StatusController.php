<?php

namespace CardCollection\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController
{
    public function showStatus(): JsonResponse
    {
        return new JsonResponse(['status' => 'O André Moura é um bom dev PHP! 🚀']);
    }
}
