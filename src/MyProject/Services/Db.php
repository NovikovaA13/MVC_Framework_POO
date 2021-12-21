<?php

namespace MyProject\Services;

use MyProject\Exceptions\DbException;

class Db{
    /**
     * @var \PDO
     */
    private $pdo;
    private static $instance;

    public static function getInstance()//on utilise ici le pattern Singleton pour ne pas créer qu'une connexion à la BDD
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;

    }

    private function __construct()
    {
        $dbOptions = (require __DIR__. '/../../settings.php')['db'];

        try{
            $this->pdo = new \PDO('mysql:host=localhost;dbname='. $dbOptions['dbname'], $dbOptions['user'], $dbOptions['password']);
            $this->pdo->query('SET CHARSET utf8');
        }
        catch(\PDOException $exception){
            throw new DbException("L'exception est jeté avec message: " . $exception->getMessage() . " et le code: " . $exception->getCode());
        }

    }
    public function query(string $sql, array $params = [], $className = 'stdClass')
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        if($result === false){
            return null;
        }else {
            return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
        }

    }
    public function getLastInsertedId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
