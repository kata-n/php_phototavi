<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「たび情報詳細ページ「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// たびIDのGETパラメータを取得
$t_id = (!empty($_GET['t_id'])) ? $_GET['t_id'] : '';
// DBからたびデータを取得
$viewData = getTripOne($t_id);
// パラメータに不正な値が入っているかチェック
if(empty($viewData)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}
debug('取得したDBデータ：'.print_r($viewData,true));

// post送信されていた場合
if(!empty($_POST['submit'])){
  debug('POST送信があります。');

  //ログイン認証
  require('auth.php');

  //変数にユーザー情報を代入
  $u_id = $_SESSION['user_id'];
  $t_id = $viewData['trip_id'];
  $message = $_POST['message'];
  $success_message = null;

  //未入力チェック
  validRequired($message,'message');

  if(empty($err_msg)){
    debug('バリデーションOKです。');
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'INSERT INTO message (user_id, trip_id, message, post_date) VALUES (:u_id, :t_id, :message, :date)';
    $data = array(':u_id' => $u_id, ':t_id' => $t_id, ':message' => $message, ':date' => date('Y-m-d H:i:s'));
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    // クエリ成功の場合
    if($stmt){
//      $_SESSION['msg_success'] = SUC03;
      $success_message = 'メッセージを書き込みました。';
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
}
// DBから掲示板データを取得
$getMessageData = getMessagebord($t_id);
debug('取得した掲示板データ'.print_r($getMessageData,true));

$dbFavoCount = ''; //いいねの数
if(!empty($_GET['t_id'])){
    // DBからいいねの数を取得
    $dbFavoCount = count(getfavoCount($t_id));
}

debug('<<<<<<<<<<<<<<<<<<<画面表示処理終了<<<<<<<<<<<<<<<<<<');
$siteTitle = 'たび情報詳細｜Phototavi';
require('head.php');
?>

<body onload="initialize()">
<!--/*お気に入りアイコン*/-->
  <style>
    .icn-like{
      color: #ddd;
    }
    .icn-like:hover{
      cursor: pointer;
    }
    .icn-like.active{
      color: #fe8a8b;
    }
  </style>

<!-- ヘッダー -->
<?php require('header.php'); ?>
  <section class="detail">
    <div class="detail__imgcontainer">
      <div class="detail__imgmain">
        <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="メイン画像：<?php echo sanitize($viewData['title']); ?>" id="js-switch-img-main">
        <p class="detail__title"><?php echo sanitize($viewData['title']); ?></p>
        <p class="detail__place"><?php echo sanitize($viewData['place']); ?></p>
        <!--お気に入りアイコン-->
        <i class="fa fa-heart icn-like detail__like js-click-fov <?php if(getfavorite($_SESSION['user_id'], $viewData['trip_id'])){ echo 'active'; } ?>" aria-hidden="true" data-tripid="<?php echo sanitize($viewData['trip_id']); ?>" ></i><span class="count detail__likecount"><?php echo $dbFavoCount ?></span>
      </div>
      <div class="detail__imgsub">
        <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="画像1：<?php echo sanitize($viewData['title']); ?>" class="js-switch-img-sub">
        <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="画像2：<?php echo sanitize($viewData['title']); ?>" class="js-switch-img-sub">
        <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="画像3：<?php echo sanitize($viewData['title']); ?>" class="js-switch-img-sub">
      </div>
     </div>

      <div id="map_canvas_display" class="map-canvas-display"></div>
      <!--緯度-->
      <div class="placepoint">
        <input type="text" id="ido" name="ido" value="<?php echo sanitize($viewData['ido']); ?>">
        <!--経度-->
        <input type="text" id="keido" name="keido" value="<?php echo sanitize($viewData['keido']); ?>">
      </div>

     <div class="detail__writing">
      <p><?php echo sanitize($viewData['comment']); ?></p>
      <div class="detail__writing-title">この記事を投稿した人</div>
       <div class="detail__writing-profile">
        <a href="userpage.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&u_id='.$viewData['user_id'] : '?u_id='.$viewData['user_id']; ?>" class="panel">
          <img src="<?php echo sanitize($viewData['pic']); ?>" alt="<?php echo sanitize($viewData['pic']); ?>">
          <p><?php echo sanitize($viewData['username']); ?></p>
        </a>
        <div class="post-date">投稿日：<?php echo sanitize($viewData['create_date']); ?></div>
       </div>
     </div>
  <div class="comment">
    <div class="comment__backbtn">
      <a href="index.php<?php echo appendGetParam(array('t_id')); ?>">&lt; たび一覧に戻る</a>
    </div>
    <?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
    <label for="username" style="display:none;">
     <input type="text" value="<?php echo $_SESSION['user_name'] ?>" name="username" readonly>
    </label>
    <br>
    <div class="comment__title">コメントを書いてみよう</div>
    <?php err('message') ?>
    <label for="message">
      <textarea id="message" name="message" class="comment__message"></textarea>
    </label>
      <div class="item-right">
        <input type="submit" value="コメントを書く" name="submit" class="btn btn-primary" id="js-posts"  style="margin-top:0;">
      </div>
    </form>
  </div>
  <div class="comment">
   <?php foreach($getMessageData as $key => $val): ?>
     <div class="comment__container">
        <div class="comment__head">
          <img src="<?php echo sanitize($val['pic']); ?>" alt="<?php echo sanitize($val['pic']); ?>">
          <p class="comment__profile"><?php echo sanitize($val['username']); ?></p>
          <p class="panel__title"><?php echo sanitize($val['post_date']); ?></p>
        </div>
        <div class="comment-body">
          <p class="comment-title"><?php echo sanitize($val['message']); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
 </div>
<!--mapapi表示処理-->
  <script src="js/displaymapapi.js"></script>
</section>

<!-- footer -->
<?php require('footer.php'); ?>
