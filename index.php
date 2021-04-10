<?php
// メッセージを保存するファイルのパス設定
define( 'FILENAME', 'message.txt');

$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();

// $_POST['send_news']が空じゃない時
if(!empty($_POST['send_news']) ) {

  // FILENAMEを追記で書き込むために開く時$file_handleへ代入
	if( $file_handle = fopen( FILENAME, "a") ) {  

    //$_POST['title']が空の時 
    if( empty($_POST['title']) ) {
      $error_message[] = 'タイトルは必須です。';
    }

    // $_POST['text']が空の時
    if( empty($_POST['text']) ) {
      $error_message[] = '記事は必須です。';
    }

    // $error_messageが空の時
    if( empty($error_message) ) {

      // FILENAMEが存在する時
      if (file_exists(FILENAME)) {
        $num = count(file(FILENAME))+1; //FILENAMEのファイルを読み込み、配列数を取得+1したのを$numに代入
    } else { 
        $num = 1; //存在しない時は$num=1
    }
    
      // $dataに書き込むデータを作成
      $data = $_POST['title'].",".$_POST['text'].",".$num."\n";
      
      // $file_handleに$dataを書き込み (file_handle = fopen( FILENAME, "a"))
      fwrite( $file_handle, $data);
      
      // ファイルを閉じる
      fclose( $file_handle);
    }	
  }
}

  // FILENAMEから読み取るために開く時$file_handleへ代入
if ($file_handle = fopen(FILENAME, 'r')) {
  while ($data = fgets($file_handle)) { //$dataに１行ずつ$file_handleのデータを代入

    $split_data = explode(',',$data); //$split_dataにexplodeで$dataの中の文字列を「,」で分割して代入

    // $messageに配列を代入
    $message = array( 
      'title' => $split_data[0], //$split_data[0]を$split_data['title']にする
      'text' => $split_data[1],  //$split_data[1]を$split_data['text']にする
      'num' => $split_data[2]
    );
    array_unshift($message_array, $message); //$message_arrayに$messageを先頭から順に配置する
  }


  fclose($file_handle);  //ファイルを閉じる
}
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
    
    <?php if( !empty($error_message) ): ?>  <!--$error_messageの中が空じゃ無い時 -->
      <ul>
        <?php foreach( $error_message as $value ): ?>  <!--$error_messageから$valueに[]を配置していく -->
          <li><?php echo $value; ?></li>  <!--$valueに入った$error_message[0]と[1]を順に表示 -->
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
    if (!empty($message_array)): ?> <!--$message_arrayが空じゃ無い時 -->
    <?php foreach($message_array as $value): ?>   <!--$message_arrayから$valueに[]を配置していく -->
      <section id="comment">
    <div class="comment-in">
      <h3><?php echo $value['title']; ?></h3> <!--$valueに入った['title']を表示 -->
      <p><?php echo $value['text']; ?></p>  <!--$valueに入った['text']を表示 -->
      <p><a href="comment.php?title=<?php echo $value['title']; ?> &text=<?php echo $value['text'];?> &num=<?php echo $value['num'];?>">記事全文・コメントを見る</a></p>
    </div>
  </section>
    <?php endforeach; ?>
    <?php endif ?>
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