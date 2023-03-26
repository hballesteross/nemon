<?php

    class Database {

        private $host;
        private $database;
        private $user;
        private $pass;
        public $conn;

        public function __construct($host, $database, $user, $pass) {

            $this->host = $host;
            $this->database = $database;
            $this->user = $user;
            $this->pass = $pass;

        }

        public function conectar() {

            try {

                $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }
        }

        public function desconectar() {
            $this->conn = null;
        }

        public function insert($dominio) {
            try {
                $stmt = $this->conn->prepare("INSERT INTO resultados ( dominio) VALUES (:dominio)");
             
                $stmt->bindParam(':dominio', $dominio);
    
                $stmt->execute();
                return true;
            } catch(PDOException $e) {
                echo "Error al insertar dominio: " . $e->getMessage();
                return false;
            }
        }

        public function getResultados() {

            try {
                $stmt = $this->conn->prepare("SELECT dominio,count(*) as total FROM resultados group by dominio order by total DESC");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Error al obtener resultados: " . $e->getMessage();
                return array();
            }
        }

        public function cleanDB(){

            try {

                // La forma correcta pero por problemas con el usuario de la db en el hosting no tengo permisos para TRUNCATE. 
                // En localhost funciona sin problemas

                // $stmt = $this->conn->prepare("TRUNCATE TABLE resultados");  
                
                $stmt = $this->conn->prepare("DELETE FROM resultados where 1=1");
                $stmt->execute();
                return true;
            } catch(PDOException $e) {
                echo "Error al obtener resultados: " . $e->getMessage();
                return false;
            }

        }

    }

