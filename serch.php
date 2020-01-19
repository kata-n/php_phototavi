<?php
//共通変数・関数ファイル読み込み
require('function.php');

debug('================================');
debug('===============パス検索画面=================');
debug('================================');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面表示用データ取得
//================================
//DBからカテゴリデータを取得
$categoryData = getCategory();
debug('取得したcategoryデータ'.print_r($categoryData,true));
//================================
// GETパラメータを取得
//================================
//カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
//カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] :'';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

//表示件数
$listSpan = 10;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
//DBからpasstitleデータを取得
$passT = (!empty($_GET['serch'])) ? $_GET['serch'] : '';
//DBからpassデータ取得
$passSortData = getPasslist($currentMinNum, $category, $passT,$sort);
debug('取得したpassデータ:'.print_r($passSortData,true));
debug('===============画面表示終了===============');
?>

<?php
$siteTitle = '登録パス検索';
require('head.php');
?>

<body class="indexbody">
    <?php
    require('header.php');
    ?>
    <div class="clmcontainer touroku">
        <main class="clmain">
            <section class="sec1">
                <h2 class="heading">パスワードを検索</h2>
                <form action="" method="get">
                    <h3 class="heading">登録名で検索</h3>
                    <input name="serch" type="text" class="">
                    <h3 class="heading">カテゴリ別で表示</h3>
                    <select name="c_id">
                        <option class="catebox" value="0" <?php if(getFormData('c_id',true) == 0){echo 'selected';}?>>選択してください
                        </option>
                        <?php foreach($categoryData as $key => $val){
                        ?>
                        <option value="<?php echo $val['id']?>" <?php if(getFormData('c_id',true) == $val['id']) { echo 'selected';}?>>
                            <?php echo $val['name'];?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
<!--                    <h3 class="heading">並び替え</h3>-->
<!--
                    <div>
                        <select name="sort">
                            <option value="0" <?php if(getFormData('sort',true) == 0 ){echo 'selected';}?>>選択してください</option>
                            <option value="1" <?php if(getFormData('sort',true) == 1 ){echo 'selected';}?>>古い順</option>
                            <option value="2" <?php if(getFormData('sort',true) == 2 ){echo 'selected';}?>>新しい順</option>
                        </select>
                    </div>
-->
                    <div class="btn-container">
                        <input type="submit" class="login-btn" value="検索">
                    </div>
                </form>
            </section>
            <section class="sec1">
                <h3 class="heading">検索結果</h3>
                <div class="serch-rst">
                    <span class="rst-style"><?php echo sanitize($passSortData['total']);?>件見つかりました！</span>
                </div>
                <div class="link" >
                    <?php foreach($passSortData['data'] as $key => $val):?>
                    <a href="registpass.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>">
                        <p class="rst-title"><?php echo sanitize($val['passtitle']);?></p>
                    </a>
                    <?php
                    endforeach;
                    ?>
                </div>
            </section>
        </main>
        <?php require('sidebar.php'); ?>
    </div>
    <?php
    require('footer.php');
    ?>
