<?php

namespace App\Model;

use PDO;

/**
 * Class AttendanceModel
 * @package App\Model
 */
class AttendanceModel extends DataLayer implements IAttendance
{

    /**@var array $safe no update or create*/
    protected static $safe = ["id", "created_at", "updated_at"];

    // protected const required = ["ra", "name", "password", "office"];

    /**@var string $entity database table*/
    protected static $entity = "db_attendance";

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        parent::__construct(self::$entity, ["user_id", "event_id", "attendance", "requested"]);
    }


    public function bootstrap(string $user_id, string $event_id, bool $attendance = true, bool $requested = true): ?AttendanceModel
    {
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->attendance = ($attendance == true ? "1" : "0");
        $this->requested = ($requested == true ? "1" : "0");

        return $this;
    }

    public function find(string $terms, string $params, string $columns = "*"): ?AttendanceModel
    {
        $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE {$terms}", $params);
        if ($this->fail() || !$find->rowCount()) {
            return null;
        }

        $this->data = $find->fetchObject(__CLASS__);
        return  $this->data;
    }

    /**
     * @param $id
     * @param string $columns
     * @return AttendanceModel|null
     */
    public function findById($id, $columns = "*"): ?AttendanceModel
    {
        return $this->find("id = :id", "id={$id}", $columns);
    }

    public function findByUserId($user_id, string $columns = "*"): ?AttendanceModel
    {
        return $this->find("user_id = :user_id", "user_id={$user_id}", $columns);
    }

    public function findByEventId($event_id, string $columns = "*"): ?AttendanceModel
    {
        return $this->find("event_id = :event_id", "event_id={$event_id}", $columns);
    }

    public function findAttendance($event_id, $user_id, string $columns = "*"): ?AttendanceModel
    {
        return $this->find("event_id = :event_id AND user_id = :user_id", "event_id={$event_id}&user_id={$user_id}", $columns);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $columns
     * @return array|null
     */
    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = null, string $orderDirection = null): ?array
    {
        if (!$orderBy && !$orderDirection) {
            $orderDirection = "ASC";
            $orderBy = "attendance";
            $all = $this->read("SELECT {$columns} FROM " . self::$entity . " ORDER BY attendance ASC, requested DESC LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");
        } else {
            $all = $this->read("SELECT {$columns} FROM " . self::$entity . " ORDER BY {$orderBy} {$orderDirection} LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");
        }

        if (!$this->isTableField($orderBy) || $orderDirection != "ASC" && $orderDirection != "DESC" ) {
            $this->message = "O campo informado não existe !";
            return null;
        }

        if ($this->fail() || !$all->rowCount()) {
            return null;
        }

        return $all->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    public function count(): ?string
    {
        $count = $this->read("SELECT COUNT(id) as total FROM " . self::$entity);
        if ($this->fail() || !$count->rowCount()) {
            return null;
        }

        return $count->fetch()->total;
    }

    /**
     * @return $this|null
     */
    public function save(): ?AttendanceModel
    {
        if (!$this->required()) {
            $this->message = "Os campos obrigatórios não foram preenchidos";
            return null;
        }

        /**User Update */
        if (!empty($this->id)) {
            $attendanceId = $this->id;

            $this->update($attendanceId);

            if ($this->fail()) {
                $this->message = "Erro ao atualizar, verifique os dados";
                return null;
            }
        }

        /**User Create */
        if (empty($this->id)) {

            $attendanceId = $this->create();

            if ($this->fail()) {
                // $this->message->error("Erro ao cadastrar, verifique os dados");
                $this->message = "Erro ao cadastrar, verifique os dados";
                return null;
            }
        }
        //atualizando o vetor de active record e não gerando um novo objeto
        $this->data = ($this->findById($attendanceId))->data();
        return $this;
    }

    /**
     * @return $this|null
     */
    public function destroy(): ?AttendanceModel
    {
        if (!empty($this->id) && $this->findById($this->id)) {
            $this->delete($this->id);
        }

        if ($this->fail()) {
            return null;
        }

        $this->data = null;
        return $this;
    }
}
