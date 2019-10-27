<?php

/**
 * Class DataBase
 */
class DataBase {
    /**
     * @var PDO
     */
    private $db;

    /**
     * @param array $config
     */
    public function __construct($config) {
        try {
            $connStr = "mysql:host={$config[0]};port=3306;dbname={$config[1]};";
            $this->db = new PDO($connStr, $config[2], $config[3], array());
        } catch (PDOException $e) {
            die("Error database connection: ".$e->getMessage());
        }

    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     */
    public function Query($query, $params) {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error Query: ".$e->getMessage());
        }

    }

} 