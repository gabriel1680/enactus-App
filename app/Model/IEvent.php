<?php


namespace App\Model;


interface IEvent extends IModel
{
    public function bootstrap(
        string $type,
        string $name,
        string $mandatory,
        string $local,
        string $date,
        string $description = ""
    ): ?IEvent;

    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = CONF_MODEL_EVENT_OREDER_BY, string $orderDirection = CONF_MODEL_OREDER_EVENT_DIRECTION): ?array;
    public function findByName(string $name, string $columns = "*"): ?IModel;
}