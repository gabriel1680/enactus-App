<?php

namespace App\Core;

/**
 * Class Session
 * @package App\Core
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (!session_id()) {
            // session_save_path();
            session_start();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!empty($name)) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->has($name);
    }

    /**
     * @return object|null
     */
    public function all(): ?object
    {
        return (object)$_SESSION;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object)$value : $value);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @return $this
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    /**
     * @return $this
     */
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }

    /**
     * Flashes class Message
     *
     * @return Message|null
     */
    public function flash(): ?Message
    {
        if ($this->has("flash")) {
            $flash = $this->flash;
            $this->unset("flash");
            return $flash;
        } else {
            return null;
        }
    }

    /**
     * CSRF token
     *
     * @return void
     */
    public function csrf(): void
    {
        $this->set('csrf_token', base64_encode(random_bytes(20)));
        //$_SESSION['csf_token'] = base64_encode(random_bytes(20));
    }
}
