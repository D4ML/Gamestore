<?php
include_once 'db.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password, address=:address";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->address = htmlspecialchars(strip_tags($this->address));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':address', $this->address);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_POST['action'] == 'register') {
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->address = $_POST['address'];
    if ($user->register()) {
        echo json_encode(array("message" => "User was created."));
    } else {
        echo json_encode(array("message" => "Unable to create user."));
    }
}
?>
