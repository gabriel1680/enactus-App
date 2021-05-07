<?php

namespace App\Model;

use PDO;

/**
 * Class EventModel
 * @package App\Model
 */
class EventModel extends DataLayer implements IEvent
{

    /**@var array $safe no update or create*/
    protected static $safe = ["id", "created_at", "updated_at"];

    // protected const required = ["ra", "name", "password", "office"];

    /**@var string $entity database table*/
    protected static $entity = "db_event";

    /**
     * EventModel constructor.
     */
    public function __construct()
    {
        parent::__construct(self::$entity, ["type", "name", "mandatory", "local", "date"]);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $mandatory
     * @param string $local
     * @param string $date
     * @param string $description
     * @return $this|null
     */
    public function bootstrap(
        string $type,
        string $name,
        string $mandatory,
        string $local,
        string $date,
        string $description = ""
    ): ?EventModel {
        $this->type = $type;
        $this->name = $name;
        $this->mandatory = $mandatory;
        $this->local = $local;
        $this->date = $date;
        $this->description = $description;
        return $this;
    }

    public function find(string $terms, string $params, string $columns = "*"): ?EventModel
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
     * @return EventModel|null
     */
    public function findById($id, $columns = "*"): ?EventModel
    {
        return $this->find("id = :id", "id={$id}", $columns);
    }

    /**
     * @param $ra
     * @param string $columns
     * @return EventModel|null
     */
    public function findByName(string $name, string $columns = "*"): ?EventModel
    {
        return $this->find("name = :name", "name={$name}", $columns);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $columns
     * @return array|null
     */
    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = CONF_MODEL_EVENT_OREDER_BY, string $orderDirection = CONF_MODEL_OREDER_EVENT_DIRECTION): ?array
    {
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " ORDER BY {$orderBy} {$orderDirection} LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");

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
    public function save(): ?EventModel
    {
        if (!$this->required()) {
            //$this->message->warning("Nome, RA, senha e cargo são obrigatórios");
            return null;
        }

        /**User Update */
        if (!empty($this->id)) {
            $eventId = $this->id;

            if ($this->find("name = :name AND id != :id", "name={$this->name}&id={$eventId}")) {
                // $this->message->warning("O ra informado já está cadastrado");
                $this->message = "O evento informado já está cadastrado";
                return null;
            }

            $this->update($eventId);

            if ($this->fail()) {
                // $this->message->error"(Erro ao atualizar, verifique os dados");
                $this->message = "Erro ao atualizar, verifique os dados";
                return null;
            }
        }

        /**User Create */
        if (empty($this->id)) {
            if ($this->findByName($this->name)) {
                // $this->message->warning("O ra informado já está cadastrado");
                $this->message = "O evento informado já está cadastrado";
                return null;
            }

            $eventId = $this->create();

            if ($this->fail()) {
                // $this->message->error("Erro ao cadastrar, verifique os dados");
                $this->message = "Erro ao cadastrar, verifique os dados";
                return null;
            }
        }
        //atualizando o vetor de active record e não gerando um novo objeto
        $this->data = ($this->findById($eventId))->data();
        return $this;
    }

    /**
     * @return $this|null
     */
    public function destroy(): ?EventModel
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
