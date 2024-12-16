<?php
namespace Sk\App\Core;

use MongoDB\Client;
//use MongoDB\Exception\Exception;

class MongoDBConn {
    private static $instance = null;
    private $client;
    private $db;

    /**
     * Constructor para inicializar la conexión a MongoDB.
     *
     * @param string $host Host de la conexión (por defecto: 127.0.0.1)
     * @param int $port Puerto de la conexión (por defecto: 27017)
     * @param string|null $user Usuario de la base de datos (opcional)
     * @param string|null $pass Contraseña de la base de datos (opcional)
     */
    private function __construct($host = "127.0.0.1", $port = 27017, $user = null, $pass= null, $dbName = null){
        $dbUri = "mongodb://$host:$port";
        $options = [];
        if (!$dbName) {
            throw new \Exception("No especifico la base de datos \$dbName", 1);
        }

        if ( $user && $pass && $user != "" && $pass != "" ) {
            $options['username'] = $user;
            $options['password'] = $pass;
        }
        try {
            $this->client = new Client($dbUri, $options);
            $this->db = $this->client->selectDatabase($dbName);
        } catch (\Exception $e) {
            die('Error al conectar con MongoDB: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia única de MongoDBConn.
     *
     * @param string $host Host de la conexión
     * @param int $port Puerto de la conexión
     * @param string|null $user Usuario de la base de datos
     * @param string|null $pass Contraseña de la base de datos
     * @return MongoDBConn
     */
    public static function getInstance($host = 'localhost', $port = 27017, $user = null, $pass = null, $dbName = null) {
        if (self::$instance === null) {
            self::$instance = new self($host, $port, $user, $pass, $dbName);
        }
        return self::$instance;
    }

    /**
     * Busca registro por ObjectId
     *
     * @param string $collectionName Nombre de la collection(tabla)
     * @param string $dataId Id para busqueda del registro
     * @return object document
     */
    public function findById($collectionName, $dataId) {
        try {
            $collection = $this->db->selectCollection($collectionName);
            return $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($dataId)]);
        } catch (\Exception $e) {
            error_log("Error al realizar la consulta. Err:" . $e->getMessage());
            throw new Exception("Error al realizar la consulta");
        }
    }

    /**
     * Busca registro por field y value
     *
     * @param string $collectionName Nombre de la collection(tabla)
     * @param string $field Nombre del campo de busqueda
     * @param string $value valor para busqueda del registro
     */
    public function findRecordByField($collectionName, $field, $value) {
        try {
            $collection = $this->db->selectCollection($collectionName);
            return $collection->findOne([$field => $value]);
        } catch (\Exception $e) {
            error_log("Error al realizar la consulta. Err:" . $e->getMessage());
            throw new \Exception("Error al realizar la consulta");
        }
    }

    /**
     * Inserta el array de datos en una colección(tabla) específica.
     *
     * @param string $collectionName Nombre de la colección
     * @param array $data Array de datos a insertar (registro)
     * @return \MongoDB\InsertOneResult Resultado de la operación de inserción
     */
    public function insertRecord($collectionName, array $data)  {
        try {
            $collection = $this->db->selectCollection($collectionName);
            return $collection->insertOne($data);
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new Exception("Error al procesar la informacion");
        }
    }

    /**
     * Actualizar un registro de una collection
     *
     * @param string $collectionName Nombre de la collection
     * @param string $dataId Id para filtrar el registro
     * @param string $setValues Array de valores para actualizar Ej. array('field1' => 'Value', 'field2'=> 2)
     */
    public function updateOneById($collectionName, $dataId, $newData) {
        try {
            $collection = $this->db->selectCollection($collectionName);
            $result = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($dataId)],
                ['$set' => $newData]
            );
            return $result->getModifiedCount();
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new \Exception("Error al procesar la informacion");
        }
    }

    /**
     * Actualizar un registro de una collection
     *
     * @param string $collectionName Nombre de la collection
     * @param string $filterField Nombre del campo para filtrar
     * @param string $filerVal Valor del campo para filtrar
     * @param string $setValues Array de valores para actualizar Ej. array('field1' => 'Value', 'field2'=> 2)
     */
    public function updateOneByField($collectionName, $filterField, $filerVal, $setValues ){
        try {
            $collection = $this->db->selectCollection($collectionName);
            $result = $collection->updateOne(
                [$filterField => $filerVal],
                [ '$set' => $setValues]
            );
            return $result->getModifiedCount();
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new \Exception("Error al procesar la informacion");
        }
    }

    /**
     * Actualizar los valores de los registros filtrados de una collection
     *
     * @param string $collectionName Nombre de la collection
     * @param string $filterField Nombre del campo para filtrar
     * @param string $filerVal Valor del campo para filtrar
     * @param string $setValues Array de valores para actualizar Ej. array('field1' => 'Value', 'field2'=> 2)
     */
    public function updateAllByField($collectionName, $filterField, $filerVal, $setValues ){
        try {
            $collection = $this->db->selectCollection($collectionName);
            $result = $collection->updateMany([$filterField => $filerVal], [ '$set' => $setValues]);
            return $result->getModifiedCount();
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new \Exception("Error al procesar la informacion");
        }
    }

    /**
     * Elimina el registro encontrado por ObjectId
     *
     * @param string $collectionName Nombre de la collection(tabla)
     * @param string $dataId Id para busqueda del registro
     * @return object objetos borrados
     */
    public function deleteOneById($collectionName, $dataId) {
        try {
            $collection = $collection = $this->db->selectCollection($collectionName);
            $result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($dataId)]);
            return $result->getDeletedCount();
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new \Exception("Error al procesar la informacion");
        }
    }

    /**
     * Elimina los registros encontrados por $filterField
     *
     * @param string $collectionName Nombre de la collection(tabla)
     * @param string $filterField Nombre del campo para filtrar
     * @param string $filerVal Valor del campo para filtrar
     */
    public function deleteAllByField($collectionName, $filterField, $filerVal){
        try {
            $collection = $collection = $this->db->selectCollection($collectionName);
            $result = $collection->deleteMany([$filterField => $filerVal]);
            return $result->getDeletedCount();
        } catch (\Exception $e) {
            error_log("Error al procesar la informacion. Err:" . $e->getMessage());
            throw new \Exception("Error al procesar la informacion");
        }
    }
}
