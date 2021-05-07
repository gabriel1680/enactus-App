<?php

namespace App\Controllers;

use App\Model\AttendanceModel;
use App\Model\EventModel;
use App\Model\UserModel;
use CoffeeCode\Router\Router;

/**
 * Classe responsável por enviar as informações para Controller renderizar as paginas relativas a Home
 */
class Home extends Controller
{
    public function __construct(?Router $router = null)
    {
        parent::__construct($router);
    }

    /**
     * Envia as informações para a Controller renderizar a página home inicial
     *
     * @return void
     */
    public function index(): void
    {
        $user = (new UserModel())->findById(session()->id);

        $v = $this->viewObject;

        echo $v->render("home", [
            'pageTitle' => "Home",
            'eventList' => (new EventModel)->all(),
            'userId' => $user->id, //(new UserModel)->findByEmail(session()->username)->id,
            'attendanceObject' => (new AttendanceModel()),
            'sidebar' => $this->setSidebar(session()->office),
            'background' => $user->background
        ]);
    }

    public function changeBackground (): void
    {
        $images = array_diff(scandir(BACKGROUND_DIR), [".", ".."]);

        $v = $this->viewObject;

        echo $v->render("Changes/change-background", [
            'pageTitle' => "Home",
            'sidebar' => $this->setSidebar(session()->office),
            "formPostRoute" => $this->router->route("home.background"),
            "imageFiles" => $images
        ]);
    }

    public function changePassport (): void
    {
        $v = $this->viewObject;

        echo $v->render("Changes/change-passport", [
            'pageTitle' => "Home",
            'userObject' => (new UserModel()),
            'sidebar' => $this->setSidebar(session()->office)
        ]);
    }

    /**
     * Envia as informações para a Controller renderizar a pagina para alterar senha
     *
     * @return void
     */
    public function changePassword(): void
    {
        $v = $this->viewObject;

        echo $v->render("Changes/change-password", [
            "pageTitle" => "Alterar Senha",
            "sidebar" => $this->setSidebar(session()->office),
            "formPostRoute" => $this->router->route("home.changePassword")
        ]);
    }

    public function showPassport ( array $data ): void
    {
        $id = $data["id"];

        $user = new UserModel();

        if ( !$user->findById($id) ) {
            message()->error("id inválido !")->flash();
            $this->router->redirect("/home/passaportes");
            exit();
        }

        $v = $this->viewObject;

        echo $v->render("showPassport", [
            'pageTitle' => $user->name,
            'eventList' => (new EventModel)->all(),
            'userId' => $user->id, //(new UserModel)->findByEmail(session()->username)->id,
            'attendanceObject' => (new AttendanceModel()),
            'sidebar' => $this->setSidebar(session()->office),
            'background' => $user->background
        ]);

    }

    //***************************** POST *****************************


    /**
     * Rota do post enviado pelo formulário changepassword, trata os dados e manda pra Model
     *
     * @return void
     */
    public function changePasswordPost(): void
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
        $confNew = $post["nPassord"];
        $newPassword = $post["nConfPassword"];

        if ($confNew != $newPassword) {
            message()->error("as senhas não conferem")->flash();
            $this->router->redirect("home.changePassword");
        }

        $user = (new UserModel)->findByEmail(session()->username);
        $user->password = $newPassword;

        if (!$user->save()) {
            message()->error($user->message())->flash();
            $this->router->redirect("home.changePassword");
        }

        message()->success("Senha alterada com sucesso !")->flash();
        $this->router->redirect("home.changePassword");
    }

    public function changeBackgroundPost (): void
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        if (!file_exists(BACKGROUND_DIR . "/{$post->background}")) {
            echo message()->error("Imagem não encontrada!")->json();
            exit();
        }

        $user = (new UserModel())->findById(session()->id);
        $user->background = $post->background;

        if (!$user->save()) {
            echo message()->error($user->message())->json();
            exit();
        }

        echo message()->success("Plano de fundo alterado!")->json();
        exit();
    }

    /**
     * Handles de data sent by home page on attendance request
     *
     * @return void
     */
    public function requestPost(array $data): void
    {
//        $postData = json_decode(file_get_contents("php://input"));
        $postData = (object)$data;

        $attendance = new AttendanceModel();
        $attendance->findAttendance($postData->id, session()->id);

        if (!$attendance) {
            echo message()->error("Presença não encontrada !")->json();
            exit();
        }

        $attendance->requested = 1;
        $attendance->requested_at = date_frmt_app();

        if (!$attendance->save()) {
            echo message()->error("Erro ao completar requisição !")->json();
            exit();
        }

        echo message()->success("Solicitação enviada com sucesso !")->json();
        exit();
    }
}
