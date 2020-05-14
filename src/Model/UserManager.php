<?php


namespace App\Model;

class UserManager extends AbstractManager
{
    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectOneByEmail(string $email)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email=:email LIMIT 1");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }


    public function insert(array $user)
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, `email`, `password`, `created_at`) VALUES (:name, :email, :password, NOW())");
        $statement->bindValue('name', $user['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);

        $statement->execute();
    }
    public function selectOneByUsername(string $pseudo)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE name=:name LIMIT 1");
        $statement->bindValue('name', $pseudo, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
