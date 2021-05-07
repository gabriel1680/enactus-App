<?php

namespace App\Model;

use PDO;
use stdClass;
use PDOException;
use PDOStatement;
use App\Model\Connection;

/**
 * Class DataLayer
 * patter Layer Super Type (stateless) for active record
 * @package App\Core
 */
abstract class DataLayer
{

    /**
     * @var string
     */
    private $entity;
    /**
     * @var array
     */
    private static $required;
    /**
     * @var string
     */
    private static $primaryKey;
    /**
     * @var array
     */
    private static $timesTamp;

    /**
     * @var array
     */
    private $safe;

    /**
     * @var object|null
     */
    protected $data;

    /**
     * @var PDOException|null
     */
    protected $fail;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * DataLayer constructor.
     * @param string $entity
     * @param array $required
     * @param string $primaryKey
     * @param bool $timesTamp
     */
    public function __construct(string $entity, array $required, string $primaryKey = "id", bool $timesTamp = true)
    {
        $this->entity = $entity;
        self::$primaryKey = $primaryKey;
        self::$required = $required;

        if ($timesTamp) {
            self::$timesTamp = ["created_at", "updated_at"];
        }

        $this->safe = self::$timesTamp;
        $this->safe[] = $primaryKey;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->data->$name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        if (empty($this->data)) {
            $this->data = new stdClass();
        }

        if ($name != "password" && !$name && $name != "forgot") {
            $value = str_trim($value);
        }

        if ($name == "name" || $name == "local") {
            $value = mb_convert_case($value, MB_CASE_TITLE);
        }

        if ($name == "description") {
            $value = mb_convert_case($value, MB_CASE_LOWER);
        }

        $this->data->$name = $value;
    }

    /**
     * @param string $name
     * @return null
     */
    public function __get(string $name)
    {
        return $this->data->$name;
    }

    /**
     * @return Object|null
     */
    public function data(): ?object
    {
        if (isset($this->data)) {

            if (isset($this->data->data)) {
                $this->data = $this->data->data;
            }

            return $this->data;
        }
        return null;
    }

    /**
     * @return PDOException
     */
    public function fail(): ?PDOException
    {
        return ($this->fail ?? null);
    }

    /**
     * @return string|null
     */
    public function message(): ?string
    {
        return ($this->message ?? null);
    }

    /**
     * @return int|null
     */
    protected function create(): ?int
    {
        try {
            $data = $this->safe();

            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $query = "INSERT INTO {$this->entity} ({$columns}) VALUES ({$values})";
            $stmt = Connection::getInstance()->prepare($query);

            $stmt->execute($this->filter($data));

            return (Connection::getInstance()->lastInsertId());
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $select
     * @param string|null $params
     * @return PDOStatement|null
     */
    protected function read(string $select, ?string $params = null): ?PDOStatement
    {
        try {
            $stmt = Connection::getInstance()->prepare($select);

            if ($params) {
                parse_str($params, $params);
                /**@var array $params*/
                foreach ($params as $key => $value) {
                    if ($key == "limit" || $key == "offset") {
                        $stmt->bindValue(":{$key}", $value, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":{$key}", $value, PDO::PARAM_STR);
                    }
                }
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $exception) {
            $this->fail = $exception;
            $this->message = $exception->getMessage();
            return null;
        }
    }

    /**
     * @param string|int $id
     * @return int|null
     */
    protected function update(string $id): ?int
    {
        try {
            $primaryKey = self::$primaryKey;

            $data = $this->safe();
            $dataSet = [];
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }

            $dataSet = implode(", ", $dataSet);
            $params = "{$primaryKey}=$id";
            parse_str($params, $params);

            $query = "UPDATE $this->entity SET {$dataSet} WHERE {$primaryKey} = :{$primaryKey}";
            $stmt = Connection::getInstance()->prepare($query);

            $stmt->execute($this->filter(array_merge($data, $params)));

            return ($stmt->rowCount() ?? 1);
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string|int $id
     * @return int|null
     */
    protected function delete(string $id): ?int
    {
        try {
            $primaryKey = self::$primaryKey;
            $stmt = Connection::getInstance()->prepare("DELETE FROM {$this->entity} WHERE {$primaryKey}=:{$primaryKey}");
            $params = "{$primaryKey}=$id";
            parse_str($params, $params);
            $stmt->execute($params);

            return ($stmt->rowCount() ?? 1);
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @return null[]|Object[]|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data();
        foreach ($this->safe as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
        }
        return $filter;
    }

    protected function required(): bool
    {
        $data = (array)$this->data();

        foreach (self::$required as $requiredField) {
            if (empty($data[$requiredField]) && $data[$requiredField] != '0') {
                return false;
            }
        }
        return true;
    }

    protected function isTableField ( string $string )
    {
        $tableFields = array_merge($this->safe, $this::$required);

        if (strpos($string, ",")) {
            $arrayOfStrings = explode(",", $string);

            foreach ($tableFields as $field) {
                foreach ($arrayOfStrings as $stringField) {
                    if ($field == $stringField) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            foreach ($tableFields as $field) {
                if ($field == $string) {
                    return true;
                }
            }
            return false;
        }
    }
}
