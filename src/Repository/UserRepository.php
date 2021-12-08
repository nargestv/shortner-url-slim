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

    public function login($id, $pwd)
    {
        $sql = "SELECT FROM users where id=".$id." and pwd=".$pwd;
        $query= $this->connection->prepare($sql);
        $query->execute();
        $result = $this->connection->query($query);

        return  $result;
    }
    
    public function create($user){
        $row = [
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
        ];

        $sql = "INSERT INTO users SET 
                username=:username, 
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