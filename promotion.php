<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('================================');
debug('========プロモーション画面=========');
debug('================================');

//================================
// 画面表示用データ取得
//================================
//画像を取得
$imgData = getImg();
debug('取得した画像データ：'.print_r($imgData,true));
//画面表示
  $siteTitle = 'Ptototaviについて | Phototavi';
  require('head.php');
?>

<body>
  <section class="promotion">
    <div class="promotion__banner">
      <div class="promotion__banner-img">
        <div class="promotion__banner-sentence">
          <p class="title">Phototaviとは</p>
          <p class="parag">
          Phototaviは、旅の思い出を共有するサービスです。<br>
          スマホで撮った写真やPCのデータを投稿するができます。<br>
          思い出を投稿して旅の感動を思い出してみませんか。<br>
          <br>
          また、Phototaviでは投稿した人たちの思い出も見ることができます。<br>
          投稿している方の旅の思い出を参考にしてみてはいかがでしょうか。
          </p>
        </div>
        <div class="promotion__btn">
          <div class="promotion__btn--login">
            <a href="login.php">ログイン</a>
          </div>
          <div class="promotion__btn--regist">
            <a href="signup.php">登録する</a>
          </div>
        </div>
        <div class="promotion__slider">
         <div class="promotion__slider-wrap">
          <?php foreach($imgData as $key => $val): ?>
           <div class="promotion__slider-slide">
            <img src="<?php echo sanitize($val['pic1']);?>">
           </div>
          <?php endforeach; ?>
          <?php foreach($imgData as $key => $val): ?>
           <div class="promotion__slider-slide">
            <img src="<?php echo sanitize($val['pic1']);?>">
           </div>
          <?php endforeach; ?>
         </div>
        </div>
      </div>
    </div>
  </section>
<?php require('footer.php'); ?>
