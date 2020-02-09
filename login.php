<?php

//共通変数・関数ファイル読み込み
require('function.php');

//debug('================================');
//debug('==========ログインページ===========');
//debug('================================');
//debugLogStart();

//ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります');

//変数にユーザー情報を代入
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

//ゲストユーザーの指定
  if(isset($_POST['guest'])){
    //debug('ゲストユーザーです');
    $name = 'ゲスト';
    $pass = '';
  }

    //パスワードの半角英数字チェック
    validHalf($pass,'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass,'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass,'pass');

    //未入力チェック
    validRequired($name,'name');
    validRequired($pass,'pass');

    if(empty($err_msg)){
        debug('バリデーションOKです');
        //例外処理
        try{
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'SELECT pass,username,id FROM users WHERE username = :username AND delete_flg= 0';
            $data = array(':username' => $name);
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            //クエリ実行の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            debug('クエリ結果の中身'.print_r($result,true));
            //パスワード照合
            if(!empty($result) && password_verify($pass,array_shift($result))){
            debug('パスワードがマッチしました');

            //ログイン有効期限
            $sesLimit = 60*60; //1時間
            //最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            //ログイン保持にチェックあるがある売位
            if($pass_save){
              debug('ログイン保持にチェックがあります');
            //ログイン有効期限を30日にセット
            $_SESSION['login_limit'] = $sesLimit * 24 * 30;
              }else{
                debug('ログン保持にチェックはありません');
            //チェックがないので1時間にセット
                $_SESSION['login_limit'] = $sesLimit;
              }
            //ユーザーネームを格納する
                $_SESSION['user_name'] = $result['username'];
            //ユーザーIDを格納する
                $_SESSION['user_id'] = $result['id'];
                debug('セッション変数の中身'.print_r($_SESSION,true));
                debug('メインページへ遷移します');
            //成功メッセージを格納する
                $_SESSION['msg_success'] = SUC08;
                header('Location:index.php');
            }else{
                debug('パスワードがアンマッチです');
                $err_msg['pass'] = MSG09;
            }
        } catch (Exception $e){
            error_log('エラー発生:' .$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('===============画面表示処理終了=================')
?>
<!--画面表示-->
<?php
    $siteTitle = 'ログイン | Phototavi';
    require('head.php');
?>

<body>
  <?php require('header.php');?>

  <main class="login__main">
    <section class="login__container">
      <form action="" method="post" class="login__form">
        <h2 class="heading">ログインする</h2>
        <label class="
        <?php if(!empty($err_msg['name'])) echo sanitize('err')?>" for="name">ユーザーネーム
          <input type="text" class="first-text" name="name" value="システム" placeholder="ユーザーネーム（必須）">
        </label>
        <div class="area-msg"><?php err('name') ?></div>
        <label class="<?php if(!empty($err_msg['pass'])) echo sanitize('err'); ?>" for="pass">パスワード
          <input type="password" class="first-pass" name="pass" value="system123" placeholder="パスワード（必須）">
        </label>
        <p>ユーザーネームとパスワードが空の場合は、お手数ですが以下の情報を入力してログインしてください</p>
        <p>ユーザーネーム：システム</p>
        <p>パスワード：system123</p>
        <div class="area-msg"><?php err('pass') ?></div>
        <label class="checkbox">
          <input type="checkbox" name="pass_save">次回ログインを省略する
        </label>
        <div class="btn-container">
          <input type="submit" class="login--btn" value="ログインする">
        </div>
<!--
        <div class="btn-container">
          <p>ゲストユーザーは一部機能が制限されていますが、そのままログインできます</p>
          <input type="submit" name="guest" class="login--btn login--guest" value="ゲストユーザーでログイン">
        </div>
-->
      </form>
      <div class="signup-btn">
        <a href="signup.php">登録する</a>
      </div>
    </section>
  </main>
  <?php require('footer.php');?>
