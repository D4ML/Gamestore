<?php
include_once 'db.php';

class Game {
    private $conn;
    private $table_name = "games";

    public $id;
    public $name;
    public $description;
    public $pegi;
    public $genre;
    public $price;
    public $quantity;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readLatest() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

$database = new Database();
$db = $database->getConnection();
$game = new Game($db);

if ($_GET['action'] == 'latest') {
    $stmt = $game->readLatest();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($games);
}
?>
