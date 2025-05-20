<?php
$mysqli = new mysqli("localhost", "root", "", "nintendo");
if ($mysqli->connect_errno) {
    die("Ошибка подключения к БД: " . $mysqli->connect_error);
}

// Добавление игры
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_game'])) {
    $stmt = $mysqli->prepare("INSERT INTO games (title, genre, release_date, developer, console_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $_POST['title'], $_POST['genre'], $_POST['release_date'], $_POST['developer'], $_POST['console_id']);
    $stmt->execute();
    header("Location: games.php");
    exit();
}

// Удаление
if (isset($_GET['delete'])) {
    $stmt = $mysqli->prepare("DELETE FROM games WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: games.php");
    exit();
}

// Обновление
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_game'])) {
    $stmt = $mysqli->prepare("UPDATE games SET title=?, genre=?, release_date=?, developer=?, console_id=? WHERE id=?");
    $stmt->bind_param("ssssii", $_POST['title'], $_POST['genre'], $_POST['release_date'], $_POST['developer'], $_POST['console_id'], $_POST['id']);
    $stmt->execute();
    header("Location: games.php");
    exit();
}

$result = $mysqli->query("SELECT games.id, title, genre, release_date, developer, consoles.name AS console_name,
                          consoles.release_year, consoles.generation, consoles.discontinued
                          FROM games JOIN consoles ON games.console_id = consoles.id");

$consoles = $mysqli->query("SELECT * FROM consoles");

$edit_game = null;
if (isset($_GET['edit'])) {
    $stmt = $mysqli->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_game = $stmt->get_result()->fetch_assoc();
}

// Начало HTML
echo <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Игры</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        th {
            background: #f0f0f0;
        }
        .form-container {
            margin-top: 30px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            padding: 5px;
            width: 100%;
        }
        button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #2d77f4;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header class="header">
  <div class="header__inner container">
    <div class="header__logo-title">
      <img class="header__logo" src="../images/header.png" alt="Шапка сайта">
      <h1 class="header__title">Игры</h1>
    </div>
    <nav class="header__menu">
      <ul class="header__menu-list">
        <li class="header__menu-item"><a href="../index.html" class="header__menu-link">Главная</a></li>
        <li class="header__menu-item"><a href="../pages/consoles.html" class="header__menu-link">Консоли</a></li>
        <li class="header__menu-item"><a href="../pages/sources.html" class="header__menu-link">Источники</a></li>
        <li class="header__menu-item"><a href="../pages/scenarios.html" class="header__menu-link">Сценарии</a></li>
        <li class="header__menu-item"><a href="../pages/form.html" class="header__menu-link">Анкета</a></li>
        <li class="header__menu-item"><a href="games.php" class="header__menu-link">Игры</a></li>
        <li class="header__menu-item"><a href="../pages/ajax_database.php" class="header__menu-link">AJAX-доступ</a></li>
        <li class="header__menu-item"><a class="header__menu-link" href="../pages/sales.html">Продажи консолей</a></li>
        <li class="header__menu-item"><a class="header__menu-link" href="../pages/consoles_xml.html">Консоли (XML)</a></li>
      </ul>
    </nav>
  </div>
</header>
<hr>
<main class="main container">
<h2>Список игр</h2>
<table>
<tr>
    <th>Название</th>
    <th>Жанр</th>
    <th>Дата выхода</th>
    <th>Разработчик</th>
    <th>Консоль</th>
    <th>Год</th>
    <th>Поколение</th>
    <th>Снята с производства</th>
    <th>✏️</th>
    <th>🗑️</th>
</tr>
HTML;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        printf("<tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%d</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href='games.php?edit=%d'>✏️</a></td>
            <td><a href='games.php?delete=%d' onclick='return confirm(\"Удалить игру?\")'>🗑️</a></td>
        </tr>",
        htmlspecialchars($row['title']),
        htmlspecialchars($row['genre']),
        htmlspecialchars($row['release_date']),
        htmlspecialchars($row['developer']),
        htmlspecialchars($row['console_name']),
        $row['release_year'],
        htmlspecialchars($row['generation']),
        $row['discontinued'] ? 'Да' : 'Нет',
        $row['id'],
        $row['id']
        );
    }
}
echo "</table>";

echo "<div class='form-container'><h2>" . ($edit_game ? "Редактировать игру" : "Добавить игру") . "</h2>";
echo "<form method='post'>";
echo "<input type='hidden' name='" . ($edit_game ? "update_game" : "add_game") . "' value='1'>";
if ($edit_game) {
    echo "<input type='hidden' name='id' value='{$edit_game['id']}'>";
}
printf("<label>Название: <input type='text' name='title' required value='%s'></label>", htmlspecialchars($edit_game['title'] ?? ''));
printf("<label>Жанр: <input type='text' name='genre' required value='%s'></label>", htmlspecialchars($edit_game['genre'] ?? ''));
printf("<label>Дата выхода: <input type='date' name='release_date' required value='%s'></label>", htmlspecialchars($edit_game['release_date'] ?? ''));
printf("<label>Разработчик: <input type='text' name='developer' required value='%s'></label>", htmlspecialchars($edit_game['developer'] ?? ''));
echo "<label>Консоль: <select name='console_id'>";
foreach ($consoles as $console) {
    $selected = ($edit_game && $edit_game['console_id'] == $console['id']) ? 'selected' : '';
    printf("<option value='%d' %s>%s (%d, %s)</option>",
        $console['id'],
        $selected,
        htmlspecialchars($console['name']),
        $console['release_year'],
        htmlspecialchars($console['generation'])
    );
}
echo "</select></label>";
echo "<button type='submit'>" . ($edit_game ? "Сохранить" : "Добавить") . "</button>";
if ($edit_game) {
    echo " <a href='games.php'>Отмена</a>";
}
echo "</form></div>";
echo "</main></body></html>";
?>
