<?php
class DatabaseController {
    private $conn;

    public function __construct($config) {
        try {
            $this->conn = new PDO(
                "sqlsrv:server={$config['db_host']};Database={$config['db_name']}",
                $config['db_user'],
                $config['db_pass']
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
