<?php

  $id = $_GET['id'];

function dbConnect() {
  $dsn = 'mysql:host=localhost;dbname=laravel_news;charset=utf8';
  $user = 'root';
  $pass = 'root';

  try {
    $dbh = new PDO($dsn,$user,$pass,[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //エラーモードを例外で出力する
    PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    } catch(PDOException $e) {
      echo '接続失敗'. $e->getMessage();
      exit();
  };
  return $dbh;
}

    $dbh = dbConnect();
    $stmt = $dbh->prepare('SELECT * FROM articles Where id = :id');
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM comments'; //データベースの'articles'から全部読み取って変数へ代入
    $comment = $dbh->query($sql); //接続したら読み取ったデータに問い合わせて$commentに代入
    $comments = $comment->fetchall(PDO::FETCH_ASSOC); //問い合わせたら全部のデータを$resultに代入
    $dbh = null; //$dbhを初期化




    $error_message = array(); //$error_messageを配列にしとく

//$_POSTで['btn_submit']が送られてきて、空白じゃない時
if (!empty($_POST['btn-submit']) ) {
   
  if (empty($_POST['comment'])) { //
    $error_message[] = 'コメントは必須です';
  } else if(mb_strlen($_POST['comment']) > 50) { 
    $error_message[] = 'コメントは50文字以下でお願いします。';
  }



  if(empty($error_message)) { //エラーメッセージが空の時
    $dbh = dbConnect();
    $sql = 'INSERT INTO comments (comment,article_id) VALUES (:comment,:article_id)';
    
    try {
      $comments_stmt = $dbh->prepare($sql);
      $comment_data = array(':comment' => $_POST['comment'],'article_id' => $id);
      $comments_stmt->execute($comment_data);
    } catch(PDOException $e) {
      exit($e);
    }
    header ('Location:  ' . $_SERVER['REQUEST_URI']); //二重投稿防止
    exit;
  }
} elseif (isset($_POST['delete_send'])) {
    $delete = $_POST['delete'];
    $dbh = dbConnect();
    $sql = 'DELETE FROM comments WHERE id = :id';
    
  try {
    $comments_stmt = $dbh->prepare($sql);
    $delete_comment = array(':id' => $delete );
    $comments_stmt->execute($delete_comment);
  } catch (PDOException $e){
    exit($e);
  }
    
    header ('Location:  ' . $_SERVER['REQUEST_URI']); //二重投稿防止
    exit;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>コメント</title>
</head>
<body>
  <header>
    <a href="index.php">
      <h1>Laravel News</h1>
    </a>
  </header>
<section id = comment>
  <div class="comment-in">
    <h3><?php echo $result['title']; ?></h3>
    <p><?php echo $result['text']; ?></p>
  </div>
</section>
<?php if( !empty($error_message) ): ?>  <!--$error_messageの中が空じゃ無い時 -->
  <ul>
     <?php foreach( $error_message as $value ): ?>  <!--$error_messageから$valueに[]を配置していく -->
     <li><?php echo $value; ?></li>  <!--$valueに入った$error_message[0]と[1]を順に表示 -->
     <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<section id="comment-form">
  <form action="" method="POST" class="first_comment">
    <div class="text-form">
      <textarea class="textarea" type="text" name="comment" value=""></textarea>
      <input class="btn-submit" type="submit"  name="btn-submit" value="コメントを書く">
    </div>
  </form>
    <?php foreach($comments as $column):?> <!-- $value['num']の空白（"\n"）を削除して$numに代入 -->
  <?php if ($column['article_id'] == $id): ?>
  <form action="" method="POST" class="other_comment">
    <div class="text-form">
      <div class="textarea" name="comment"><?php echo $column['comment'];?></div><!-- $value['comment']を表示 -->
      <input type="hidden"  name="delete" value="<?php echo $column['id'] ?>">
      <input class="btn-submit" type="submit" name="delete_send" value="コメントを消す">
    </div>
  </form>
    <?php endif ?>
    <?php endforeach; ?>

</section>
</body>
</html>