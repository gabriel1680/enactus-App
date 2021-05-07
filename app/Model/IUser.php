<?php


namespace App\Model;



interface IUser extends IModel
{
    public function bootstrap(string $email, string $name, string $password, string $office): ?IUser;
    public function findByEmail($email, string $columns = "*"): ?UserModel;
    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = CONF_MODEL_USER_OREDER_BY, string $orderDirection = CONF_MODEL_OREDER_USER_DIRECTION): ?array;
}