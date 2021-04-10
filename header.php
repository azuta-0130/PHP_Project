<?php
// メッセージを保存するファイルのパス設定
define( 'FILENAME', 'message.txt');

$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();

if(!empty($_POST['send_news']) ) {

	if( $file_handle = fopen( FILENAME, "a") ) {

    if( empty($_POST['title']) ) {
      $error_message[] = 'タイトルは必須です。';
    }

    if( empty($_POST['text']) ) {
      $error_message[] = '記事は必須です。';
    }

    if( empty($error_message) ) {

      
      // 書き込むデータを作成
      $data = $_POST['title'].",".$_POST['text']."\n";
      
      // 書き込み
      fwrite( $file_handle, $data);
      
      // ファイルを閉じる
      fclose( $file_handle);
    }	
  }
}

if ($file_handle = fopen(FILENAME, 'r')) {
  while ($data = fgets($file_handle)) {

    $split_data = explode( ',',$data);

    $message = array(
      'title' => $split_data[0],
      'text' => $split_data[1]
    );
    array_unshift($message_array, $message);
  }
  fclose($file_handle);
}
?>  