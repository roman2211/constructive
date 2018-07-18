<?php
/**
  * Настройки подключения к базе данных выносится в отдельный файл
 */
include_once("db.php");

/**
 * @param PDO $pdo
 * @param string $ids
 * @return mixed
 */
function loadUsersData(string $ids, $pdo)
{
    $data = [];
    $ids = explode(',', $ids);
    if (count($ids) < 1) {
        return $data;
    }
    /**
     * PDO bind.
     */
    $in = str_repeat('?,', count($ids) - 1) . '?';
    $query = $pdo->prepare("SELECT * FROM users WHERE id IN ($in)");
    $query->execute($ids);
    while ($user = $query->fetchObject()) {
        $data[$user->id] = $user->username;
    }
    return $data;
}
/**
 * Вынесем рендер в отдельный метод.
 * Сделаем вывод немного удобней, чтобы не нужно было экранировать символы, если придется дописывать больше html кода.
 * @param $data
 */
function render($data)
{
    foreach ($data as $uid => $name) {
        echo <<<HTML
   <a href="show_user.php?id=$uid">$name</a>
HTML;
    }
}
/**
 * Подключение к базе данных делается не в цикле.
 */
$dsn = "mysql:dbname=$db;host=$host";
try {
    $connection = new PDO($dsn, $user, $pw);
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48
    /**
     * Добавим проверку на пустое значение user_ids.
     */
    $data = loadUsersData($_GET['user_ids'] ?? '', $connection);
    render($data);
} catch (PDOException $e) {
    //log $e;
    echo 'Fail: check logs';
}