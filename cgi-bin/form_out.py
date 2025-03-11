import cgi

form = cgi.FieldStorage()  # Получение данных из формы
print("Content-type: text/html\n")  # Установка типа контента

html_header = """
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Анкета. Ответ сервера</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<section class="nintendo-section">
    <h2 class="nintendo-title">Анкета пользователя</h2>
    <table border="2" class="nintendo-table">
        <tr>
            <th>Имя поля</th>
            <th>Значение</th>
        </tr>
"""

print(html_header)

# Поля формы
fields = [
    ('Фамилия', 'surname'),
    ('Имя', 'name'),
    ('Отчество', 'patronymic'),
    ('Любимая консоль', 'console'),
    ('Любимые игры', 'games'),
    ('Как узнали о Nintendo', 'discovery'),
    ('Электронная почта', 'email'),
    ('Время отправки', 'submission_time')
]

data = {}
for label, field in fields:
    if field not in form:
        value = '(не указано)'
    else:
        if not isinstance(form[field], list):
            value = form[field].value
        else:
            value = ', '.join(x.value for x in form[field])

    data[field] = value
    print(f'<tr><td>{label}</td><td>{value}</td></tr>')

# Формирование фразы с данными пользователя
html_phrase = f"""
    </table>
    <p class="nintendo-summary">
        {data.get('surname', '')} {data.get('name', '')[0]}. {data.get('patronymic', '')[0]}. выбрал консоль: {data.get('console', '')}.
        Любимые игры: {data.get('games', '')}. Узнал о Nintendo: {data.get('discovery', '')}.
        Для связи указал email: {data.get('email', '')}. Время отправки: {data.get('submission_time', '')}. 
    </p>
"""

html_footer = """
</section>
</body>
</html>
"""

print(html_phrase)
print(html_footer)

file_name = "survey_results.txt"

with (open(file_name, "a", encoding="utf-8") as file):
    result = f"{data.get('submission_time', '')};" \
             f"{data.get('surname', '')};{data.get('name', '')};{data.get('patronymic', '')};" \
             f"{data.get('console', '')};{data.get('games', '')};" \
             f"{data.get('discovery', '')};{data.get('email', '')}" \
             f"\n"

    file.write(result)
