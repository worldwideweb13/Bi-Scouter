<?php
//1. POSTデータ取得,入力フォームの値を変数化
//$name = filter_input( INPUT_GET, ","name" ); //こういうのもあるよ
$product_name = $_POST["product_name"];
$sku = $_POST["sku"];
$asin  = $_POST["asin"];
$stock = $_POST["stock"];
$category_id = 1;

// SKUから値を取り出し
$skuArray = explode("-",$sku);
$store_id = $skuArray[0];
$regist_date = $skuArray[1];
$purchase_price = (int)$skuArray[3];
$update_date = date("Ymd");

// 値を整数型に変換
$stock = (int)$stock;
$purchase_price = (int)$purchase_price;

// echo('<pre>');
// echo($store_id);
// echo('</pre>');

//2. DB接続します
include("funcs.php");
$pdo = db_conn();
// products_tableにデータ送信
$sql = "INSERT INTO products_table(product_name,category_id,sku,asin,purchase_price,store_id,regist_date,update_date)VALUES(:product_name,:category_id,:sku,:asin,:purchase_price,:store_id,:regist_date,:update_date)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':product_name', $product_name, PDO::PARAM_STR);
$stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);
$stmt->bindValue(':sku', $sku, PDO::PARAM_STR);
$stmt->bindValue(':asin', $asin, PDO::PARAM_STR);
$stmt->bindValue(':purchase_price', $purchase_price, PDO::PARAM_INT);
$stmt->bindValue(':regist_date', $regist_date, PDO::PARAM_STR);
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);
$stmt->bindValue(':store_id', $store_id, PDO::PARAM_STR);
$status = $stmt->execute();
// stock_tableにデータ送信
$sql = "INSERT INTO stock_table(sku,stock,update_date)VALUES(:sku,:stock,:update_date)";
echo($sql);
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sku', $sku, PDO::PARAM_STR);
$stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: index.php"); //リダイレクト
  exit();
}
?>
