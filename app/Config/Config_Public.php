<?php

// Arquivo de configurações da aplicação

/**
 * GLOBAL
 */

$DEPLOY = false;

/**
 * URL
 */

//CONFIGURAÇÕES EM AMBIENTE DE PRODUÇÃO VS DEPLOY
if ($DEPLOY) {

    define('MAIN_URL', '');

} else {

    define('MAIN_URL', '/enactuspassport');

}

define('CONF_URL_BASE', 'https://localhost/enactuspassport');

//diretório do site
define('SITE', 'enactuspassport');

//Dados do banco de dados usados no arquivo connection da camada Model

/**
 * DATABASE
 */

define("DB_PROPERTIES", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "",
    "username" => "",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);


/**
 * EMAIL
 */

define("CONF_MAIL", [
    "charset" => "utf-8",
    "language" => "br",
    "host" => "",
    "port" => "",
    "username" => "",
    "password" => "",
    "sender" => [
        "name" => "Suporte EnactusPassport",
        "address" => ""
    ],
    "isHTML" => true,
    "SMTPAuth" => true,
    "SMTPSecure" => "tls"
]);

/**
 * DIRETÓRIOS
 */

function theme(string $path): string
{
    return CONF_PUBLIC_FOLDER . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
}

define("CONF_PUBLIC_FOLDER", MAIN_URL. "/public");

define("PUBLIC_FOLDER", MAIN_URL. "/public");

//diretório do aplicativo
define("APP_PATH", __DIR__ . "/../");

define("IMG_FOLDER", PUBLIC_FOLDER . "/img");
define("BACKGROUND_FOLDER", IMG_FOLDER . "/background");
define("BACKGROUND_DIR", __DIR__ . "/../../public/img/background");

define("STYLE_DIR", PUBLIC_FOLDER. "/style/");
define("SCRIPT_DIR", PUBLIC_FOLDER . "/js/");

/**
 * APP
 */
//cargo administrador
define("ALLOWED_OFFICE", "gestão de talentos");

/**
 * PAGINATOR
 */
define("CONF_PAGINATOR_LIMIT", 10);
define("CONF_PAGINATOR_OFFSET", 2);

/**
 * DATE
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * SESSION
 */
define("CONF_SES_PATH", __DIR__ . "/../../storage/sessions");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_COST", ["cost" => 10]);


/**
 * MESSAGE
 */
define("CONF_MESSAGE_CLASS", "trigger");
define("CONF_MESSAGE_INFO", "info");
define("CONF_MESSAGE_WARNING", "warning");
define("CONF_MESSAGE_SUCCESS", "success");
define("CONF_MESSAGE_ERROR", "error");
define("CONF_MESSAGE_INFO_ICON", "icon-info");
define("CONF_MESSAGE_WARNING_ICON", "icon-notification");
define("CONF_MESSAGE_SUCCESS_ICON", "icon-checkmark");
define("CONF_MESSAGE_ERROR_ICON", "icon-cancel-circle");


/**
 * VIEW
 */

define("CONF_VIEW_PATH", __DIR__ . "/../View");
define("CONF_VIEW_EXT", "php");

/**
 * MODEL LAYER
 */
define("CONF_MODEL_LIMIT", 100000);
define("CONF_MODEL_OFFSET", 0);
define("CONF_MODEL_EVENT_OREDER_BY", "name, date");
define("CONF_MODEL_USER_OREDER_BY", "name");
define("CONF_MODEL_ATTENDANCE_OREDER_BY", "requested");
define("CONF_MODEL_OREDER_EVENT_DIRECTION", "DESC");
define("CONF_MODEL_OREDER_USER_DIRECTION", "ASC");
define("CONF_MODEL_OREDER_ATTENDANCE_DIRECTION", "DESC");