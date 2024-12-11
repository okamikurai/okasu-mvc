<?php
namespace Sk\App\Core;

use MongoDB;

class MongoDBConn {
    private $host;
    private $port;
    private $dbName;
    private $user;
    private $pass;

    private $client;
    private $dbUri;
    private $db;

    public function __construct($host = "127.0.0.1", $port = 27017, $dbName, $user, $pass = ""){
        $this->host = $host;
        $this->port = $port;
        $this->dbName = $dbName;
        if ($user != null && $user != "" && $pass != null && $pass != "" ) {
            $this->user = $user . ":";
            $this->pass = $pass . "@";
        } else {
            $this->user = "";
            $this->pass = "";
        }
        try {
            $this->dbUri = "mongodb://{$this->user}{$this->pass}{$this->host}:{$this->port}/?authMechanism=DEFAULT&authSource={$this->db}";
            $this->client = new MongoDB\Client($dbUri);
            $this->db = $this->client->$dbName;
        } catch (MongoDB\Driver\Exception\Exception $e) {
            die("Error de conexión a MongoDB: " . $e->getMessage());
        }
    }

    public function getDatabase() {
        return $this->db;
    }

    public function findRecordByField($collectionName, $field, $value) {
        $collection = $this->db->$collectionName;
        $query = [$field => $value];
        $result = $collection->findOne($query);
        return $result;
    }

    public function insertRecord($collectionName, $data) {
        $collection = $this->db->$collectionName;
        $insertResult = $collection->insertOne($data);
        if ($insertResult->getInsertedCount() === 1) {
            return $insertResult->getInsertedId();
        } else {
            return null;
        }
    }

    public function updateRecordByField($collectionName,$filterField,$filerVal,$setValues){
        $collection = $this->db->$collectionName;
        $query = [$filterField => $filerVal];
        $updateResult = $collection->updateOne($query,[ '$set' => $setValues]);
        return $updateResult;
    }

    /*
    public function findById($dataId) {
        $data = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($dataId)]);
        return $data;
    }

    public function updateById($dataId, $newData) {
        $result = $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($dataId)],
            ['$set' => $newUserData]
        );
        return $result->getModifiedCount();
    }

    public function deleteById($dataId) {
        $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($dataId)]);
        return $result->getDeletedCount();
    }

    // Consultar todos los usuarios
    public function getAllUsers() {
        $cursor = $this->collection->find();
        $users = iterator_to_array($cursor);
        return $users;
    }

    // Buscar un usuario por su nombre de usuario
    public function findUserByUsername($username) {
        $user = $this->collection->findOne(['username' => $username]);
        return $user;
    }
    */
}
