<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('Ajax処理');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(isset($_POST['tripid']) && isset($_SESSION['user_id']) && isLogin()){
  debug('POST送信があります。');
  $t_id = $_POST['tripid'];
  debug('たびID：'.$t_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // レコードがあるか検索
    $sql = 'SELECT * FROM favorite WHERE trip_id = :t_id AND user_id = :u_id';
    $data = array(':u_id' => $_SESSION['user_id'], ':t_id' => $t_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    debug($resultCount);
    // レコードが１件でもある場合
    if(!empty($resultCount)){
      // レコードを削除する
      $sql = 'DELETE FROM favorite WHERE trip_id = :t_id AND user_id = :u_id';
      $data = array(':u_id' => $_SESSION['user_id'], ':t_id' => $t_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
//      debug(count(getfavoCount($t_id)));
      echo count(getfavoCount($t_id));//追加
    }else{
      // レコードを挿入する
      $sql = 'INSERT INTO favorite (trip_id, user_id, create_date) VALUES (:t_id, :u_id, :date)';
      $data = array(':t_id' => $t_id, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      echo count(getfavoCount($t_id));//追加
      debug(count(getfavoCount($t_id)));
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>