<?php
echo 'helloworld';

$mysqli = new mysqli('faq_db', 'root_user', 'root', 'study');
if($mysqli->connect_error) {
    echo '接続失敗'.PHP_EOL;
    exit();
} else {
    echo '接続成功'.PHP_EOL;
}

var_dump($mysqli);
// echo $mysqli->client_info;
