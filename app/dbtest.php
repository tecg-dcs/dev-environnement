<?php

try {
    $db = new \PDO('mysql:host=mariadb;port=3306;charset=utf8', 'root', 'rootpass', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ));
    echo 'Connection établie';
} catch (\PDOException $pe) {
    echo $pe->getMessage();
}
