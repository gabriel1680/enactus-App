<?php

namespace App\Controllers;

use App\Core\View;
use CoffeeCode\Router\Router;

/**
 * Classe responsável por enviar informações para renderizar a pagina de erros
 */
class ErrorController extends Controller
{
    public function __construct(?Router $router = null)
    {
        $this->router = $router;
    }

    public function index($error): void
    {
        $error = $error['errCode'];

        $error = filter_var($error, FILTER_SANITIZE_STRIPPED);

        if (!filter_var($error, FILTER_VALIDATE_INT)) {
            $error = 404;
        }

        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        echo $v->render("error", [
            'pageTitle' => "Erro",
            'errCode' => $error
        ]);
    }
}
