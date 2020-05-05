<?php


namespace App\Model;

class QuestionManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'question';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $question
     * @return int
     */
    public function insert(array $question): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`content`) VALUES (:content)");
        $statement->bindValue('content', $question['content'], \PDO::PARAM_STR);
     if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
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

    /**
     * @param array $question
     * @return bool
     */
    public function update(array $question):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `content` = :content WHERE id=:id");
        $statement->bindValue(':id', $question['id'], \PDO::PARAM_INT);
        $statement->bindValue(':content', $question['content'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
