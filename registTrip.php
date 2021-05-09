<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('たび情報登録ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

// 画面表示用データ取得
//================================
// GETデータを格納
$t_id = (!empty($_GET['t_id'])) ? $_GET['t_id'] : '';
// DBから旅データを取得
$dbFormData = (!empty($t_id)) ? getTripDetail($_SESSION['user_id'], $t_id) : '';
// 新規登録画面か編集画面か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;
// DBから場所データを取得
$dbPlaceData = getPlace();
debug('旅ID：'.$t_id);
debug('フォーム用DBデータ：'.print_r($dbFormData,true));
//debug('場所データ：'.print_r($dbPlaceData,true));

// パラメータ改ざんチェック
//================================
// GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい商品データが取れないのでマイページへ遷移させる
if(!empty($t_id) && empty($dbFormData)){
  debug('GETパラメータのたびIDが違います。メインページへ遷移します。');
  header("Location:index.php"); //メインページへ
}

// POST送信時処理
//================================
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //変数にユーザー情報を代入
  $title = $_POST['title'];
  $place = $_POST['place_id'];
  $season = $_POST['season'];
  $emotion = $_POST['emotion'];
  foreach ($emotion as $value){
    $e .= $value.",";
  }
  $comment = $_POST['comment'];
  $maptitle = $_POST['maptitle'];
  $ido = $_POST['ido'];
  $keido = $_POST['keido'];
  //画像をアップロードし、パスを格納
  $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'],'pic1') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;
  $pic2 = ( !empty($_FILES['pic2']['name']) ) ? uploadImg($_FILES['pic2'],'pic2') : '';
  $pic2 = ( empty($pic2) && !empty($dbFormData['pic2']) ) ? $dbFormData['pic2'] : $pic2;
  $pic3 = ( !empty($_FILES['pic3']['name']) ) ? uploadImg($_FILES['pic3'],'pic3') : '';
  $pic3 = ( empty($pic3) && !empty($dbFormData['pic3']) ) ? $dbFormData['pic3'] : $pic3;
  
  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    validRequired($title, 'title');
    //最大文字数チェック
    validMaxLen($title, 'title');
    //セレクトボックスチェック
    validSelect($place, 'place_id');
    //最大文字数チェック
    validMaxLen($comment, 'comment', 500);
    //未入力チェック
    validRequired($title, 'title');
  }else{
    if($dbFormData['title'] !== $title){
      //未入力チェック
      validRequired($title, 'title');
      //最大文字数チェック
      validMaxLen($title, 'title');
    }
    if($dbFormData['place_id'] !== $place){
      //セレクトボックスチェック
      validSelect($place, 'place_id');
    }
    if($dbFormData['comment'] !== $comment){
      //最大文字数チェック
      validMaxLen($comment, 'comment', 500);
    }
  }

  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //削除チェック（delete_flgオン)
    if(isset($_POST['tripdelete'])){
      debug('たびデータを非表示にします');
        try {
        //DB接続
        $dbh = dbConnect();
        //SQL作成
        $sql = 'UPDATE trip SET delete_flg = 1 WHERE user_id = :u_id AND trip_id = :t_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':t_id' => $t_id);
        debug('SQL：'.$sql);
        debug('流し込みデータ：'.print_r($data,true));
        // クエリ実行
        $stmt = querypost($dbh, $sql, $data);
        // クエリ成功の場合
        if($stmt){
            $_SESSION['msg_success'] = SUC04;
            debug('マイページへ遷移します。');
            header("Location:mypage.php"); //マイページへ
        }
       }catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
    }

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
      if($edit_flg){
        debug('DB更新です。');
        $sql = 'UPDATE trip SET title = :title, place_id = :place, season = :season, emotion = :emotion, comment = :comment, maptitle = :maptitle, ido = :ido, keido = :keido, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE user_id = :u_id AND trip_id = :t_id';
        $data = array(':title' => $title , ':place' => $place, ':season' => $season, ':emotion' => $e, ':comment' => $comment, ':maptitle' => $maptitle, ':ido' => $ido, ':keido' => $keido, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':t_id' => $t_id);
      }else{
        debug('DB新規登録です。');
        $sql = 'insert into trip (title, place_id, season, emotion, comment, maptitle, ido, keido, pic1, pic2, pic3, user_id, create_date ) values (:title, :place, :season, :emotion, :comment,:maptitle, :ido, :keido, :pic1, :pic2, :pic3, :u_id, :date)';
        $data = array(':title' => $title , ':place' => $place, ':season' => $season, ':emotion' => $e, ':comment' => $comment, ':maptitle' => $maptitle, ':ido' => $ido, ':keido' => $keido, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      }
      debug('SQL：'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC06;
        debug('マイページへ遷移します。');
        header("Location:mypage.php"); //myページへ
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('<<<<<<<<<<<<<<<<<<<<画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<');
$siteTitle = (!$edit_flg) ? '投稿ページ｜Phototavi' : '編集ページ｜Phototavi';
require('head.php'); 
?>
<!-- メニュー -->
 <?php require('header.php'); ?>
 <!-- メイン -->
 <body onload="initialize()">
 <section class="regist">
  <div class="regist__container">
   <form action="" method="post" class="regist__form" enctype="multipart/form-data" name="registform">
   <h2 class="regist__title"><?php echo (!$edit_flg) ? '投稿する' : '更新する'; ?></h2>
   <div class="area-msg"><?php err('common')?></div>
    <label class="<?php if(!empty($err_msg['title'])) echo sanitize('err'); ?>">
     タイトル<span class="label-require">必須</span>
      <input type="text" class="regist-title" name="title" value="<?php echo sanitize(getFormData('title')); ?>">
      <p class="resist-exp">例:東京タワーに登ってきました</p>
    </label>
    <div class="area-msg"><?php err('title')?></div>
     <label class="<?php if(!empty($err_msg['place_id'])) echo sanitize('err'); ?>">
       都道府県を選択<span class="label-require">必須</span>
      <select name="place_id" class="regist-pref">
        <option value="0" <?php if(getPlace('place_id') == 0 ){ echo sanitize('selected'); } ?> >選択する</option>
        <?php foreach($dbPlaceData as $key => $val){ ?>
         <option value="<?php echo sanitize($val['place_id']) ?>" <?php if(getFormData('place_id') == $val['place_id'] ){ echo sanitize('selected'); } ?> >
           <?php echo sanitize($val['place']); ?>
          </option>
        <?php } ?>
      </select>
     </label>

      <label class="<?php if(!empty($err_msg['season'])) echo sanitize('err'); ?>">
        季節を選んでください<span class="label-require">必須</span>
          <group class="inline-radio">
            <div><input type="radio" id="select1" name="season" value="1"  <?php if(getFormData('season') == 1 ){ echo sanitize('checked'); } ?>><label for="select1">春</label></div>
            <div><input type="radio" id="select2" name="season" value="2"  <?php if(getFormData('season') == 2 ){ echo sanitize('checked'); } ?>><label for="select2">夏</label></div>
            <div><input type="radio" id="select3" name="season" value="3" <?php if(getFormData('season') == 3 ){ echo sanitize('checked'); } ?>><label  for="select3">秋</label></div>
            <div><input type="radio" id="select4" name="season" value="4" <?php if(getFormData('season') == 4 ){ echo sanitize('checked'); } ?>><label for="select4">冬</label></div>
          </group>
      </label>

      <label class="<?php if(!empty($err_msg['season'])) echo sanitize('err'); ?>">
        どんな場所？（複数選択可）<span class="label-require">必須</span>
          <group class="inline-radio">
            <div><input type="checkbox" id="emotion1" name="emotion[]" value="1" <?php if(strpos(getFormData('emotion'),'1') !== false){ echo sanitize('checked'); } ?>><label for="emotion1">絶景</label></div>
            <div><input type="checkbox" id="emotion2" name="emotion[]" value="2" <?php if(strpos(getFormData('emotion'),'2') !== false){ echo sanitize('checked'); } ?>><label for="emotion2">楽しい</label></div>
            <div><input type="checkbox" id="emotion3" name="emotion[]" value="3" <?php if(strpos(getFormData('emotion'),'3') !== false){ echo sanitize('checked'); } ?>><label for="emotion3">驚き</label></div>
            <div><input type="checkbox" id="emotion4" name="emotion[]" value="4" <?php if(strpos(getFormData('emotion'),'4') !== false){ echo sanitize('checked'); } ?>><label for="emotion4">神秘的</label></div>
            <div><input type="checkbox" id="emotion5" name="emotion[]" value="5" <?php if(strpos(getFormData('emotion'),'5') !== false){ echo sanitize('checked'); } ?>><label for="emotion5">魅力的</label></div>
          </group>
      </label>
      <div class="area-msg"><?php err('place_id') ?></div>
      <label class="<?php if(!empty($err_msg['comment'])) echo sanitize('err'); ?>">
        内容を書いてください（空白可）
        <textarea name="comment" id="js-count" value="例：東京タワーに行ってきました" class="regist-contain" cols="50" rows="10" style="height:150px;"><?php echo sanitize(getFormData('comment')); ?></textarea>
      </label>
      <p class="counter-text"><span id="js-count-view">0</span>/500文字</p>
      <div class="area-msg">
        <?php err('comment') ?>
      </div>
    <div class="map-area">
    場所を選択(名所などで検索できます)
    <div id="map_canvas" style="width:100%; height:100%"></div>
    </div>
    <input type="text" name="maptitle" class="maptitle" value="<?php echo sanitize(getFormData('maptitle')); ?>" size="20" id="place">
    <button type="button" onclick="search()" class="mapserch-btn">検索</button><br />
    <div class="mapdit">
      緯度：<input type="text" id="ido" name="ido" value="<?php echo sanitize(getFormData('ido')); ?>" readonly>
      経度：<input type="text" id="keido" name="keido" value="<?php echo sanitize(getFormData('keido')); ?>" readonly>
    </div>

      <div style="overflow:hidden;" class="imgDrop">
        <div class="imgDrop-container">
          画像1
          <label class="area-drop <?php if(!empty($err_msg['pic1'])) echo sanitize('err'); ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="pic1" class="input-file">
            <img src="<?php echo sanitize(getFormData('pic1')); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo sanitize('display:none;') ?>">
              ドラッグ＆ドロップ
          </label>
          <div class="area-msg">
            <?php 
            if(!empty($err_msg['pic1'])) echo sanitize($err_msg['pic1']);
            ?>
          </div>
        </div>
        <div class="imgDrop-container">
          画像２
          <label class="area-drop <?php if(!empty($err_msg['pic2'])) echo sanitize('err'); ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="pic2" class="input-file">
            <img src="<?php echo sanitize(getFormData('pic2')); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic2'))) echo sanitize('display:none;') ?>">
              ドラッグ＆ドロップ
          </label>
          <div class="area-msg">
            <?php 
            if(!empty($err_msg['pic2'])) echo sanitize($err_msg['pic2']);
            ?>
          </div>
        </div>
        <div class="imgDrop-container">
          画像３
          <label class="area-drop <?php if(!empty($err_msg['pic3'])) echo sanitize('err'); ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="pic3" class="input-file">
            <img src="<?php echo sanitize(getFormData('pic3')); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic3'))) echo sanitize('display:none;') ?>">
              ドラッグ＆ドロップ
          </label>
          <div class="area-msg">
            <?php 
            if(!empty($err_msg['pic3'])) echo sanitize($err_msg['pic3']);
            ?>
          </div>
        </div>
      </div>

      <div class="btn-container">
        <input type="submit" class="btn btn-mid" id="js-posts" value="<?php echo (!$edit_flg) ? '投稿する' : '更新する'; ?>">
      </div>
      <?php if(empty($edit_flg)) $class="hide";?>
      <input type="submit" id="js-delete" class="btn <?php if(isset($class)) echo sanitize($class); ?>" value="削除する" name="tripdelete">
    </form>
   </div>
  </section>
<!--mapapi処理読み込み-->
  <script src="js/mapapi.js"></script>
<!-- フッター -->
<?php require('footer.php'); ?>