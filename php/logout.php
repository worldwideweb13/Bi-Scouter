<?php

session_start();

// SESSIONを初期化(空っぽにする)
$_SESSION = array();

// cookieに保存してある"SessionIDの保存期間を過去にして破棄
if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time()-42000, '/');
}

// 処理後、index.phpへリダイレクト
header("Location: login.php");
exit;

?>