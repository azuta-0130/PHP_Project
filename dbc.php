<?php

$dsn = 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
$user = 'root';
$pass = 'root';

try {

    $dbh = new PDO($dsn,$user,$pass,[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //エラーモードを例外で出力する
      ]);
      // echo '接続成功';
      $sql = 'SELECT * FROM articles';
      $stmt = $dbh->query($sql);
      $result = $stmt->fetchall(PDO::FETCH_ASSOC);
      var_dump($result);
      $dbh = null;
  } catch(PDOException $e) {
    echo '接続失敗'. $e->getMessage();
    exit();
  };

?>

