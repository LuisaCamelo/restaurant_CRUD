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

    public function emailExists($email) {
    $query = "SELECT id FROM subscription WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->rowCount() > 0;
    }


    // Save subscription
    public function saveSubscription($email) {
        $query = "INSERT INTO subscription (email, subscription) 
        VALUES (:email, 1)";
        $stmt = $this->conn->prepare($query);
        
        // Link parameters
        $stmt->bindValue(':email', $email);
        
        return $stmt->execute();
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.html?status=invalid");
        exit;
    }
    elseif ($db->emailExists($email)) {
        header("Location: ../index.html?status=exists");
        exit;
    }
    elseif ($db->saveSubscription($email)) {
        header("Location: ../index.html?status=success");
        exit;
    }
    else {
        header("Location: ../index.html?status=error");
        exit;
    }
}
?>