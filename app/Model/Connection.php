<?php

namespace App\Model;

use PDO;
use PDOException;

/**
 * Class Connection
 * @package App\Core
 */
class Connection
{
    /**
     * @var
     */
    private static $instance;

    /**
     * Connection constructor.
     */
    final private function __construct()
    {
    }

    /**
     *
     */
    final protected function __clone()
    {
    }

    /**
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (empty(self::$instance)) {
            try {
                $DB = DB_PROPERTIES;
                self::$instance = new PDO(
                    $DB["driver"] . ":host=" . $DB["host"] . ";dbname=" . $DB["dbname"],
                    $DB["username"],
                    $DB["passwd"],
                    $DB["options"]
                );
            } catch (PDOException $exception) {
                die("<h1>Ops ! Erro ao concetar com o Banco de dados</h1><p>Erro: {$exception}</p>");
            }
        }

        return self::$instance;
    }
}
