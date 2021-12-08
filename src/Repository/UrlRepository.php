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
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create($attributes){
       

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

    public function fetchUrl($url): array
    {
        $shortCode = $this->urlExistsInDB($url);
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
}
