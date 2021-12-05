<?php
session_start();
// LOGIN チェック
include("funcs.php");
sschk();

$sku = $_POST["sku"];
$pdo = db_conn();
//２．データ登録SQL作成

$stmt = $pdo->prepare("DELETE FROM products_table WHERE sku=:sku");
$stmt->bindValue(":sku", $sku, PDO::PARAM_STR);
$status = $stmt->execute();

//３．データ表示
if($status==false) {
  sql_error($stmt);

}else{
    echo("OK");
    redirect("index.php");
}

?>