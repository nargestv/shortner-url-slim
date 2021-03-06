<?php

namespace App\Repository;

use PDO;
use App\Repository\Interfaces\BaseRepositoryInterface;

/**
 * Repository.
 */
class UrlRepository implements BaseRepositoryInterface{
    /**
     * @var PDO The database connection
     */
    private $connection;
    protected static $table = "short_urls";
    protected static $codeLength = 7;
    protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
  
    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    // public function __construct(PDO $connection)
    // {
    //    // $this->connection = $connection;
    // }

    public function create($attributes){
       return $this->fetchUrl($attributes);
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

    public function getByID($url)
    {
        return $this->urlExistsInDB($url);
    }

    public function update($url, array $data)
    {
        $fetchUrl = $this->urlExistsInDB($url);
        if($fetchUrl){
            $update = $this->fetchUrl($data);

        }
    }

    public function delete($id)
    {
       $this->deleteUrlInDB($id);
    }

    public function fetchUrl($url): array
    {
        $shortCode = $this->urlExistsInDB($url);
        if($shortCode){
            $shortCode = $this->updateShortCode($url);
        }
        if($shortCode == false){
            $shortCode = $this->createShortCode($url);
        }

        return $shortCode;
    }

    protected function urlExistsInDB($url){
        $query = "SELECT short_code FROM ".self::$table." WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);

        $params = array(
            "long_url" => $url
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_code"];
    }

    protected function createShortCode($url){
        $shortCode = $this->generateRandomString(self::$codeLength);
        $id = $this->insertUrlInDB($url, $shortCode);
        return $shortCode;
    }

    protected function updateShortCode($url){
        $shortCode = $this->generateRandomString(self::$codeLength);
        $id = $this->updateUrlInDB($url, $shortCode);
        return $shortCode;
    }
    
    protected function generateRandomString($length = 6){
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
       
        return $randString;
    }
    
    protected function insertUrlInDB($url, $code){
        $query = "INSERT INTO ".self::$table." (long_url, short_code, created) VALUES (:long_url, :short_code, :timestamp)";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url,
            "short_code" => $code,
            "timestamp" => $this->timestamp
        );
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }

    protected function updateUrlInDB($url, $code){
        $query = "UPDATE ".self::$table."  
        SET `long_url` = :long_url,
            `short_code` = :short_code
        WHERE `id` = :id";

        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url,
            "short_code" => $code,
            "timestamp" => $this->timestamp
        );
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }


    protected function deleteUrlInDB($id){
        $sql = 'DELETE FROM publishers
        WHERE publisher_id = :publisher_id';

// prepare the statement for execution
$statement = $pdo->prepare($sql);
$statement->bindParam(':publisher_id', $publisher_id, PDO::PARAM_INT);

// execute the statement
if ($statement->execute()) {
	echo 'publisher id ' . $publisher_id . ' was deleted successfully.';
}
    }
}
