<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('================================');
debug('=========マイページ画面============');
debug('================================');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
//DBからデータを取得
$dbmyTripData = getMyTripData($u_id);
$dbmyFavoriteTrip = getfavoriteList($u_id);
// DBからユーザーデータを取得
$userData = getUserOne($u_id);

debug('取得した自分が投稿した旅データ'.print_r($dbmyFavoriteTrip,true));
debug('===============画面表示終了===============');
$siteTitle = 'マイページ|Phototavi';
require('head.php');
?>

<body>
 <?php require('header.php'); ?>
  <section class="mypage">
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>
    <div class="mypage__container">
    <div class="mypage__title">
      <p>マイページ</p>
      <img src="<?php echo sanitize($userData['pic']); ?>" alt="<?php echo sanitize($userData['pic']); ?>">
      <span class="mypage__title-badge"><?php echo sanitize($userData['username']); ?></span>
      <p class="mypage__title-editlink"><a href="profedit.php">プロフィール編集を行う</a></p>
    </div>
      <h3 class="heading">投稿一覧</h3>
      <button class="post-btn">表示する</button>
      <div class="mypage__panel-list2">
      <p>内容編集が可能です。削除する場合はここから行ってください。</p>
       <?php foreach($dbmyTripData as $key => $val): ?>
         <div class="mypage__panel2">
            <div class="panel-head2">
              <a href="registTrip.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
                  <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['pic1']); ?>">
              </a>
            </div>
            <div class="panel-body2">
              <a href="registTrip.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
              <p class="panel-title"><?php echo sanitize($val['title']); ?></p>
              <p class="panel-section"><?php echo sanitize($val['comment']); ?></p>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <h3 class="heading">お気に入り一覧</h3>
      <button class="favorites-btn">表示する</button>
      <div class="mypage__favorite-list">
      <p>お気に入り登録した一覧です</p>
       <?php foreach($dbmyFavoriteTrip as $key => $val): ?>
         <div class="mypage__favorite">
            <div class="panel-head2">
              <a href="tripDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
                  <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['pic1']); ?>">
              </a>
            </div>
            <div class="panel-body2">
              <a href="tripDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
              <p class="panel-title"><?php echo sanitize($val['title']); ?></p>
              <p class="panel-section"><?php echo sanitize($val['comment']); ?></p>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php require('footer.php'); ?>
