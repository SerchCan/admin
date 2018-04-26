<?php
    class PDORepository{
        const USERNAME="root";
        const PASSWORD="";
        const HOST="localhost";
        const DB="store";

        private function getConnection(){
            $username = self::USERNAME;
            $password = self::PASSWORD;
            $host = self::HOST;
            $db = self::DB;
            $connection = new PDO("mysql:dbname=$db;host=$host", $username, $password);
            return $connection;
        }
        /**
         * Query list
         * @param SQL Query
         * @param array Arguments passed to query
         * @return Result FetchIt as you need (fetch all or single record).
         */
        public function queryList($sql, $args){
            $connection = $this->getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        }
    }
?>