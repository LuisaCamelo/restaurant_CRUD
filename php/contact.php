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
    public function saveMessage($name, $email, $message) {
        $query = "INSERT INTO contact (name, email, message) 
        VALUES (:name, :email, :message)";
        $stmt = $this->conn->prepare($query);
        
        // Link parameters
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':message', $message);
        
        return $stmt->execute();
    }
}

// --- Uso de la clase ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (strlen($message) < 20) {
    header("Location: ../contact.html?status=short");
    exit;
    }
    elseif ($db->saveMessage($name, $email, $message)) {
        header("Location: ../contact.html?status=success");
        exit;
    }
    else {
        header("Location: ../contact.html?status=error");
        exit;
    }
}
?>