<?php
//共通変数・関数ファイル読み込み
require('function.php');
debug('================================');
debug('===============ユーザー登録処理=================');
debug('================================');

//POST送信されていた場合
if(!empty($_POST)){
//    変数にユーザー情報を代入
  $name = $_POST['name'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

//バリデーション
//未入力のチェック
  validRequired($name,'name');
  validRequired($pass,'pass');
  validRequired($pass_re,'pass_re');

if(empty($err_msg)){
  //名前の重複チェック
  validNameDup($name);
  //パスワード半角英数字チェック
  validHalf($pass,'pass');
  //パスワードの最大文字数
  validMaxLen($pass, 'pass');
  //パスワードの最小文字数
  validMinLen($pass, 'pass');
  //パスワード再入力最大入力の最大文字数
  validMaxLen($pass_re, 'pass_re');
  //パスワード再入力の最小文字数
  validMinLen($pass_re, 'pass_re');

  if(empty($err_msg)){
    //パスワードと再入力があっているかチェック
    validMatch($pass, $pass_re, 'pass_re');

    if(empty($err_msg)){
        try {
        $dbh = dbConnect();
        $sql = "INSERT INTO users (username,pass,login_time,create_date) VALUES(:name,:pass,:login_time,:create_date)";
        $data = array(':name'=>$name,':pass'=>password_hash($pass, PASSWORD_DEFAULT),':login_time'=>date('Y-m-d H:i:s'),':create_date'=>date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        //クエリ成功の場合
        if($stmt){
          //ログイン有効期限は1時間
          $sesLimit = 60* 60;
          //最終ログイン日時を現在日時に
          $_SESSION['login_date'] = time();
          $_SESSION['login_limit'] = $sesLimit;
          //ユーザーIDを格納
          $_SESSION['user_id'] = $dbh->lastInsertId();
          //ユーザーネームを格納
          $_SESSION['user_name'] = $name;
          //登録メッセージ
          $_SESSION['msg_success'] = SUC07;
//          debug('セッション変数の中身：'.print_r($_SESSION,true));
          //メインページへ
          header("Location:index.php");
        }
        } catch (Exception $e){
            error_log('エラー発生；'. $e->getMessage());
            $err_msg['common'] = MSG07;
      }
    }
  }
 }
}
$siteTitle ="ユーザー登録";
require('head.php');
?>
<!--画面表示-->
<body>
  <?php require('header.php'); ?>
  <main class="sign__main">
    <section class="sign__container">
      <form action="" method="post" class="sign__form">
        <h2 class="heading">ユーザー登録</h2>
        <label class="
        <?php if(!empty($err_msg['name'])) echo 'err'?>">
          <input type="text" class="sign-name" name="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name'];?>" placeholder="ユーザーネーム（必須）">
        </label>
        <div class="area-msg">
          <?php 
          if(!empty($err_msg['name'])) echo $err_msg['name'];
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
          <input type="password" class="sign-pass" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>" placeholder="パスワード（英数字6文字以上）">
        </label>
        <div class="area-msg">
          <?php 
          if(!empty($err_msg['pass'])) echo $err_msg['pass'];
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
          <input type="password" class="sign-repass" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>" placeholder="パスワード再入力（必須）">
        </label>
        <div class="area-msg">
          <?php 
          if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
          ?>
        </div>
        <div class="btn-container">
          <input type="submit" class="login-btn sign-btn js-signup" value="登録する" disabled>
        </div>
      </form>
    </section>
  </main>
<?php require('footer.php'); ?>
