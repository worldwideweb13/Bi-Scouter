<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">    
    <title>Document</title>
</head>
<body>
    <div class="parts">
        <!-- Headerコンテンツ -->
        <div class="header">
            <img src="../img/rogo.png" alt="" id="headerogo">
            <div class="menu">
                <ul class="stocklist">
                <li><a href="./index.php">Register</a></li>
                <li><a href="./index.php">Stock Report</a></li>
                <li><a href="#news">Sales Report</a></li>
                <li><a href="./pro_csv_before.php">Sales-CSV Import</a></li>
                <li><a href="./pro_csv_before.php">Stock-CSV Import</a></li>
                </ul>
            </div>
        </div>
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
            $score ="";
            $stockScore ="";
            while ($list = fgetcsv($fp)) {
                // 先頭行は処理を飛ばす   
                if($c ==0){
                    $c++;
                    continue;
                }
                // 文字コード変換をして文字化けを解消   
                $elem = mb_convert_encoding($list,'UTF-8', 'SJIS');
                // echo('<pre>');
                // var_dump($list);
                $stock = (int)$elem[4];
                $product_name = $elem[3];
                // 不要なデータを削除
                if(strpos($product_name,'納品不備商品') !== false){
                    continue;
                }
                $sku = $elem[2];
                // (2)SKUから値を整数型で抜き出す
                $sku_array = explode("-", $elem[2]);
                $store_id = $sku_array[0];
                $regist_date = $sku_array[1];
                $purchase_price = (int)$sku_array[3];
                $update_date = date("Ymd");
                $category_id = 1;
                if($c==1){
                    $c++;
                    $score = "("."'".$category_id."'".","."'".$product_name."'".","."'".$sku."'".","."'".$store_id."'".","."'".$regist_date."'".","."'".$purchase_price."'".","."'".$update_date."'".")";
                    $stockScore ="(". "'".$stock."'".","."'".$sku."'".","."'".$update_date."'".")";
                    continue;
                }
                $score .= ","."("."'".$category_id."'".","."'".$product_name."'".","."'".$sku."'".","."'".$store_id."'".","."'".$regist_date."'".","."'".$purchase_price."'".","."'".$update_date."'".")";
                $stockScore .= ","."(". "'".$stock."'".","."'".$sku."'".","."'".$update_date."'".")";  
            }
            // var_dump ($score);
            // echo('<pre>');
            // echo($score);
            // exit;
            // echo('<pre>');
            fclose($fp);
            include("./funcs.php");
            $pdo = db_conn();
            $stmt = $pdo->prepare("INSERT INTO products_table(category  _id,product_name,sku,store_id,regist_date,purchase_price,update_date) VALUES $score");
            $status = $stmt->execute();
            //2．データ登録処理後
            if($status==false){
                // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                $error = $stmt->errorInfo();
                exit("SQLError:".$error[2]);
                }            
            $stmt = $pdo->prepare("INSERT INTO stock_table(stock,sku,update_date) VALUES $stockScore");
            $status = $stmt->execute();
        ?>        
    </div>
</body>
</html>