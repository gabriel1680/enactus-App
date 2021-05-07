<?php


namespace App\Model;


interface IAttendance extends IModel
{
    public function bootstrap(string $user_id, string $event_id, bool $attendance = true, bool $requested = true): ?IAttendance;
    public function findByEventId($event_id, string $columns = "*"): ?IAttendance;
    public function findAttendance($event_id, $user_id, string $columns = "*"): ?IAttendance;
    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = null, string $orderDirection = null): ?array;

}