<?php
class TableroController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getChartData() {
        $data = $this->db->query("SELECT * FROM PersonasVoluntariasAspirantes_NAY");
        echo json_encode($data);
    }

    public function getQueryData($query) {
        $data = $this->db->query($query);
        echo json_encode($data);
    }
}
