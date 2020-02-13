<?php
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = false;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}
//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();
//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
debug('===============画面処理開始=================');
debug('セッションID：'.session_id());
debug('セッション変数の中身'.print_r($_SESSION,true));
debug('現在日時タイムスタンプ'.time());
if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
debug('ログイン期限日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
}
}
//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02','Email形式が正しくありません');
define('MSG03','パスワード再入力が一致しません');
define('MSG04','半角英数字で入力してください');
define('MSG05','6文字以上で入力してください');
define('MSG06','255字以内で入力してください');
define('MSG07','システムエラーが発生しました。');
define('MSG08','既に登録されたemailです');
define('MSG09','ユーザーネームとパスワードが一致していません');
define('MSG15','正しくありません');
define('MSG20','既に登録されたユーザーネームです');
define('SUC02','プロフィール情報を更新しました');
define('SUC03','投稿が完了しました');
define('SUC04','登録しました！');
define('SUC05','削除が完了しました');
define('SUC06','投稿が完了しました');
define('SUC07','ユーザー登録が完了しました。プロフィール画像はマイページ ＞ プロフィール編集で設定することができます！');
define('SUC08','ログインしました');

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();
//================================
// ログイン認証
//================================
function isLogin(){
  // ログインしている場合
  if( !empty($_SESSION['login_date']) ){
    debug('ログイン済みユーザーです。');

    // 現在日時が最終ログイン日時＋有効期限を超えていた場合
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('ログイン有効期限オーバーです。');

      // セッションを削除（ログアウトする）
      session_destroy();
      return false;
    }else{
      debug('ログイン有効期限以内です。');
      return true;
    }

  }else{
    debug('未ログインユーザーです。');
    return false;
  }
}
//================================
// バリデーション関数
//================================
//エラー表示
function err($key){
  global $err_msg;
  if(!empty($err_msg[$key])) echo sanitize($err_msg[$key]);
}
//最大文字数チェック
function validMaxLen($str,$key, $max = 256){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
//半角英数字のチェック
function validHalf($str,$key){
    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//最小文字数のチェック
function validMinLen($str, $key, $min =6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
//未入力のチェック
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
//パスワードの入力チェック
function validMatch($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg['$key'] = MSG03;
    }
}
//selectboxチェック
function validSelect($str,$key){
    if(!preg_match("/^[0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}
//名前の重複チェック
function validNameDup($name){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $sql = "SELECT count(*) FROM users WHERE username = :name AND delete_flg = 0";
        $data = array(':name' => $name);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty(array_shift($result))){
            $err_msg['name'] = MSG20;
        }
    } catch (exception $e){
        error_log('エラー発生'. $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
function getUser($u_id){
  debug('ユーザー情報を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM users  WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    // クエリ結果のデータを１レコード返却
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
//  return $stmt->fetch(PDO::FETCH_ASSOC);
}

//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
    $dsn = 'mysql:host=aws-and-infra-web.;dbname=***;charset=utf8';
    $user = '*******';
    $password = '********';
    $options =array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    //PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}
    function queryPost($dbh, $sql, $data){
    //クエリー実行
    $stmt = $dbh->prepare($sql);
    //プレイスホルダーに値をセットし、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリ失敗しました');
        debug('SQLエラー'.print_r($stmt->errorInfo(),true));
        $err_msg['common'] =MSG07;
        return 0;
     }
    debug('クエリ実行');
    return $stmt;
}
function getMessagebord($t_id){
    debug('掲示板情報を取得します');
    debug('たびID:'.$t_id);
    //例外処理
    try{
        $dbh = dbConnect();
        $sql = 'SELECT m.message, m.post_date, u.username, u.pic FROM message AS m LEFT JOIN users AS u ON m.user_id = u.id WHERE m.trip_id = :t_id AND m.delete_flg = 0 ORDER BY post_date DESC';
        $data = array(':t_id' => $t_id);
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ成功の場合全データ返却
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
    }
}
function getFormData($str, $flg = false){
  if($flg){
    $method = $_GET;
  }else{
    $method = $_POST;
  }
  global $dbFormData;
  // ユーザーデータがある場合
  if(!empty($dbFormData)){
    //フォームのエラーがある場合
    if(!empty($err_msg[$str])){
      //POSTにデータがある場合
      if(isset($method[$str])){
        return sanitize($method[$str]);
      }else{
        //ない場合（基本ありえない）はDBの情報を表示
        return sanitize($dbFormData[$str]);
      }
    }else{
      //POSTにデータがあり、DBの情報と違う場合
      if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
        return sanitize($method[$str]);
      }else{
        return sanitize($dbFormData[$str]);
      }
    }
  }else{
    if(isset($method[$str])){
      return sanitize($method[$str]);
    }
  }
}
function getPlace(){
    debug('都道府県データを取得します');
    try{
        //DBへ接続
        $dbh = dbConnect();
        //SQL
        $sql = "SELECT * FROM Pref";
        $data = array();
//        クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            //クエリ成功の場合全データ返却
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生' . $e->getMessage());
    }
}
function getPrefList($u_id){
    debug('ユーザーの都道府県一覧データを取得します');
    try{
        //DBへ接続
        $dbh = dbConnect();
        //SQL
//        $sql = "SELECT DISTINCT t.place_id, p.place FROM trip AS t LEFT JOIN pref AS p ON t.place_id = p.place_id WHERE t.user_id = :u_id AND t.delete_flg = 0 ORDER BY t.place_id ASC";
        $sql = "SELECT DISTINCT place_id FROM trip WHERE user_id = :u_id AND delete_flg = 0 ORDER BY place_id ASC";
        $data = array(':u_id' => $u_id);
//        クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          //クエリ成功の場合全データ返却
          return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生' . $e->getMessage());
    }
}
function getTripDetail($u_id, $t_id){
  debug('たび情報を取得します。');
  debug('ユーザーID：'.$u_id);
  debug('たびID：'.$t_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM trip WHERE user_id = :u_id AND trip_id = :t_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id, ':t_id' => $t_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getTripList($currentMinNum = 1, $prefCategory, $emotion, $season, $span = 100){
  debug('たび情報を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // 件数用のSQL文作成
    $sql = 'SELECT trip_id FROM trip';
    if(!empty($prefCategory)){
      $sql .= ' WHERE delete_flg = 0 AND place_id IN (';
      foreach($prefCategory as $value){
        $sql .= $value.',';
      }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    if(!empty($emotion)){
      if(empty($prefCategory)){
        $sql .= ' WHERE delete_flg = 0 AND emotion IN (';
      } else {
        $sql .= ' AND emotion IN (';
      }
      foreach($emotion as $value){
        $sql .= $value.',';
      }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    if(!empty($season)){
      if(empty($prefCategory) && empty($emotion)){
        $sql .= ' WHERE delete_flg = 0 AND season IN (';
      }else{
        $sql .= ' AND season IN (';
      }
      foreach($season as $value){
        $sql .= $value.',';
    }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount(); //総レコード数
    $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
    if(!$stmt){
      return false;
    }
    
    // ページング用のSQL文作成
    $sql = 'SELECT t.trip_id, t.title, t.place_id, t.season, t.emotion, t.comment, t.pic1, t.create_date, u.id, u.username, u.pic FROM trip AS t INNER JOIN users AS u ON t.user_id = u.id';
    
    if(!empty($prefCategory)){
      $sql .= ' WHERE place_id IN (';
      foreach($prefCategory as $value){
        $sql .= $value.',';
      }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    if(!empty($emotion)){
      if(!empty($prefCategory)){
        $sql .= ' WHERE emotion IN (';
      } else {
        $sql .= ' AND emotion IN (';
      }
      foreach($emotion as $value){
        $sql .= $value.',';
      }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    if(!empty($season)){
      if(empty($prefCategory) && empty($emotion)){
        $sql .= ' WHERE season IN (';
      }else{
        $sql .= ' AND season IN (';
      }
      foreach($season as $value){
        $sql .= $value.',';
    }
      $sql = substr( $sql , 0 , strlen($sql)-1 );
      $sql .= ')';
    }
    $sql .= ' order by create_date desc';
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array();
    debug('SQL：'.$sql);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを全レコードを格納
      $rst['data'] = $stmt->fetchAll();
      return $rst;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getfavoCount($t_id){
  debug('いいね数を取得します');
  try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM favorite WHERE trip_id = :t_id';
      $data = array(':t_id' => $t_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
          return $stmt->fetchAll();
      }else{
          return false;
      }
  } catch (Exception $e){
      error_log('エラー発生：'.$e->getMessage());
  }
}
function getPasslist($currentMinNum =1, $category, $passT, $sort, $span =10){
    debug('検索によるpassデータを取得します');
    //例外処理
    try{
        $dbh = dbConnect();
        
        $sql  = 'SELECT * ';
        $sql .= 'FROM manage ';

        $where = '';
        if($category != '' || $passT != ''){

            if($passT != ''){
                $passT = '%'.$passT.'%';
                $where .= 'WHERE ';
                $where .= 'passtitle like '.'\''.$passT.'\' ';
            }
            if($category != ''){
                if($where == ''){
                    $where .= 'WHERE ';
                }else{
                 $where .= 'AND ';
                }
                $where .= 'category_id = '.$category.' ';
            }
        $sql .= $where;
        }
        
        $sql .= 'ORDER BY passhuri ASC; ';
        
        $data = array();
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt->rowCount(); //総レコード数
        $result['total_page'] = ceil($result['total']/$span); //総ページ数
        if(!$stmt){
            return false;
        }
        
        //ページング用のSQL文を作成
        $sql  = 'SELECT * ';
        $sql .= 'FROM manage ';
        
        $where = '';
        if($category != '' || $passT != ''){

            if($passT != ''){
                $passT = '%'.$passT.'%';
                $where .= 'WHERE ';
                $where .= 'passtitle like '.'\''.$passT.'\' ';
            }
            if($category != ''){
                if($where == ''){
                    $where .= 'WHERE ';
                }else{
            $where .= 'AND ';
                }
            $where .= 'category_id = '.$category.' ';
            }
            $sql .= $where;
        }
        
        $sql .= 'ORDER BY passhuri ASC';
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum.';';

        $data = array();
        debug('あなたが書いたSQL：'.$sql);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ結果の全レコードを格納
            $result['data'] = $stmt->fetchAll();
            return $result;
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}
function getMyTripData($u_id){
    debug('自分が登録したたびの一覧をDBから取得します');
    debug('ユーザーID:'.$u_id);
    //例外処理
    try{
        $dbh = dbConnect();
        //SQL作成
        $sql = 'SELECT * FROM trip WHERE user_id = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //クエリ結果の全レコードを返却
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生:' . $e->getMessage());
    }
}
function getTripOne($t_id){
  debug('メイン画面からのたび詳細情報を取得します。');
  debug('たびID：'.$t_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT t.trip_id , t.title , t.season, t.emotion, t.comment, t.ido, t.keido, t.pic1, t.pic2, t.pic3, t.user_id, t.create_date, p.place, u.username, u.pic FROM trip AS t LEFT JOIN Pref AS p ON t.place_id = p.place_id LEFT JOIN users AS u ON t.user_id = u.id WHERE t.trip_id = :t_id AND t.delete_flg = 0';
    $data = array(':t_id' => $t_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getUserOne($u_id){
  debug('ユーザー情報を取得します');
  debug('ユーザーID：'.$u_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT u.username, u.pic FROM users AS u WHERE u.id = :u_id';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getUserList($u_id){
  debug('ユーザー詳細情報を取得します。');
  debug('ユーザーID：'.$u_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT t.trip_id , t.title , t.place_id , t.season, t.emotion, t.comment, t.pic1, t.pic2, t.pic3, t.user_id, t.create_date, p.place, u.username FROM trip AS t LEFT JOIN Pref AS p ON t.place_id = p.place_id LEFT JOIN users AS u ON t.user_id = u.id WHERE t.user_id = :u_id AND t.delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果の全レコードを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function isLike($u_id, $p_id){
  debug('お気に入り情報があるか確認します。');
  debug('ユーザーID：'.$u_id);
  debug('ID：'.$p_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
    $data = array(':u_id' => $u_id, ':p_id' => $p_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt->rowCount()){
      debug('お気に入りです');
      return true;
    }else{
      debug('特に気に入ってません');
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getfavorite($u_id, $t_id){
    debug('お気に入り情報があるか確認します');
    debug('たびID:'.$t_id);
//    例外処理
    try{
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT * FROM favorite WHERE user_id = :u_id AND trip_id = :t_id';
        $data = array(':u_id' => $u_id, ':t_id' => $t_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt->rowCount()){
            debug('お気に入りです');
            return true;
        }else{
            debug('時に気に入っていません');
            return false;
        }
    } catch (Exception $e){
        error_log('エラー発生:' . $e->getMessage());
    }
}

function getfavoriteList($u_id){
    debug('お気に入りした情報を取得します');
    debug('ユーザーID:'.$u_id);
//    例外処理
    try{
    //DBへ接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT t.trip_id , t.title , t.season, t.emotion, t.comment, t.pic1, t.user_id, t.create_date, f.trip_id, f.user_id FROM trip AS t LEFT JOIN favorite AS f ON t.trip_id = f.trip_id WHERE f.user_id = :u_id AND t.delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果の全レコードを返却
      return $stmt->fetchAll();
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
function getImg(){
  debug('画像を取得します。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT pic1, create_date from trip WHERE delete_flg = 0 order by create_date desc limit 6';
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを１レコード返却
      return $stmt->fetchAll();
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

//================================
// その他
//================================
//sanitize XSS対策
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}
//GETパラメータ付与
function appendGetParam($arr_del_key = array()){
    if(!empty($_GET)){
        $str = '?';
        foreach($_GET as $key => $val){
            if(!in_array($key,$arr_del_key,true)){
                $str = $key. '=' .$val. '&';
            }
        }
        $str = mb_substr($str, 0, -1, "UTF-8");
    }
}
// 画像処理
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('FILE情報：'.print_r($file,true));
  
  if (isset($file['error']) && is_int($file['error'])) {
    try {
      // バリデーション
      // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
      //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
      switch ($file['error']) {
          case UPLOAD_ERR_OK: // OK
              break;
          case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
              throw new RuntimeException('ファイルが選択されていません');
          case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
          case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
              throw new RuntimeException('ファイルサイズが大きすぎます');
          default: // その他の場合
              throw new RuntimeException('その他のエラーが発生しました');
      }

      // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
          throw new RuntimeException('画像形式が未対応です');
      }

      // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
      // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      // image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
          throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      // 保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);
      
      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：'.$path);
      return $path;

    } catch (RuntimeException $e) {

      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();

    }
  }
}
//ページネーション
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
  // 現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク４個出す
  if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum - 4;
    $maxPageNum = $currentPageNum;
  // 現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
  }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum - 3;
    $maxPageNum = $currentPageNum + 1;
  // 現ページが2の場合は左にリンク１個、右にリンク３個だす。
  }elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum - 1;
    $maxPageNum = $currentPageNum + 3;
  // 現ページが1の場合は左に何も出さない。右に５個出す。
  }elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
    $minPageNum = $currentPageNum;
    $maxPageNum = 5;
  // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
  }elseif($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
  // それ以外は左に２個出す。
  }else{
    $minPageNum = $currentPageNum - 2;
    $maxPageNum = $currentPageNum + 2;
  }
  
  echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
      if($currentPageNum != 1){
        echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
      }
      for($i = $minPageNum; $i <= $maxPageNum; $i++){
        echo '<li class="list-item ';
        if($currentPageNum == $i ){ echo 'active'; }
        echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
      }
      if($currentPageNum != $maxPageNum && $maxPageNum > 1){
        echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
      }
    echo '</ul>';
  echo '</div>';
}
//画像表示用関数
function showImg($path){
  if(empty($path)){
    return 'img/sample-img.png';
  }else{
    return $path;
  }
}
//sessionを１回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}
?>
