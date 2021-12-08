<?php

namespace App\Repository;

use PDO;
use App\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Repository.
 */
class UserRepository implements BaseRepositoryInterface{

    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function login($email, $pwd)
    {
        $sql = "SELECT FROM users where email=".$email." and pwd=".$pwd;
        $query= $this->connection->prepare($sql);
        $query->execute();
        $result = $this->connection->query($query);

        return  $result;
    }
    
    public function create($payload){

        $row = [
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
            'email' => $payload['email'],
        ];

        $sql = "INSERT INTO users SET 
                first_name=:first_name, 
                last_name=:last_name, 
                email=:email;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();

    }

    /**
    * @return defaultOrder
    */
    public function getAll()
    {
    }

    /**
    * @return paginate defaultOrder
    */
    public function paginate(int $limit = 15)
    {
       
    }
    public function getByID($id)
    {
      
    }
  

    public function update($id, array $data)
    {
      
    }

    public function delete($id)
    {
       
    }
  
}