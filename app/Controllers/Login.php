<?php

namespace App\Controllers;

use App\Core\Mail;
use App\Core\View;
use App\Model\UserModel;
use CoffeeCode\Router\Router;

/**
 * Classe responsável por enviar as informações para Controller renderizar as paginas relativas a login
 */
class Login extends Controller
{
    /**
     * Login constructor.
     * @param Router|null $router
     */
    public function __construct(?Router $router = null)
    {
        $this->router = $router;
    }

    /**
     * Envia as informações para Controller renderizar a tela de login
     *
     * @return void
     */
    public function index(): void
    {
        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        echo $v->render("login", [
            'pageTitle' => "Login",
            'formPostUri' => url("/login"),
            'aboutPath' => MAIN_URL . "/sobre"
        ]);
    }

    /**
     * Envia as informações para Controller renderizar a tela de sobre
     *
     * @return void
     */
    public function about(): void
    {
        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        echo $v->render("about", [
            'pageTitle' => "Sobre",
        ]);
    }

    /**
     * @return void
     */
    public function forgot(): void
    {
        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        echo $v->render("forgot", [
            'pageTitle' => "Recuperação de Senha",
            "formPostRoute" => $this->router->route("login.forgot")
        ]);
    }

    /**
     * @param array $urlParameters
     * @return void
     */
    public function recovery(array $urlParameters): void
    {
        $urlParameters = (object)$urlParameters;

        $user = new UserModel;

        if (!isEmail($urlParameters->email)) {
            message()->error("O e-mail informado não é válido !")->flash();
            $this->router->redirect("login.index");
            die;
        }

        if (!$user->findByEmail($urlParameters->email)) {
            message()->error("Ops... Algo deu errado com a verificação do seu email")->flash();
            $this->router->redirect("login.index");
            die;
        }

        if ($user->forgot != $urlParameters->forgot) {
            message()->error("Este link não é mais válido")->flash();
            $this->router->redirect("erro/505");
            die;
        }

        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        echo $v->render("Auth/recovery-auth", [
            'pageTitle' => "Recuperação de Senha",
            "formPostRoute" => $this->router->route(
                "login.recovery",
                [
                    "email" => $urlParameters->email,
                    "forgot" => $urlParameters->forgot
                ]
            )
        ]);
    }

    /**************************************************** POST *************************************** */

    /**
     * @return void
     */
    public function logout(): void
    {
        session()->unset("username")->unset("office");
        $this->router->redirect("login.index");
    }
}
