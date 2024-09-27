<?php
namespace Sk\App\Core;
class Pgdb {
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $appName;
    private $conn;

    public function __construct(){
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->db   = DB_NAME;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->appName = APP_NAME;
    }

    public function setParams($host,$port,$db,$user,$pass,$appName = ""){
        $this->appName = $appName;
        $this->host = $host;
        $this->port = $port;
        $this->db   = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connDb(){
        $options = $this->appName == "" ? "" : "options='--application_name=$this->appName'";
        try {
            $this->conn = pg_connect("host=$this->host port=$this->port dbname=$this->db user=$this->user password=$this->pass $options");
        } catch (Exception $e) {
            error_log("No se pudo conectar: " . $e->getMessage() );
        }
        return $this->conn;
    }

    public function closeDb(){
        pg_close($this->conn);
    }

    public function query($query){
        try {
            $this->connDb();
            $q = pg_query($this->conn, $query);
            $this->closeDb();
            return $q;
        } catch (Exception $e) {
            @$this->closeDb();
            error_log("Error: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function prepQuery($sql, $params){
        try {
            $this->connDb();
            $q = pg_query_params($this->conn, $sql, $params);
            $this->closeDb();
            return $q;
        } catch (Exception $e) {
            $this->closeDb();
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function beginTx(){
        pg_query($this->conn, "BEGIN");
    }
    public function commitTx() {
        pg_query($this->conn, "COMMIT");
    }
    public function rollbackTx() {
        pg_query($this->conn, "ROLLBACK");
    }
    public function executeTransaction($queries){
        $this->connDb();
        $this->beginTx();
        $res = array();
        try {
            foreach ($queries as $query) {
                $result = pg_query_params($this->conn, $query['sql'], $query['params']);
                if (!$result) {
                    error_log("Error en la consulta: " . pg_last_error());
                    $this->rollbackTx();
                    return false;
                }
                array_push($res,pg_fetch_all($result));
            }
            $this->commitTx();
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->rollbackTx();
            $this->closeDb();
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
