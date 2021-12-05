<?php
$tempfile = $_FILES['fname']['tmp_name'];
$filename = '../product_db_csv/'.$_FILES['fname']['name'];
if(is_uploaded_file($tempfile)){
    if(move_uploaded_file($tempfile,$filename)){
        echo $filename . "をアップロードしました。";                
    }else{
        echo "ファイルをアップロードできません。";
    }
} else {
    echo "ファイルが選択されていません";
}

// fopenでファイルを開く（'r'は読み込みモードで開く）
$fp = fopen('../product_db_csv/'.$filename, 'r');  

// $c = 0;
// $list= array();
while ($list = fgetcsv($fp)) {
    // ++$c;
    // if ($c == 30) {
    //     $result = array_shift($list);
    //     var_dump($list);
    //     return;
    // }
    // (1)在庫数を整数型で抜き出す
    $stock = (int)$list[4];
    // (2)SKUからプライスを整数型で抜き出す
    $sku_array = explode("-", $list[2]);
    $store_id = $sku_array[0];
    $regist_date = $sku_array[1];
    $price = (int)$sku_array[3];
    // (1)×(2)より商品別の棚卸額を算出
    // $StockAmount = $stock * $price;
    // $TotalStockAmount += $StockAmount;
    array_push($list,$stock,$store_id,$regist_date,$price,"","","","");
    // 不要な値の削除
    unset($list[0],$list[1],$list[4],$list[5],$list[6],$list[7]);
    // 配列番号を採番する
    $list = array_values($list);          
    // 文字コードをUTF-8に変換
    // $listconvert = mb_convert_encoding($list, "UTF-8");
    // 配列を","区切りでテキストデータに置換
    $txt = implode(",",$list); 
    // DBフォーマットに整形
    $file = fopen("../product_db_txt/data.txt","a");
    fwrite($file, $txt."\r\n");
    fclose($file);      
}
fclose($fp);


//1. DB接続します
include("funcs.php");
$pdo = db_conn();

//2．データ登録SQL作成
// BULK INSERT products_table
// FROM 'D:/Applications/MAMP/htdocs/lab10/06_Bi_Scouter/product_db_txt/data.txt'
// WITH
// (
// FIRSTROW = 2,
// FIELDTERMINATOR = ',',
// CHECK_CONSTRAINTS,
// DATAFILETYPE='widechar'
// FORMATFILE='D:/Applications/MAMP/htdocs/lab10/06_Bi_Scouter/php/format.xml'
// )

// $stmt = $pdo->prepare("BULK INSERT products_table FROM '/Applications/MAMP/htdocs/lab10/06_Bi_Scouter/product_db_txt/data.txt' WITH (FIRSTROW = 2,FIELDTERMINATOR = ',',CHECK_CONSTRAINTS,DATAFILETYPE='widechar',FORMATFILE='/Applications/MAMP/htdocs/lab10/06_Bi_Scouter/php/format.xml');
// ");
// $status = $stmt->execute();


$name = "石神千空";
$price = 1000;
$stock = 100;
$store_id = "au";
$store_name = "縄文";
$regist_date = "20201122";
$update_date = "";


//2. DB接続します
include("./funcs.php");
$pdo = db_conn();

$stmt = $pdo->prepare("INSERT INTO products_table(product_name,purchase_price,stock,store_id,store_name,regist_date,update_date)VALUES(:name,:price,:stock,:store_id,:store_name,regist_date,:update_date)
");
$stmt->bindValue(':name',   $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':price',  $price,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stock', $stock, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':store_id', $store_id, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':store_name', $store_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':regist_date', $regist_date, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//3．データ登録処理後
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト
    header("Location: pro_csv_before.php"); //リダイレクト
    exit();
  }
  
?>