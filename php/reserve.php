<?php
// Db connection
class Database {
    private $host = "localhost";
    private $db_name = "restaurant";
    private $username = "root";
    private $password = "";
    public $conn;

    // Connect db
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
            $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    // Save reserve
    public function saveReserve($date, $input_time, $people, $idCard, $name, $email) {
        $query = "INSERT INTO reservation (idCard, name, email, date, input_time, people) 
        VALUES (:idCard, :name, :email, :date, :input_time, :people)";
        $stmt = $this->conn->prepare($query);
        
        // Link parameters
        $stmt->bindValue(':idCard', $idCard);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':input_time', $input_time);
        $stmt->bindValue(':date', $date);
        $stmt->bindValue(':people', $people);
        
        return $stmt->execute();
    }
}

// --- Uso de la clase ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $idCard = trim($_POST['idCard'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $input_time = trim($_POST['input_time'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $people = trim($_POST['people'] ?? '');

    if ($db->saveReserve($date, $input_time, $people, $idCard, $name, $email)) {
        header("Location: ../reservation.html?status=successReserve");
        exit;
    }
    else {
        header("Location: ../reservation.html?status=error");
        exit;
    }
}
?>