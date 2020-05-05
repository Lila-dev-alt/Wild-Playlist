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


    public function insert(array $question)
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`content`) VALUES (:content)");
        $statement->bindValue('content', $question['content'], \PDO::PARAM_STR);
        $statement->execute();
    }

}
