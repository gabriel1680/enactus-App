<?php

namespace App\Controllers;

use App\Support\Pager;
use App\Model\EventModel;
use CoffeeCode\Router\Router;

/**
 *  Classe responsável por enviar as informações para Controller renderizar as paginas relativas a eventos
 */
class EventController extends Controller
{

    public function __construct(?Router $router = null)
    {
        parent::__construct($router);

        $this->officeValidation();
    }

    /**
     * Envia as informações para Controller renderizar o template de crud dos eventos
     *
     * @param array $feedback
     * @return void
     */
    public function index(array $data): void
    {
        $page = (isset($data["page"]) ? filter_var($data["page"], FILTER_SANITIZE_STRIPPED) : "1");

        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            $this->router->redirect("/eventos/1");
            die();
        }

        $pager = new Pager(url("/eventos/"));
        $pager->setPager(event()->count(), (int)$page);

        $v = $this->viewObject;

        echo $v->render("crudView", [
            "pageTitle" => "Eventos",
            "sidebar" => "gt",
            "crudTitle" => "CONTROLE DE EVENTOS",
            "addNewUrlPath" => url("/eventos/cadastrar"),
            "addButtonText" => "Novo Evento",
            "tableName" => "eventTable",
            "modelObject" => (new EventModel),
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

        echo $v->render("Forms::eventForm", [
            "pageTitle" => "Cadastrar",
            "sidebar" => "gt",
            "formPostRoute" => url("/eventos/cadastrar"),
            "formTitle" => "Novo Evento"
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

        $event = (new EventModel);

        if (!$event->findById($data->id)) {
            echo message()->error("Evento não encontrado! {$data->id}")->json();
            exit();
        }

        if (!$event->destroy()) {
            echo message()->error($event->message())->json();
            exit();
        }

        echo message()->success("Evento excluido com sucesso!")->json();
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

        echo $v->render("Forms::eventForm", [
            "pageTitle" => "Editar",
            "sidebar" => "gt",
            "formPostRoute" => url("/eventos/editar"),
            "formTitle" => "Editar",
            "event" => (new EventModel)->findById($id)
        ]);
    }

    /**
     * Rota do post enviado pelo formulário EventForm para cadastro, trata os dados e manda pra Model
     *
     * @return void
     */
    public function addNew(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        $event = (new EventModel)->bootstrap(
            $post->type,
            $post->event,
            $post->mandatory,
            $post->local,
            $post->datetime,
            $post->description
        );

        if (!$event->save()) {
            echo message()->error($event->message())->json();
            exit();
        }

        if (!updateAttendances($event, null)) {
            echo message()->error("Ops ! Parece que algo deu errado...")->json();
            exit();
        }

        echo message()->success("Evento cadastrado com sucesso!")->json();
        exit();
    }

    /**
     * Rota do post enviado pelo formulário EventForm para editar, trata os dados e manda pra Model
     *
     * @return void
     */
    public function editPost(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        $event = (new EventModel)->findById($post->id);
        $event->type = $post->type;
        $event->local = $post->local;
        $event->date = $post->datetime;
        $event->mandatory = $post->mandatory;
        $event->description = $post->description;

        if (!$event->save()) {
            echo message()->error($event->message())->json();
            exit();
        }

        message()->success("Evento alterado com sucesso!")->flash();
        echo message()->urlJson(url("/eventos"));
        exit();
    }
}
