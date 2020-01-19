<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('================================');
debug('===========メイン画面=============');
debug('================================');

//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');

//================================
// 検索ソート
//================================
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：'.print_r($_POST,true));

//変数をユーザー情報に代入
foreach($_POST['pref'] as $value){
  $str .= $value.",";
  $array = explode(",",$str);
  $pref = array_filter($array,"strlen");
}
//  debug('pref情報：'.print_r($pref,true));

foreach($_POST['emotion'] as $valuee){
  $str2 .= $valuee.",";
  $arrayy = explode(",",$str2);
  $emotion = array_filter($arrayy,"strlen");
}
//  debug('emotion情報：'.print_r($emotion,true));

foreach($_POST['season'] as $valueee){
  $str3 .= $valueee.",";
  $arraye = explode(",",$str3);
  $season = array_filter($arraye,"strlen");
}
//  debug('season情報：'.print_r($season,true));
}

//================================
// 画面表示用データ取得
//================================
// カレントページ//デフォルトは１ページめ
$currentPageNum = (!empty($_GET['t'])) ? $_GET['t'] : 1;
//都道府県カテゴリー
$prefCategory = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
if(!is_int($currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}
//初期化
$pref = (!empty($pref)) ? $pref : '';
$emotion = (!empty($emotion)) ? $emotion : '';
$season = (!empty($season)) ? $season : '';
// 表示件数
$listSpan = 5;
// 現在の表示レコード先頭を算出
//1ページ目なら(1-1)*20 = 0 、 ２ページ目なら(2-1)*20 = 20
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBからたびデータを取得
$dbTripData = getTripList($currentMinNum, $pref, $emotion, $season);
// DBからカテゴリデータを取得
$dbPlaceData = getPlace();
//お気に入りデータ取得
$dbfavoritesData = $dbTripData['trip_id'];
//debug('取得したDBデータ：'.print_r($dbTripData,true));
// 検索結果の表示
if($dbTripData['total'] == 0){
 $serchresult = "件です。指定した条件での旅データはありませんでした。検索条件を変えてもう一度検索してください。";
}else{
 $serchresult = "件の旅データが見つかりました!";
}
//画面表示
  $siteTitle = 'トップページ | Phototavi';
  require('head.php');
?>

<body>
<?php require('header.php'); ?>
  <section>
  <div class="index__container">
      <p id="js-show-msg" style="display:none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
      </p>
    <div class="index__herobanner">
      <?php require('sidebar.php'); ?>
      <span class="total-num">
      <?php if(!empty($_POST))echo sanitize($dbTripData['total']); ?>
      <?php if(!empty($_POST))echo sanitize($serchresult); ?></span>
    </div>
    <div class="index__panel">
     <?php foreach($dbTripData['data'] as $key => $val): ?>
     <div class="index__panel--container">
      <a href="tripDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
        <div class="panel-head">
          <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['pic1']); ?>">
        </div>
        <div class="panel-title season<?php echo sanitize($val['season']); ?>">
          <p><?php echo sanitize($val['title']); ?></p>
        </div>
        <div class="panel-body">
         <div class="panel-comment">
            <p><?php echo sanitize($val['comment']); ?></p>
         </div>
          <!--いいね数表示-->
          <p class="panel-favorite"><?php echo sanitize(count(getfavoCount($val['trip_id'])));?></p>
        </div>
      </a>
      <a href="userpage.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&u_id='.$val['id'] : '?u_id='.$val['id']; ?>" class="panel">
        <div class="panel-profile">
          <img src="<?php echo sanitize($val['pic']); ?>" alt="<?php echo sanitize($val['pic']); ?>">
          <p><?php echo sanitize($val['username']); ?></p>
        </div>
       </a>
      </div>
    <?php endforeach; ?>
   </div>
  </div>
  <div class="regist-btn js-regist-btn">
    <a href="registTrip.php">投稿する</a>
  </div>
  </section>
<?php require('footer.php'); ?>
