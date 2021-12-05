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
$c=0;

while ($list = fgetcsv($fp)) {
     // 先頭行は処理を飛ばす   
    if($c ==0){
        $c++;
        continue;
    }
    // 文字コード変換をして文字化けを解消   
    $elem = mb_convert_encoding($list,'UTF-8', 'SJIS');
    // (1)在庫数を整数型で抜き出す
    // echo('<pre>');
    // var_dump($list);
    $stock = (int)$elem[4];
    $product_name = $elem[3];
    if(strpos($product_name,'納品不備商品') !== false){
        continue;
    }
    $sku = $elem[2];
    // (2)SKUから値を整数型で抜き出す
    $sku_array = explode("-", $elem[2]);
    $store_id = $sku_array[0];
    $regist_date = $sku_array[1];
    $purchase_price = (int)$sku_array[3];
    $asin = "";
    // 取り出した値を配列化
    $recordArray = array($sku,$product_name,$stock,$purchase_price,$store_id,$asin,$regist_date,20201123);
    // $recordArrayを","区切りでテキストデータに置換
    $txt = implode(",",$recordArray); 
    // DBフォーマットに整形
    $file = fopen("../product_db_txt/data.txt","a");
    fwrite($file, $txt."\r\n");
    fclose($file);
}
fclose($fp);

?>