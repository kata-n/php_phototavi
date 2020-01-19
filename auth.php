<?php
//================================
// ログイン認証・自動ログアウト
//================================
// ログインしている場合
if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです');
    
//現在日時が最終ログイン日時と有効期限を超えていた場合
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
        debug('ログインが有効期限を超えています');
        debug('セッションを削除してログアウトします');
//セッションを削除
        session_destroy();
//ログイン画面へ遷移
        header("Location:login.php");
    }else{
        debug('ログイン有効期限内です');
//最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();
//ファイル名が同じかチェック
//無限ループ防止の為の処理
        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            debug('インデックス画面へ遷移します');
            header("Location:index.php");
        }
        }
    }else{
        debug('未ログインユーザーです');
        if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
            header("Location:login.php");
        }
}
?>
