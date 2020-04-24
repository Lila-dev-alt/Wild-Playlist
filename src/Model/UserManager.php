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
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
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
}
