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
    
    public function create($data){

        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['nickname'])) {
            $tplVars['message'] = 'Please fill in both names and nickname';
        } elseif (empty($data['gender']) || ($data['gender'] != 'male' && $data['gender'] != 'female')) {
            $tplVars['message'] = 'Gender must be either "male" or "female"';
        } else {
            // everything filled
            try {
                $stmt = $this->connection->prepare(
                    "INSERT INTO person (first_name, last_name, nickname, birth_day, height, gender) 
                    VALUES (:first_name, :last_name, :nickname, :birth_day, :height, :gender)"
                );
                $stmt->bindValue(':first_name', $data['first_name']);
                $stmt->bindValue(':last_name', $data['last_name']);
                $stmt->bindValue(':nickname', $data['nickname']);
                $stmt->bindValue(':gender', $data['gender']);
    
                if (empty($data['birth_day'])) {
                    $stmt->bindValue(':birth_day', null);
                } else {
                    $stmt->bindValue(':birth_day', $data['birth_day']);
                }
    
                if (empty($_POST['height']) || empty(intval($data['height']))) {
                    $stmt->bindValue(':height', null);
                } else {
                    $stmt->bindValue(':height', intval($data['height']));
                }
                $stmt->execute();
                $tplVars['message'] = "Person added";
            } catch (PDOException $e) {
                $this->logger->error($e->getMessage());
                $tplVars['message'] = "Failed to insert person (" . $e->getMessage() . ")";
            }
        }
        $tplVars['person'] = $data;
        return $tplVars;
        // $row = [
        //     'first_name' => $payload['first_name'],
        //     'last_name' => $payload['last_name'],
        //     'email' => $payload['email'],
        // ];

        // $sql = "INSERT INTO users SET 
        //         first_name=:first_name, 
        //         last_name=:last_name, 
        //         email=:email;";

        // $this->connection->prepare($sql)->execute($row);

        //return (int)$this->connection->lastInsertId();

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
  

    public function update($personIdid, array $data)
    {
        if (empty($personId)) {
            exit("Parameter 'id' is missing.");
        }
        try {
            $stmt = $this->db->prepare("SELECT * FROM person WHERE id_person = :id_person");
            $stmt->bindValue(':id_person', $personId);
            $stmt->execute();
            $tplVars['person'] = $stmt->fetch();
            if (!$tplVars['person']) {
                exit("Cannot find person with ID: $personId");
            }
        } catch (PDOException $e) {
            exit("Cannot get person " . $e->getMessage());
        }
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['nickname'])) {
            $tplVars['message'] = 'Please fill in both names and nickname';
        } elseif (empty($data['gender']) || ($data['gender'] != 'male' && $data['gender'] != 'female')) {
            $tplVars['message'] = 'Gender must be either "male" or "female"';
        } else {
            // everything filled
            try {
                $stmt = $this->db->prepare(
                    "UPDATE person SET first_name = :first_name, last_name = :last_name, 
                    nickname = :nickname, birth_day = :birth_day, gender = :gender, height = :height
                    WHERE id_person = :id_person"
                );
                $stmt->bindValue(':id_person', $personId);
                $stmt->bindValue(':first_name', $data['first_name']);
                $stmt->bindValue(':last_name', $data['last_name']);
                $stmt->bindValue(':nickname', $data['nickname']);
                $stmt->bindValue(':gender', $data['gender']);
    
                if (empty($data['birth_day'])) {
                    $stmt->bindValue(':birth_day', null);
                } else {
                    $stmt->bindValue(':birth_day', $data['birth_day']);
                }
    
                if (empty($data['height']) || empty(intval($data['height']))) {
                    $stmt->bindValue(':height', null);
                } else {
                    $stmt->bindValue(':height', intval($data['height']));
                }
                $stmt->execute();
                $tplVars['message'] = "Person updated";
            } catch (PDOException $e) {
                $this->logger->error($e->getMessage());
                $tplVars['message'] = "Failed to update person (" . $e->getMessage() . ")";
            }
        }
        $tplVars['person'] = $data;
        $this->view->render($response, 'person-update.latte', $tplVars);
    }

    public function delete($id)
    {
       
    }
  
}