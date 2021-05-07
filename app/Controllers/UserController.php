<?php

namespace App\Controllers;

use App\Support\Pager;
use App\Model\UserModel;
use CoffeeCode\Router\Router;

/**
 * Classe responsável por enviar as informações para Controller renderizar as paginas relativas a usuários
 */
class UserController extends Controller
{

    /**
     * UserController constructor.
     * @param Router|null $router
     */
    public function __construct(?Router $router = null)
    {
        parent::__construct($router);

        $this->officeValidation();
    }

    /**
     * Envia as informações para Controller renderizar o template de crud dos usuários
     *
     * @param array $feedback
     * @return void
     */
    public function index(array $data): void
    {
        $page = (isset($data["page"]) ? filter_var($data["page"], FILTER_SANITIZE_STRIPPED) : "1");

        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            $this->router->redirect("/usuarios/1");
            die();
        }

        $pager = new Pager(url("/usuarios/"));
        $pager->setPager(user()->count(), (int)$page);

        $v = $this->viewObject;

        echo $v->render("crudView", [
            "pageTitle" => "Usuários",
            "sidebar" => "gt",
            "crudTitle" => "CONTROLE DE MEMBROS",
            "addNewUrlPath" => url("/usuarios/cadastrar"),
            "addButtonText" => "Adicionar",
            "tableName" => "userTable",
            "modelObject" => (new UserModel),
            "pager" => $pager,
            "limit" => $pager->limit(),
            "offset" => $pager->offset(),
            "page" => $page
        ]);
    }

    /**
     * Envia as informações para Controller renderizar o template de cadastro
     *
     * @param array $feedback
     * @return void
     */
    public function register(): void
    {
        $v = $this->viewObject;

        echo $v->render("Forms::userForm", [
            "pageTitle" => "Cadastrar",
            "sidebar" => "gt",
            "formPostRoute" => url("/usuarios/cadastrar"),
            "formTitle" => "Cadastrar"
        ]);
    }

    /**
     * Exclui o usuário (id enviado através da classe router no indice definido na index)
     *
     * @param array $id
     * @return void
     */
    public function exclude(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $data = (object)$data;

        $user = (new UserModel);

        if (!$user->findById($data->id)) {
            echo message()->error("Usuário não encontrado!")->json();
            exit();
        }

        if (!$user->destroy()) {
            echo message()->error($user->message())->json();
            exit();
        }

        echo message()->success("Usuário excluido com sucesso!")->json();
        exit();
    }

    /**
     * Envia as informações para Controller renderizar o template de edição (id enviado através da classe router no indice definido na index)
     *
     * @param array $id
     * @return void
     */
    public function edit(array $data): void
    {
        $id = $data["id"];

        $v = $this->viewObject;

        echo $v->render("Forms::userForm", [
            "pageTitle" => "Editar",
            "sidebar" => "gt",
            "formPostRoute" => url("/usuarios/editar"),
            "formTitle" => "Editar",
            "user" => (new UserModel)->findById($id)
        ]);
    }

    /**
     * Rota do post enviado pelo formulário UserForm para cadastro, trata os dados e manda pra Model
     *
     * @return void
     */
    public function addNew(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        $user = (new UserModel);

        $user->email = $post->email;
        $user->name = $post->name;
        $user->password = setPasswordByEmail($user->email);
        $user->office = $post->office;

        if (!$user->save()) {
            echo message()->error($user->message())->json();
            exit();
        }

        if (!updateAttendances(null, $user)) {
            echo message()->error("Ops ! Parece que algo deu errado...")->json();
            exit();
        }

        echo message()->success("Usuário cadastrado com sucesso!")->json();
        exit();
    }

    /**
     * Rota do post enviado pelo formulário UserForm para editar, trata os dados e manda pra Model
     *
     * @return void
     */
    public function editPost(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        $user = (new UserModel)->findById($post->id);
        $user->name = $post->name;
        $user->office = $post->office;

        if (!$user->save()) {
            echo message()->error($user->message())->json();
            exit();
        }

        message()->success("Usuário alterado com sucesso!")->flash();
        echo message()->urlJson(url("/usuarios"));
    }
}
