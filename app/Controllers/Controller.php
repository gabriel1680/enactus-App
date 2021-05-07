<?php

namespace App\Controllers;

use App\Core\View;
use CoffeeCode\Router\Router;

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller
{
    /**
     * @var View
     */
    protected $viewObject;
    /**
     * @var Router|null
     */
    public $router;

    /**
     * Controller constructor.
     * @param Router|null $router
     */
    public function __construct(?Router $router = null)
    {
        session_start();

        $this->viewObject = new View();
        $this->viewObject->path("View", __DIR__ . "/../View");
        $this->viewObject->engine()->addFolder("Shared", __DIR__ . "/../View/Shared");
        $this->viewObject->engine()->addFolder("Assets", __DIR__ . "/../View/Assets");
        $this->viewObject->engine()->addFolder("Forms", __DIR__ . "/../View/Forms");

        //verifica se a sessão foi setada se não envia a rota para O LOGIN
        if (!isset($_SESSION["username"])) {
            header("Location: " . CONF_URL_BASE . "/login");
            die;
        }
        $this->router = $router;
    }

    /**
     * @return void
     */
    protected function officeValidation(): void
    {
        if (session()->office != ALLOWED_OFFICE) { //se não for gt
                $this->router->redirect("home.index");
        }
    }

    protected function setSidebar ( string $userOffice ): string
    {
        if (session()->office == ALLOWED_OFFICE) {
            return "gt";
        } else {
             return "default";
        }
    }
}
