<?php
session_start();
// LOGIN チェック
include("funcs.php");
sschk();

// ini_set('display_errors', 1);
//1. POSTデータ取得
$product_name = $_POST["product_name"];
$sku = $_POST["sku"];
$asin  = $_POST["asin"];
$stock = $_POST["stock"];
$stock = (int)$stock;
$category_id = 1;

// SKUから値を取り出し
$skuArray = explode("-",$sku);
$store_id = $skuArray[0];
$regist_date = $skuArray[1];
$purchase_price = (int)$skuArray[3];
$update_date = date("Ymd");
// var_dump($product_name);
// var_dump($update_date);
// var_dump($stock);
// var_dump($category_id);
// var_dump($store_id);
// var_dump($regist_date);
// var_dump($purchase_price);
// exit;

//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "UPDATE products_table SET product_name=:product_name, asin=:asin, category_id=:category_id, store_id=:store_id, purchase_price=:purchase_price, regist_date=:regist_date, update_date=:update_date WHERE sku=:sku";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sku', $sku, PDO::PARAM_STR);
$stmt->bindValue(':product_name', $product_name, PDO::PARAM_STR);
$stmt->bindValue(':asin', $asin, PDO::PARAM_STR);
// $stmt->bindValue(':stock', $stock, PDO::PARAM_STR);
$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$stmt->bindValue(':store_id', $store_id, PDO::PARAM_STR);
$stmt->bindValue(':purchase_price', $purchase_price, PDO::PARAM_INT);
$stmt->bindValue(':regist_date', $regist_date, PDO::PARAM_STR);
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);

$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
    sql_error($stmt);
}else{
    redirect("index.php");
}
?>