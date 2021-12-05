<?php
// 最初にSESSION開始
session_start();
//POST値
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];

// DB接続します
include("funcs.php");
$pdo = db_conn();
$sql="SELECT * FROM users_table WHERE lid=:lid AND lpw=:lpw AND life_flg=0";
$stmt = $pdo->prepare($sql); //* PasswordがHash化の場合→条件はlidのみ
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR); 
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
    sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch(); //1レコードだけ取得する方法
//5. 該当レコードがあればSESSIONに値を代入
if( $val["id"] != ""){
    // Login成功(LogIn経由)
    $_SESSION["chk_ssid"] = session_id();
    $_SESSION["kanri_flg"] = $val['kanri_flg'];
    $_SESSION["name"] = $val['name'];
    // echo $_SESSION["chk_ssid"];
    redirect("index.php");
}else{
    // Login失敗時(LogOut経由)
    redirect("login.php");
    ini_set('display_errors', 1);
}

exit();

?>

