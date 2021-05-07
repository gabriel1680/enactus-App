<?php


namespace App\Core;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Mail
 * @package App\Core
 */
class Mail
{
    /** @var array $data*/
    private $data;

    /**
     * @var PHPMailer
     */
    private $mail;

    /**
     * @var string
     */
    private $message;

    /**
     * Mail constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        //CONFIG
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->setLanguage(CONF_MAIL["language"]);
        $this->mail->isHTML(CONF_MAIL["isHTML"]);
        $this->mail->SMTPAuth = CONF_MAIL["SMTPAuth"];
        $this->mail->SMTPSecure = CONF_MAIL["SMTPSecure"];
        $this->mail->CharSet = CONF_MAIL["charset"];

        //AUTH
        $this->mail->Host = CONF_MAIL["host"];
        $this->mail->Username = CONF_MAIL["username"];
        $this->mail->Password = CONF_MAIL["password"];
        $this->mail->Port = CONF_MAIL["port"];
    }

    /**
     * @param string $subject
     * @param string $message
     * @param string $toEmail
     * @param string $toName
     * @return $this
     */
    public function bootstrap(string $subject, string $message, string $toEmail, string $toName): Mail
    {
        //MAIL
        $this->data = new \stdClass();
        $this->data->subject = $subject;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;

        return $this;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @return $this
     */
    public function attachment(string $filePath, string $fileName): Mail
    {
        $this->data->attchment[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param string|mixed $fromEmail
     * @param string|mixed $fromName
     * @return bool
     */
    public function send(string $fromEmail = CONF_MAIL["sender"]["address"], string $fromName = CONF_MAIL["sender"]["name"]): bool
    {
        if (empty($this->data)) {
            $this->message = "Erro ao enviar, verifique os dados";
            return false;
        }

        if (!$this->isEmail($this->data->toEmail)) {
            $this->message = "O email do destinatário não é válido";
            return false;
        }

        if (!$this->isEmail($fromEmail)) {
            $this->message = "O email do remetente não é válido";
            return false;
        }

        try {

            //SEND
            $this->mail->setFrom($fromEmail, $fromName);
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->message);
            $this->mail->addAddress($this->data->toEmail, $this->data->toName);

            if (!empty($this->data->attachment)) {
                foreach ($this->data->attachment as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            $this->message = $exception->getMessage();
            return false;
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @param string $email
     * @return bool
     */
    private function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
