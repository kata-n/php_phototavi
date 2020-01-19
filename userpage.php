<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「ユーザー情報詳細ページ「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// たびIDのGETパラメータを取得
$u_id = (!empty($_GET['u_id'])) ? $_GET['u_id'] : '';
// DBからユーザーデータを取得
$userData = getUserOne($u_id);
// DBからユーザーが登録したたびデータを取得
$viewData = getUserList($u_id);
//都道府県データを取得する
$AllPrefData = getPlace();
//ユーザーが登録した都道府県データ
$dbprefData = getPrefList($u_id);
foreach($dbprefData as $key => $valpref){
  $prefstr .= $valpref['place_id'].",";
  $prefarry = explode(",",$prefstr);
  $preef = array_filter($prefarry,"strlen");
}
//  debug('emotion情報：'.print_r($preef,true));
// パラメータに不正な値が入っているかチェック
if(empty($viewData)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header("Location:index.php"); //トップページへ
}
debug('取得したDBデータ：'.print_r($viewData,true));
debug('<<<<<<<<<<<<<<<<<<<画面表示処理終了<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'ユーザーぺージ | Phototavi';
require('head.php');
?>

<body>
  <style>
    /*お気に入りアイコン*/
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

  <!-- メインコンテンツ -->
  <section class="userpage">
    <div class="userpage__title">
       <h3>ユーザー情報</h3>
      <img src="<?php echo sanitize($userData['pic']); ?>" alt="<?php echo sanitize($userData['pic']); ?>">
      <span class="userpage__title-badge"><?php echo sanitize($userData['username']); ?></span>
    </div>
    <div class="userpage__pref">
      <p class="userpage__title-pref">投稿した都道府県一覧<br>(クリックでソートできます)</p>
       <div class="userpage__pref-box">
        <?php foreach($AllPrefData as $key => $val): ?>
          <div><input type="checkbox" id="pref<?php echo sanitize($val['place_id'])?>" name="pref-chk" value="<?php echo sanitize($val['place_id'])?>" <?php if(in_array($val['place_id'],$preef) !== false){ echo 'checked'; } ?> disabled="disabled"><label class="prefnum" value="<?php echo sanitize($val['place_id'])?>" for="pref<?php echo sanitize($val['place_id'])?>"><?php echo sanitize($val['place'])?></label></div>
        <?php endforeach; ?>
       </div>
    </div>
    <p class="userpage__intoro"><?php echo sanitize($userData['username']);?>さんの投稿一覧</p>
    <button class="prefnum prefAll" value="prefAll">すべて表示</button>
    <div class="userpage__container">
     <?php foreach($viewData as $key => $val): ?>
      <div class="userpage__panel js-target <?php echo $val['place_id']?>">
        <a href="tripDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&t_id='.$val['trip_id'] : '?t_id='.$val['trip_id']; ?>" class="panel">
          <div class="panel-head">
            <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['pic1']); ?>">
          </div>
          <div class="panel-body">
            <p class="panel-title">旅タイトル：<?php echo sanitize($val['title']); ?></p>
            <p class="panel-section"><?php echo sanitize($val['comment']); ?></p>
          </div>
            <!--いいね数表示-->
            <p class="panel-favorite"><?php echo sanitize(count(getfavoCount($val['trip_id'])));?></p>
        </a>
      </div>
     <?php endforeach; ?>
    </div>
  </section>
<!-- フッター -->
<?php require('footer.php'); ?>
