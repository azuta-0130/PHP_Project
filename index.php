<?php

//データベースへ接続
function dbConnect() {
    $dsn = 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
    $user = 'root';
    $pass = 'root';

  try {
    $dbh = new PDO($dsn,$user,$pass,[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //エラーモードを例外で出力する
      ]);
  } catch(PDOException $e) {
    echo '接続失敗'. $e->getMessage();
    exit();
  };

  return $dbh;

}

//データベースから取得
    $dbh = dbConnect(); //$dbhにデータベース接続する関数を入れる
    $sql = 'SELECT * FROM articles'; //データベースの'articles'から全部読み取って変数へ代入
    $stmt = $dbh->query($sql); //接続したら読み取ったデータに問い合わせて$stmtに代入
    $result = $stmt->fetchall(PDO::FETCH_ASSOC); //問い合わせたら全部のデータを$resultに代入
    // var_dump($result);
    $dbh = null; //$dbhを初期化

//取得したデータを表示
$error_message = array(); //$error_messageを配列にしとく

if (!empty($_POST['send_news'])) { //投稿を押された時

  if (empty($_POST['title'])) { //
    $error_message[] = 'タイトルは必須です';
  } else if(mb_strlen($_POST['title']) > 30) { 
    $error_message[] = 'タイトルは30文字以下でお願いします。';
  }
   if (empty($_POST['text'])) {
    $error_message[] = '記事は必須です';
  }

  if(empty($error_message)) { //エラーメッセージが空の時
    $dbh = dbConnect();
    $sql = 'INSERT INTO articles (title, text) VALUES (:title, :text)';
    
    try {
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':title',$_POST['title'],PDO::PARAM_STR);
      $stmt->bindValue(':text',$_POST['text'],PDO::PARAM_STR);
      $stmt->execute();
    } catch(PDOException $e) {
      exit($e);
    }
    header ('Location:  ' . $_SERVER['SCRIPT_NAME']); //二重投稿防止
    exit;
  }
} 

// var_dump($error_message);


?>  


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP課題</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
    <a href="index.php">
      <h1>Laravel News</h1>
    </a>
  </header>
  <section id="news">

    <h2>さぁ、最新のニュースをシェアしましょう</h2>
    
    <?php if(!empty($error_message) ): ?>  <!--$error_messageの中が空じゃ無い時 -->
      <ul>
        <?php foreach( $error_message as $value ): ?>  <!--$error_messageから$valueに[](配列)を配置していく -->
          <li><?php  echo $value; ?></li>  <!--$valueに入った$error_message[0]と[1]を順に表示(ループ) -->
          <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <form action="index.php" method="POST" name="form">
      タイトル：<input class="title" type="text" name="title" value=""><br>
      <span>記事：</span><input class="text" type="text" name="text" value=""><br>
      <button type="submit" name="send_news" onclick="MoveCheck();" value="投稿">投稿</button>
    </form>
  </section>
  <?php 
   // if (!empty($message_array)): ?> <!--$message_arrayが空じゃ無い時 -->
    <?php foreach($result as $column): ?>   <!--$message_arrayから$valueに[](配列)を配置していく -->
    <?php //var_dump($column);?>
      <section id="comment">
    <div class="comment-in">
      <h3><?php echo $column['title'] ?></h3> <!--$valueに入った['title']を表示 -->
      <p><?php echo $column['text'] ?></p>  <!--$valueに入った['text']を表示 -->
      <p><a href="comment.php?id=<?php echo $column['id']?>">記事全文・コメントを見る</a></p>
    </div>
  </section>
    <?php endforeach;?>
    <?php //endif ?>
</body>
<footer>
  <ul>
    <li>&lt;</li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">6</a></li>
    <li><a href="#">7</a></li>
    <li><a href="#">8</a></li>
    <li><a href="#">9</a></li>
    <li><a href="#">10</a></li>
    <li>&gt;</li>
  </ul>
</footer>


<script>
function MoveCheck() {
    if( confirm("投稿してよろしいですか？") ) {
      return ture;
    }
    else {
        alert("キャンセルしました");
        return false;
    }
}



</script>
</html>