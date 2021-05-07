<?php

namespace App\Model;

use PDO;

/**
 * Class UserModel
 * @package App\Model
 */
class UserModel extends DataLayer implements IUser
{
    /**@var array $safe no update or create*/
    protected static $safe = ["id", "created_at", "updated_at"];

    /**@var string $entity database table*/
    protected static $entity = "db_id";

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        parent::__construct(self::$entity, ["email", "name", "password", "office"]);
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $office
     * @return $this|null
     */
    public function bootstrap(string $email, string $name, string $password, string $office): ?UserModel
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->office = $office;
        return $this;
    }

    /**
     * @param string $terms
     * @param string $params
     * @param string $columns
     * @return UserModel|null
     */
    public function find(string $terms, string $params, string $columns = "*"): ?UserModel
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
     * @return UserModel|null
     */
    public function findById($id, string $columns = "*"): ?UserModel
    {
        filter_var($id, FILTER_SANITIZE_STRIPPED);
        return $this->find("id = :id", "id={$id}", $columns);
    }

    /**
     * @param $email
     * @param string $columns
     * @return UserModel|null
     */
    public function findByEmail($email, string $columns = "*"): ?UserModel
    {
        return $this->find("email = :email", "email={$email}", $columns);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $columns
     * @return array|null
     */
    public function all(int $limit = CONF_MODEL_LIMIT, int $offset = CONF_MODEL_OFFSET, string $columns = "*", string $orderBy = CONF_MODEL_USER_OREDER_BY, string $orderDirection = CONF_MODEL_OREDER_USER_DIRECTION): ?array
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

    /**
     * @return string|null
     */
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
    public function save(): ?UserModel
    {
        if (!$this->required()) {
            $this->message = "Os campos obrigatórios não foram preenchidos";
            return null;
        }

        if (!$this->isEmail($this->data->email)) {
            $this->message = "O email informado não possui um formato válido";
            return null;
        }

        if (!$this->isPassword($this->data->password)) {
            $max = CONF_PASSWD_MAX_LEN;
            $min = CONF_PASSWD_MIN_LEN;
            $this->message = "A senha deve ter entre {$min} e {$max} caracteres";
            return null;
        } else {
            $this->data->password = $this->password($this->data->password);
        }

        /**User Update */
        if (!empty($this->id)) {

            $userId = $this->id;

            if ($this->find("email = :email AND id != :id", "email={$this->email}&id={$userId}")) {
                $this->message = "O email informado já está cadastrado";
                return null;
            }

            $this->update($userId);

            if ($this->fail()) {
                $this->message = "Erro ao atualizar, verifique os dados";
                return null;
            }
        }

        /**User Create */
        if (empty($this->id)) {
            if ($this->findByEmail($this->email)) {
                $this->message = "O email informado já está cadastrado";
                return null;
            }

            $userId = $this->create();

            if ($this->fail()) {
                $this->message = "Erro ao cadastrar, verifique os dados";
                return null;
            }
        }
        //atualizando o vetor de active record e não gerando um novo objeto
        $this->data = ($this->findById($userId))->data();
        return $this;
    }

    /**
     * @return $this|null
     */
    public function destroy(): ?UserModel
    {
        if (!empty($this->id) && $this->findById($this->id)) {
            $this->delete($this->id);
        }

        if ($this->fail()) {
            $this->message = "Não foi possível apagar o registro";
            return null;
        }

        $this->data = null;
        return $this;
    }

    /***************************** PRIVATE FUNCTIONS *****************************/

    /**
     * @param string $email
     * @return bool
     */
    private function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $string
     * @return bool
     */
    private function isRa(string $string): bool
    {
        if (strlen($string) != 10) {
            return false;
        }

        if (!strstr($string, ".") || !strstr($string, "-")) {
            return false;
        }

        if (strpos($string, ".") != 2 || strpos($string, "-") != 8) {
            return false;
        }

        return true;
    }

    /**
     * @param string $string
     * @return bool
     */
    private function isMauaDomain(string $string): bool
    {
        return ($string === "maua.br");
    }

    /**
     * @param string $email
     * @return bool
     */
    private function isMauaEmail(string $email): bool
    {
        if ($this->isEmail($email)) {
            $emailParsed = explode("@", $email);

            return (!$this->isRa($emailParsed[0]) || !$this->isMauaDomain($emailParsed[1]) ? false : true);
        } else {
            return false;
        }
    }

    /**
     * @param string $passwd
     * @return bool
     */
    private function isPassword(string $passwd): bool
    {
        if (password_get_info($passwd)["algo"]) {
            return true;
        }

        return (mb_strlen($passwd) >= CONF_PASSWD_MIN_LEN && mb_strlen($passwd) <= CONF_PASSWD_MAX_LEN ? true : false);
    }

    /**
     * @param string $string
     * @return bool
     */
    private function isHash(string $string): bool
    {
        if (password_get_info($string)["algo"]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $passwd
     * @return string
     */
    private function password(string $passwd): string
    {
        if (!$this->isHash($passwd)) {
            return passwd($passwd);
        }

//        if (passwd_needs_rehash($passwd)) {
//            return passwd($passwd);
//        }

        return  $passwd;
    }
}
