<?php
require('function.php');

debug('================================');
debug('===============プロフィール編集画面=================');
debug('================================');
debugLogStart();

//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');
// DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報：'.print_r($dbFormData,true));

// POST送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

//変数にユーザー情報を代入
$username = $_POST['username'];
$pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : '';
// 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
$pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;
//DBの情報と入力情報が異なる場合にバリデーションを行う
if($dbFormData['username'] !== $username){
  //名前の最大文字数チェック
  validMaxLen($username, 'username');
}

if(empty($err_msg)){
  debug('バリデーションOKです。');

//例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'UPDATE users SET username = :u_name, pic = :pic WHERE id = :u_id';
    $data = array(':u_name' => $username , ':pic' => $pic, ':u_id' => $dbFormData['id']);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    // クエリ成功の場合
    if($stmt){
      $_SESSION['msg_success'] = SUC02;
      debug('メインページへ遷移します。');
      header("Location:mypage.php"); //myページへ
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
  }
}
debug('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

//================================
// 画面表示用データ取得
//================================
//タイトル表示
    $siteTitle = 'プロフィール編集|Phototavi';
    require('head.php');
?>

<body>
  <?php require('header.php'); ?>
    <section class="profile">
      <div class="profile__container">
        <h2 class="profile__title">プロフィールを更新する</h2>
        <form action="" method="post" class="profile__form" enctype="multipart/form-data">
          <div class="area-msg"><?php err('common') ?></div>
          <label class="<?php if(!empty($err_msg['username'])) echo sanitize('err'); ?>">
            ユーザーネーム
            <input type="text" name="username" class="profile__name" value="<?php echo sanitize(getFormData('username')); ?>">
          </label>
          <div class="area-msg"><?php err('username') ?></div>
            プロフィール画像<br>
            <label class="area-drop2 <?php if(!empty($err_msg['pic'])) echo sanitize('err'); ?>">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="pic" class="input-file" >
              <img src="<?php echo sanitize(getFormData('pic')); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo sanitize('display:none;') ?>">
                ドラッグ＆ドロップ
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pic'])) echo sanitize($err_msg['pic']);
              ?>
            </div>
            <div class="btn-container" >
              <input type="submit" class="btn btn-mid" value="変更する">
            </div>
        </form>
      </div>
    </section>
 <?php require('footer.php');?>
