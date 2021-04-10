<?php

$filename = 'comment.txt';
$messagefile = 'message.txt';

$comment_data = null;
$fp = null;
$message = array();
$message_array = array();
$error_message = array();

if (!empty($_POST['btn-submit']) ) {
  
  if ($fp = fopen($filename, "a") ) {

    //$_POST['comment']が空の時 
    if( empty($_POST['comment']) ) {
      $error_message[] = 'コメントは必須です。';
    }

    // $error_messageが空の時
    if( empty($error_message) ) {

      $comment_data = $_POST['comment'].",".$_GET['num']."\n";

      fwrite($fp, $comment_data);

      fclose($fp);

    }
  }
}



if ($fp = fopen($filename, 'r')) {

  $public_array = array();
  
  while ($comment_data = fgets($fp)) { //$comment_dataに１行ずつ$fpのデータを代入

    $split_data = explode( ',',$comment_data); //$split_dataにexplodeで$comment_dataの中の文字列を「,」で分割して代入
    // var_dump($split_data);

    // $messageに配列を代入
    $message = array( 
      'comment' => $split_data[0], //$comment_data[0]を$comment_data['comment']にする
      'num' => $split_data[1]
    );
    

    array_unshift($message_array, $message); //$message_arrayに$messageを先頭から順に配置する
    array_push($public_array, $message);
  }

  fclose($fp);  //ファイルを閉じる
}

if ($file_handle = fopen($messagefile, 'r')) {
  while ($data = fgets($file_handle)) { //$dataに１行ずつ$file_handleのデータを代入

    $message_data = explode( ',',$data); //$split_dataにexplodeで$dataの中の文字列を「,」で分割して代入

    // $messageに配列を代入
    $message_ary = array( 
      'title' => $message_data[0], //$split_data[0]を$split_data['title']にする
      'text' => $message_data[1],  //$split_data[1]を$split_data['text']にする
      'num' => $message_data[2]
    );

    fclose($messagefile);  //ファイルを閉じる
    }
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
    <h3><?php echo $_GET['title']; ?></h3>
    <p><?php echo $_GET['text']; ?></p>
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

    <?php foreach($public_array as $value):
          $num  = trim($value['num'], "\n");
      ?>   <!--$message_arrayから$valueに[]を配置していく -->
      <?php if($num == $_GET['num']) :?> <!--$message_arrayが空じゃ無い時 -->
  <form action="" method="POST" class="other_comment">
    <div class="text-form">
      <div class="textarea" name="comment"><?php echo $value['comment'];?></div>
      <input class="btn-submit" type="submit"  name="delete" value="コメントを消す">
    </div>
  </form>
    <?php endif ?>
  <?php endforeach; ?>

</section>
</body>
</html>