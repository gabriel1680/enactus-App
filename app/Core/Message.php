<?php

namespace App\Core;

class Message
{
    private $type;
    private $message;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Get the value of message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the value of type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function info(string $mesasge): Message
    {
        $this->type = CONF_MESSAGE_INFO_ICON . ' ' . CONF_MESSAGE_INFO;
        $this->message = $this->filter($mesasge);
        return $this;
    }

    public function warning(string $mesasge): Message
    {
        $this->type = CONF_MESSAGE_WARNING_ICON . ' ' . CONF_MESSAGE_WARNING;
        $this->message = $this->filter($mesasge);
        return $this;
    }

    public function success(string $mesasge): Message
    {
        $this->type = CONF_MESSAGE_SUCCESS_ICON . ' ' . CONF_MESSAGE_SUCCESS;
        $this->message = $this->filter($mesasge);
        return $this;
    }

    public function error(string $mesasge): Message
    {
        $this->type = CONF_MESSAGE_ERROR_ICON . ' ' . CONF_MESSAGE_ERROR;
        $this->message = $this->filter($mesasge);
        return $this;
    }

    public function render(): string
    {
        return "<div class ='" . CONF_MESSAGE_CLASS . " {$this->type}'>{$this->message}
                    <div class= \"progress-bar\" ></div>
                </div>";
    }

    public function json(): string
    {
        return json_encode(["type" => $this->type, "message" => $this->message]);
    }

    public function flash(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["flash"] = $this;
    }

    public function urlJson ( string $url ): string
    {
        return json_encode(["url" => $url]);
    }

    private function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
