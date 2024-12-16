<?php
namespace Sk\App\Models;

use Sk\App\Core\MongoDBConn;

class UserMgModel {
    private $client;
    
    public function __construct(){
        $this->client = MongoDBConn::getInstance(MONGO_HOST, MONGO_PORT, MONGO_USER, MONGO_PASS, MONGO_DB);
    }

    public function getUser($userName){
        $userData = $this->client->findRecordByField('sysusers', 'email', $userName);
        return $userData;
    }

    public function addUser(){
        $dataUser = [
            "id_usrsys" => 3,
            "uname" => 'yisus',
            "upass" => '$2y$10$o5RzOUs9MlAyyfNdiyotaex2SnKD5h4Rg.jNIjsJ7Q4/rUjTpmeMu',
            "email" => 'jesus.cruz@centrolaboral.gob.mx',
            "paterno" => 'Cruz',
            "materno" => 'Rosas',
            "nombre" => 'Jesus'
        ];

        $userData = $this->client->insertRecord('sysusers',$dataUser);
        return $userData;
    }




}
