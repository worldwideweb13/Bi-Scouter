<?php
// 
$spreadSheetId = $_POST["sheet_id"];
$spreadSheetName = $_POST["sheet_name"];

// スプレッドシートの URL にある spreadSheetId
    $spreadSheetId = '1LF5yLMTUNRihxLRINZX2OLukcZd0rjhRytJEG_EjBPQ';
    $spreadSheetName = '2020_仕入台帳';
// DB各tableに共通で渡す値
    $up_date = date("Ymd");

// GoogleSpreadSheet連携時の認証情報確認
    require_once __DIR__ . '/vendor/autoload.php';
    define('APPLICATION_NAME', $spreadSheetName);
    define('CLIENT_SECRET_PATH', './credentials.json');
// スコープの設定
    define('SCOPES', implode(' ', array(
    Google_Service_Sheets::SPREADSHEETS)
    ));

// アカウント認証情報インスタンスを作成
$client = new Google_Client();
$client->setScopes(SCOPES);
$client->setAuthConfig(CLIENT_SECRET_PATH);
// シート名を取得
$sheet_1 = new Google_Service_Sheets($client);
// スプレッドシートからシート名を取得
$sheet_title = $sheet_1->spreadsheets->get($spreadSheetId);
// $sheet_title_list = シート名を配列で格納
$sheet_title_list= array();
foreach($sheet_title->getSheets() as $sheet) {
    $sheet_name = $sheet->properties->title;
    if($sheet_name == "format"){
        continue;
    }
    array_push($sheet_title_list,$sheet_name);    
}
// echo('<pre>');
// var_dump($sheet_title_list);
// echo('</pre>');
// exit;

// DBサーバー接続
    include("./php/funcs.php");
    $pdo = db_conn();

// sheet_table内の登録情報確認
    $sql = "SELECT sheet_name FROM sheet_table WHERE file_name = :file_name ORDER BY sheet_name DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':file_name', $spreadSheetName, PDO::PARAM_STR); 
    $record = $stmt->execute();
//2．データ登録処理後
    $record_sheet_array = array();
    if($record==false){
        // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt->errorInfo();
        exit("SQLError:".$error[2]);
        } else{
            //Selectデータの数だけ自動でループしてくれる
            while($sql_record = $stmt->fetch(PDO::FETCH_ASSOC)){
                $record_sheet_name = $sql_record["sheet_name"];
                array_push($record_sheet_array,$record_sheet_name);
            }        
        }

$diff_array = array_diff($sheet_title_list,$record_sheet_array);

// echo('<pre>');
// var_dump($diff_array);
// echo('</pre>');
// exit;

// $diff_arrayを引数にスプレッドシートから商品データを取得
    $product_list = array();
    foreach($diff_array as $val){
        $sheet_2 = new Google_Service_Sheets($client);
        $range = $val.'!H6:T';
        $product_data = $sheet_2->spreadsheets_values->get($spreadSheetId, $range);
        $values = $product_data->getValues();    
        foreach ($values as $val) {
            $sku  = $val[0];
            $asin = $val[4];
            $product_condition = $val[6];
            $product_name = $val[9];
            $stock = (int)$val[12];
            $purchase_price = (int)$val[10];
            // SKUから値取り出し
            $skuArray = explode("-",$sku);
            $store_id = $skuArray[0];
            $regist_date = $skuArray[1];
            if($asin==""){
                continue;
            }
            // $keyIndexにマイナス値を入れておくことで0以上のkeyIndex番号がtrueと判断される
            $keyIndex = -1;
            // array_search = array_columnで配列化したSKUリストから"$sku"に一致する配列番号を返す  
            $i = array_search($sku, array_column($product_list,'sku'));
            // array_searchで0以上の値が取得できた時に$keyIndexに$iを代入
            if($i !==false){
                $keyIndex = $i; 
            }
            if ( 0 <= $keyIndex ){
                //$result = "stock_tableの更新対象SKUの在庫数" 
                $result = $product_list[$keyIndex]["stock"];
                $u_stock = $result+$stock;
                //加算した在庫数をstock_tableの[対象配列番号]["stock"]で格納
                $product_list[$keyIndex]["stock"]=$u_stock;
            } else {
                // productable
                $product_array = array("sku"=>$sku,"asin"=>$asin,"category_id"=>1,"product_name"=>$product_name,"purchase_price"=>$purchase_price,"product_condition"=>$product_condition,"store_id"=>$store_id,"regist_date"=>$regist_date,"stock"=>$stock,"up_date"=>$up_date);
                array_push($product_list,$product_array);        
            }        
        }
    }
// echo('<pre>');
// var_dump($product_list);
// echo('</pre>');
// exit;

// 1.products_tableに商品登録
    foreach($product_list as $val){
        $sql = "INSERT IGNORE INTO products_table(sku,asin,category_id,product_name,purchase_price,product_condition,store_id,regist_date,up_date) VALUES(:sku,:asin,:category_id,:product_name,:purchase_price,:product_condition,:store_id,:regist_date,:up_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
        $stmt->bindValue(':asin', $val["asin"], PDO::PARAM_STR); 
        $stmt->bindValue(':category_id', $val["category_id"], PDO::PARAM_STR); 
        $stmt->bindValue(':product_name', $val["product_name"], PDO::PARAM_STR); 
        $stmt->bindValue(':purchase_price', $val["purchase_price"], PDO::PARAM_INT); 
        $stmt->bindValue(':product_condition', $val["product_condition"], PDO::PARAM_STR); 
        $stmt->bindValue(':store_id', $val["store_id"], PDO::PARAM_STR); 
        $stmt->bindValue(':regist_date', $val["regist_date"], PDO::PARAM_INT); 
        $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_INT); 
        $product_table_record = $stmt->execute();
    };
    //2．データ登録処理後
        if($product_table_record==false){
            // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt->errorInfo();
            exit("SQLError:".$error[2]);
            } else{
                echo("product_table処理完了");
            }
    
// 1.stock_tableのデータ更新
    foreach($product_list as $val){
        $sql = "INSERT INTO stock_table (sku,stock,up_date) values (:sku,:stock,:up_date) ON DUPLICATE KEY UPDATE stock = stock+ :stock,up_date = :up_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
        $stmt->bindValue(':stock', $val["stock"], PDO::PARAM_INT); 
        $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_INT); 
        $stock_table_record = $stmt->execute();
    };
    //2．データ登録処理後
        if($stock_table_record==false){
            // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt->errorInfo();
            exit("SQLError:".$error[2]);
            } else{
                echo("stock_table処理完了");
            }

// 1. products_table,stock_table更新後、sheet_tableにシート名を登録
    foreach($diff_array as $val){
        $sql = "INSERT INTO sheet_table(sheet_name,file_name,up_date) VALUES(:sheet_name,:file_name,:up_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sheet_name', $val, PDO::PARAM_STR); 
        $stmt->bindValue(':file_name', $spreadSheetName, PDO::PARAM_STR); 
        $stmt->bindValue(':up_date', $up_date, PDO::PARAM_INT); 
        $subscribe_status = $stmt->execute();
    };

    //2．データ登録処理後
        if($subscribe_status==false){
            // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt->errorInfo();
            exit("SQLError:".$error[2]);
            } else{
                redirect("php/sheet_register.php?alert_num=1");                
            } 
?>