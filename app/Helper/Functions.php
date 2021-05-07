<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

use App\Model\DataBase\Connection;
use App\Model\EventModel;
use App\Model\UserModel;

/**
 * Verify an string by the parameters of config file
 *
 * @param string $string
 * @return boolean
 */
function is_passwd(string $string): bool
{
    if (password_get_info($string)["algo"]) {
        return true;
    }
    return (mb_strlen($string) >= CONF_PASSWD_MIN_LEN && mb_strlen($string) <= CONF_PASSWD_MAX_LEN ? true : false);
}

/**
 * @param string $password
 * @return void
 */
function  passwd(string $password): string
{
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_COST);
}

/**
 * @param string $password
 * @param string $hash
 * @return boolean
 */
function passwd_verfiy(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return boolean
 */
function passwd_needs_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_COST);
}

/**
 * @return string
 */
function csrf_input(): string
{
    (new App\Core\Session)->csrf();
    return "<input type = 'hidden' name = 'csrf' value = '" . ($_SESSION["csrf_token"] ?? "") . "'/>";
}

/**
 * @param [type] $request
 * @return boolean
 */
function csrf_verify($request): bool
{
    if (empty($_SESSION["csrf_token"]) || empty($request['csrf']) || $request['csrf'] != $_SESSION["csrf_token"]) {
        return false;
    } else {
        return true;
    }
}

function isEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isRa(string $ra): bool
{
    if (strlen($ra) != 10) {
        return false;
    }

    if (!strstr($ra, ".") || !strstr($ra, "-")) {
        return false;
    }

    if (strpos($ra, ".") != 2 || strpos($ra, "-") != 8) {
        return false;
    }

    return true;
}

function isMauaDomain(string $string): bool
{
    return ($string === "maua.br");
}

function isMauaEmail(string $email): bool
{
    if (isEmail($email)) {
        $emailParsed = explode("@", $email);

        return (!isRa($emailParsed[0]) || !isMauaDomain($emailParsed[1]) ? false : true);
    } else {
        return false;
    }
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

function str_trim(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $trim = str_replace(
        ["     ", "    ", "   ", "  "],
        " ",
        trim($string)
    );

    return $trim;
}

function str_trim_assoc(array $array): array
{
    foreach ($array as $key => $value) {
        $value = filter_var(mb_strtolower($value), FILTER_SANITIZE_STRIPPED);
        $trim = str_replace(
            ["     ", "    ", "   ", "  "],
            " ",
            trim($value)
        );

        $array[$key] = $trim;
    }

    return $array;
}

/**
 * Convert: "olá Meu Nome   é" to "ola-meu-nome-e"
 *
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(
        ["-----", "----", "---", "--"],
        "-",
        str_replace(
            " ",
            "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * Exlcude " " and tranform string to studly case
 *
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    str_slug($string);
    $studlyCase = str_replace(" ", "", mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE));
    return $studlyCase;
}

/**
 * Transform a string to camel case
 *
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $srting
 * @return false|string|string[]
 */
function str_title(string $srting)
{
    return mb_convert_case(filter_var($srting, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * Resume a string by number of words and add a pointer at the end
 *
 * @param string $string
 * @param integer $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = filter_var(trim($string), FILTER_SANITIZE_SPECIAL_CHARS);
    $arrWords = explode(" ", $string);

    if (count($arrWords) < $limit) {
        return $string;
    }

    return implode(" ", array_slice($arrWords, 0, $limit)) . $pointer;
}

/**
 * Resume a string by number of chars and add a pointer at the end
 *
 * @param string $string
 * @param integer $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return $chars . $pointer;
}

function extensionRemove(string $string): string
{
    $strNoExtension = explode(".", $string);
    return $strNoExtension[0];
}

/**
 * ####################
 * ###  DATE TIME   ###
 * ####################
 */

/**
 * Undocumented function
 *
 * @param string $dateTime
 * @return string
 */
function date_frmt(string $dateTime = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($dateTime))->format($format);
}

/**
 * @param string $dateTime
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_frmt_br(string $dateTime = "now", string $format = CONF_DATE_BR): string
{
    return (new DateTime($dateTime))->format($format);
}


/**
 * @param string $dateTime
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_frmt_app(string $dateTime = "now", string $format = CONF_DATE_APP): string
{
    return (new DateTime($dateTime))->format($format);
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * Undocumented function
 *
 * @param string $path
 * @return string
 */
function url(string $path): string
{
    if (!$path) {
        return CONF_URL_BASE;
    }
    return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
}

/**
 * Undocumented function
 *
 * @param string $url
 * @return void
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    $location = url($url);
    header("Location: {$location}");
    exit;
}


/**
 * ################
 * ###   CORE   ###
 * ################
 */

/**
 * Trigger of PDO Class
 *
 * @return PDO
 */
function db(): PDO
{
    return Connection::getInstance();
}

/**
 * Trigger of Message Class 
 *
 * @return App\Core\Message
 */
function message(): App\Core\Message
{
    return (new App\Core\Message());
}

/**
 * Trigger of Session Class
 *
 * @return App\Core\Session
 */
function session(): App\Core\Session
{
    return (new App\Core\Session());
}

/**
 * #################
 * ###   MODEL   ###
 * #################
 */

/**
 * @return App\Model\UserModel
 */
function user(): App\Model\UserModel
{
    return (new App\Model\UserModel());
}

/**
 * @return App\Model\EventModel
 */
function event(): App\Model\EventModel
{
    return (new App\Model\EventModel());
}

/**
 * @return App\Model\AttendanceModel
 */
function attendance(): App\Model\AttendanceModel
{
    return (new App\Model\AttendanceModel());
}

function updateAttendances(EventModel $eventObject = null, UserModel $userObject = null): bool
{

    $eventObject = (empty($eventObject) ? event()->all() : array($eventObject));
    $userObject = (empty($userObject) ? user()->all() : array($userObject));

    foreach ($eventObject as $event) {
        foreach ($userObject as $user) {
            if (!attendance()->bootstrap($user->id, $event->id, false, false)->save()) {
                return false;
            }
        }
    }
    return true;
}

function setPasswordByMauaEmail(string $email): ?string
{
    $password = explode("@", $email);
    $password = str_replace(".", "", $password[0]);
    $password = str_replace("-", "", $password);
    return $password;
}

function setPasswordByEmail(string $email): ?string
{
    $password = explode("@", $email);
    $password = $password[0];
    return $password;
}
