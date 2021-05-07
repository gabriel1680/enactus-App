<?php

namespace App\Controllers;

use CoffeeCode\Router\Router;
use App\Model\AttendanceModel;
use App\Model\EventModel;
use App\Model\UserModel;
use App\Support\Pager;
use http\Env\Request;

/**
 * Classe responsável por enviar as informações para Controller renderizar as paginas relativas a presença
 */
class AttendanceController extends Controller
{
    /**
     * AttendanceController constructor.
     *
     * @param Router|null $router
     */
    public function __construct(?Router $router = null)
    {
        parent::__construct($router);

        $this->officeValidation();
    }

    /**
     * Envia as informações para Controller renderizar o template de crud dos presença
     *
     * @param array $data
     * @return void
     */
    public function index(array $data): void
    {
        $page = (isset($data["page"]) ? filter_var($data["page"], FILTER_SANITIZE_STRIPPED) : "1");

        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            $this->router->redirect("/presenca/1");
            die();
        }

        $pager = new Pager(url("/presenca/"));
        $pager->setPager(attendance()->count(), (int)$page);

        $v = $this->viewObject;

        echo $v->render("crudView", [
            "pageTitle" => "Presença",
            "sidebar" => "gt",
            "crudTitle" => "CONTROLE DE PRESENÇA",
            "addNewUrlPath" => url("/presenca/alterar"),
            "addButtonText" => "Alterar",
            "tableName" => "attendanceTable",
            "modelObject" => (new AttendanceModel),
            "pager" => $pager,
            "limit" => $pager->limit(),
            "offset" => $pager->offset(),
            "page" => $page
        ]);
    }

    /**
     * Envia as informações para Controller renderizar o template de alteração
     *
     * @param array $data
     * @return void
     */
    public function alter(): void
    {
        $v = $this->viewObject;

        echo $v->render("Forms::attendanceForm", [
            "pageTitle" => "Alterar",
            "sidebar" => "gt",
            "formPostRoute" => url("/presenca/alterar"),
            "formTitle" => "Alterar"
        ]);
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

        $attendance= new AttendanceModel();

        if (!filter_var($id, FILTER_VALIDATE_INT) || !$attendance->findById($id)){
            $this->router->redirect("/erro/405");
            exit();
        }

        $v = $this->viewObject;

        $event = (new EventModel)->findById($attendance->event_id);
        $user = (new UserModel)->findById($attendance->user_id);

        echo $v->render("Forms::attendanceForm", [
            "pageTitle" => "Editar",
            "sidebar" => "gt",
            "formPostRoute" => url("/presenca/editar"),
            "formTitle" => "Editar",
            "event" => $event,
            "user" => $user,
            "attendance" => $attendance->attendance
        ]);
    }

    /**
     * Rota do post enviado pelo formulário AttendanceForm para alteração, trata os dados e manda pra Model
     *
     * @return void
     */
    public function postAlter(): void
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
        $post = (object)$post;

        $attendance = (new AttendanceModel)->findAttendance($post->eventId, $post->userId);
        $attendance->attendance = $post->attendance;

        if (!$attendance->save()) {
            echo message()->error($attendance->message())->json();
            die();
        } else {
            echo message()->success("Presença alterada com sucesso !")->json();
            die();
        }
    }

    /**
     * Handles data sent by js page on edit screen
     *
     * @return void
     */
    public function postEdit(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $data = (object)$data;

        $attendance = (new AttendanceModel)->findById($data->id);

        $attendance->attendance = $data->attendance;

        if (!$attendance->save()) {
            echo message()->error($attendance->message())->json();
            exit();
        } else {
            message()->success("Presença alterada com sucesso !")->flash();
            echo message()->urlJson(url("/presenca"));
            exit();
        }
    }
}
