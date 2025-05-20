<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>AJAX: Игры Nintendo</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        table { width: 90%; margin: auto; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f2f2f2; }
        .filter-container { text-align: center; margin: 20px; }
        #browserInfo { text-align: center; margin-bottom: 15px; font-style: italic; }
    </style>
</head>
<body>
<header class="header">
  <div class="header__inner container">
    <div class="header__logo-title">
      <img class="header__logo" src="../images/header.png" alt="Шапка сайта">
      <h1 class="header__title">AJAX: Игры</h1>
    </div>
    <nav class="header__menu">
      <ul class="header__menu-list">
        <li class="header__menu-item">
            <a class="header__menu-link" href="../index.html">Главная</a>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="../pages/consoles.html">Консоли</a>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="../pages/sources.html">Источники</a>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="../pages/scenarios.html">Сценарии</a>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="../pages/form.html">Анкета</a>
        </li>
        <li class="header__menu-item"><a href="../pages/games.php" class="header__menu-link">Игры</a></li>
        <li class="header__menu-item"><a href="../pages/ajax_database.php" class="header__menu-link">AJAX-доступ</a></li>
        <li class="header__menu-item"><a class="header__menu-link" href="../pages/sales.html">Продажи консолей</a></li>
        <li class="header__menu-item"><a class="header__menu-link" href="../pages/consoles_xml.html">Консоли (XML)</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="main container">
    <div class="filter-container">
        <label>Фильтр по консоли:
            <select id="consoleSelect" onchange="loadData()">
                <option value="">Все</option>
                <?php
                $conn = new mysqli("localhost", "root", "", "nintendo");
                $conn->set_charset("utf8");
                $res = $conn->query("SELECT DISTINCT name FROM consoles ORDER BY name");
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
                $conn->close();
                ?>
            </select>
        </label>
    </div>

    <div id="browserInfo"></div>

    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Жанр</th>
                <th>Дата выхода</th>
                <th>Разработчик</th>
                <th>Консоль</th>
                <th>Год</th>
                <th>Поколение</th>
                <th>Снята с производства</th>
            </tr>
        </thead>
        <tbody id="dataBody"></tbody>
    </table>
</main>
<footer class="footer">
  <address class="footer__address">
    <a href="mailto:walawkizi@gmail.com">walawkizi@gmail.com</a>
    <p> © Nintendo. Все права защищены.</p>
  </address>
</footer>

<script>
function loadData() {
    let xhr = new XMLHttpRequest();
    let console = document.getElementById("consoleSelect").value;
    let params = "console=" + encodeURIComponent(console);

    // Определим браузер
    let ua = navigator.userAgent;
    let browser = "Неизвестный";
    if (ua.includes("Chrome")) browser = "Chrome";
    else if (ua.includes("Firefox")) browser = "Firefox";
    else if (ua.includes("Safari")) browser = "Safari";
    else if (ua.includes("Edg")) browser = "Edge";

    document.getElementById("browserInfo").innerText =
        "XMLHttpRequest: " + xhr.constructor.name + ", Браузер: " + browser;

    xhr.open("POST", "../get_data.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        let tbody = document.getElementById("dataBody");
        tbody.innerHTML = "";
        try {
            let data = JSON.parse(this.responseText);
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="8">${data.error}</td></tr>`;
                return;
            }
            data.forEach(row => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${row.title}</td>
                    <td>${row.genre}</td>
                    <td>${row.release_date}</td>
                    <td>${row.developer}</td>
                    <td>${row.console_name}</td>
                    <td>${row.release_year}</td>
                    <td>${row.generation}</td>
                    <td>${row.discontinued}</td>
                `;
                tbody.appendChild(tr);
            });
        } catch (e) {
            tbody.innerHTML = `<tr><td colspan="8">Ошибка загрузки данных</td></tr>`;
        }
    };
    xhr.send(params);
}
window.onload = loadData;
</script>
</body>
</html>
