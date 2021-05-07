<?php

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$r = new Router(CONF_URL_BASE);

$r->namespace("App\Controllers");

/**
 * NULL
 * home->index
 */
$r->group(null);
$r->get("/", "Home:index");
$r->post("/", "Home:requestPost");
$r->post("/home", "Home:requestPost");
$r->get("/sobre", "Login:about", "about.index");

/**
 * HOME
 */
$r->group("home");
$r->get("/", "Home:index", "home.index");

//Background
$r->get("/background", "Home:changeBackground", "home.background");
$r->post("/background", "Home:changeBackgroundPost");

// Passaportes
$r->get("/passaportes", "Home:changePassport", "home.changePassport");
//$r->post("/passaportes", "Home:changePassport");
$r->get("/passaportes/{id}", "Home:showPassport");

// Alterar Senha
$r->get("/alterar-senha", "Home:changePassword", "home.changePassword");
$r->post("/alterar-senha", "Home:changePasswordPost");

/**
 * LOGIN
 */
$r->group("login");
$r->get("/", "Login:index", "login.index");
$r->post("/", "Auth:login");
$r->get("/{erro}", "Login:index");

/**
 * PRESENCA
 */
$r->group("presenca");
$r->get("/{page}", "AttendanceController:index", "attendance.index");
$r->get("/", "AttendanceController:index", "attendance.index");


$r->get("/alterar", "AttendanceController:alter", "attendance.alter");
$r->post("/alterar", "AttendanceController:postAlter");

$r->get("/editar/{id}", "AttendanceController:edit");
$r->post("/editar/{id}", "AttendanceController:postEdit");

/**
 * USUARIOS
 */
$r->group("usuarios");
$r->get("/{page}", "UserController:index", "user.index");
$r->get("/", "UserController:index", "user.index");

$r->post("/{page}", "UserController:exclude");
$r->post("/", "UserController:exclude");

$r->get("/cadastrar", "UserController:register", "user.register");
$r->post("/cadastrar", "UserController:addNew");

$r->get("/editar/{id}", "UserController:edit", "user.edit");
$r->post("/editar/{id}", "UserController:editPost");


/**
 * EVENTOS
 */
$r->group("eventos");
$r->get("/{page}", "EventController:index", "event.index");
$r->get("/", "EventController:index", "event.index");

$r->post("/{page}", "EventController:exclude");
$r->post("/", "EventController:exclude");

$r->get("/cadastrar", "EventController:register", "event.register");
$r->post("/cadastrar", "EventController:addNew");

$r->post("/editar/{id}", "EventController:editPost");
$r->get("/editar/{id}", "EventController:edit");

/**
 * LOGOUT
 */
$r->group(null);
$r->get("/logout", "Login:logout", "login.logout");

/**
 ********************************* RECOVERY *******************************************
 */
$r->group(null);
$r->get("/forgot", "Login:forgot", "login.forgot");
$r->post("/forgot", "Auth:passwordRecovery");
$r->get("/senha/{email}/{forgot}", "Login:recovery", "login.recovery");
$r->post("/senha/{email}/{forgot}", "Auth:passwordAuth");

/**
 * ERRO
 */
$r->group("erro")->namespace("App\Controllers");
$r->get("/{errCode}", "ErrorController:index");


$r->dispatch();

if ($r->error()) {
    $r->redirect("/erro/{$r->error()}");
}
