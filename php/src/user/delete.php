<?php
    session_start();

    // login.php ログインされているか検証 & 管理者チェック
    if (!isset($_SESSION['id']) && !isset($_SESSION['name']) && $_SESSION['role'] == 1){
        header('Location: login.php');
        exit();
    }

    // データベース接続
    require_once('../db/dbconnect.php');

    // list.php id取得
    $listId = $_GET['id'];

    // SQL作成
    $deleteSql = $mysqli->prepare('delete from users where id = ?');
    if (empty($deleteSql)) {
        die($mysqli->error);
    }

    // 値セット
    $deleteSql->bind_param('i', $listId);

    // SQL実行
    $success = $deleteSql->execute();
    if (empty($success)) {
        die($mysqli->error);
    } else {
        header('Location: list.php');
        exit();
    }
?>