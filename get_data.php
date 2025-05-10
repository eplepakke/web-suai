<?php
header('Content-Type: application/json; charset=utf-8');

$mysqli = new mysqli("localhost", "root", "", "nintendo");
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Ошибка подключения к БД: ' . $mysqli->connect_error]);
    exit();
}
$mysqli->set_charset("utf8");

$console_name = $_POST['console'] ?? '';

// Запрос с фильтром по консоли, если задана
if ($console_name) {
    $stmt = $mysqli->prepare("
        SELECT g.title, g.genre, g.release_date, g.developer,
               c.name AS console_name, c.release_year, c.generation, c.discontinued
        FROM games g
        JOIN consoles c ON g.console_id = c.id
        WHERE c.name = ?
    ");
    $stmt->bind_param("s", $console_name);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $mysqli->query("
        SELECT g.title, g.genre, g.release_date, g.developer,
               c.name AS console_name, c.release_year, c.generation, c.discontinued
        FROM games g
        JOIN consoles c ON g.console_id = c.id
    ");
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $row['discontinued'] = $row['discontinued'] ? 'Да' : 'Нет';
    $data[] = $row;
}

echo json_encode($data);
?>
