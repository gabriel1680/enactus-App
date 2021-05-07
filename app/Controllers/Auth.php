<?php


namespace App\Controllers;


use App\Core\Mail;
use App\Core\View;
use App\Model\UserModel;
use CoffeeCode\Router\Router;

/**
 * Class Auth
 * Responsável pela autentitacação do usuário no sistema
 * @package App\Controllers
 */
class Auth extends Controller
{
    /**
     * Auth constructor.
     * @param Router|null $router
     */
    public function __construct(?Router $router = null)
    {
        $this->router = $router;
    }

    /**
     *  Autentca o usuário para login
     * @return void
     */
    public function login(): void
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

        $user = new UserModel();
        $email = $post["nUsername"];
        $password = $post["nPassword"];

        if (!$user->findByEmail($email) || !passwd_verfiy($password, $user->password)) {
            echo message()->warning("Usuário ou senha incorretos")->json();
            exit();
        }

        session()->set("username", $email)->set("office", $user->office)->set("id", $user->id);
        $userFirstName = str_limit_words($user->name, 1, "" );
        message()->info("Bem vindo(a) {$userFirstName}")->flash();

        echo message()->urlJson(url("/home"));
    }

    /**
     * Faz as verificações necessárias para recuperar a senha
     *
     * @param array $data
     * @return void
     */
    public function passwordRecovery(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $post = (object)$post;

        $user = new UserModel;

        if (!isEmail($post->email)) {
            echo message()->warning("O email inserido não é válido")->json();
            exit();
        }

        if (!$user->findByEmail($post->email)) {
            echo message()->error("O email informado não está cadastrado")->json();
            exit();
        }

        $user->forgot = (md5(uniqid(rand(), true)));

        if (!$user->save()) {
            echo message()->error("Ops... Algo deu errado ao gerar sua chave de acesso !")->json();
            exit();
        }

        $v = new View();
        $v->path("View", __DIR__ . "/../View");

        $link = $this->router->route("login.recovery", ["email" => $user->email, "forgot" => $user->forgot]);

        $mail = (new Mail());
        $mail->bootstrap(
            "Recuperação de Senha EnactusPassport",
            $v->render(
                "Emails/forgot",
                ["link" => $link]
            ),
            $user->email,
            $user->name
        );

        if (!$mail->send()) {
            echo message()->error($mail->message())->json();
            exit();
        }

        echo message()->success("E-mail enviado com sucesso !")->json();
        exit();
    }

    /**
     * Autentetica e seta a nova senha
     *
     * @param array $data
     * @return void
     */
    public function passwordAuth(array $data): void
    {
        $post = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $post = (object)$post;

        if ($post->password != $post->confPassword) {
            echo message()->error("As senhas precisam ser iguais!")->json();
            exit();
        }

        if (!is_passwd($post->password)) {
            echo message()->error("As senhas devem contr de 8 e 40 caracteres!")->json();
            exit();
        }

        $user = (new UserModel)->findByEmail($post->email);
        $user->password = $post->password;
        $user->forgot = null;

        if (!$user->save()) {
            echo message()->error($user->message())->json();
            exit();
        }

        message()->success("Senha alterada com sucesso !")->flash();
        echo message()->urlJson(url("/login"));
        exit;
    }
}