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
            // $score ="";
            // $stockScore ="";
            $sales_table = array();
            $stock_table = array();
            $rt_sales_table = array();
            $ts_table = array();
            $tsf_table = array();
            while ($list = fgetcsv($fp)) {
                // CSVファイルの七行目までは不要行のため処理を飛ばす   
                if($c <= 6){
                    $c++;
                    continue;
                }               
                // 文字コード変換をして文字化けを解消   
                $elem = mb_convert_encoding($list,'UTF-8', 'SJIS');
                $sales_date = str_replace("JST","",$elem[0]);
                $status = $elem[2];
                $ts_id = $elem[3];
                $sku = $elem[4];
                $product_name = $elem[5];
                $stock = (int)$elem[6];
                $sales = (int)str_replace(",","",$elem[13]);
                $tax = (int)str_replace(",","",$elem[14]);
                $txSales = (int)$sales+$tax;
                $deposit = (int) str_replace(",","",$elem[26]);
                $update_date = date("Ymd");
                // (2)SKUから値を整数型で抜き出す
                $sku_array = explode("-", $elem[4]);
                $store_id = $sku_array[0];
                $regist_date = $sku_array[1];
                $purchase_price = (int)$sku_array[3];
                // 条件分岐処理(注文ステータスで格納先のテーブルを分けます)
                // 返金テーブル ="purchase_price"
                if(strpos($status,'返金') !== false){
                    $ts_list = array("ts_date"=>$sales_date, "ts_id"=>$ts_id, "sku"=>$sku, "purchase_price"=>$purchase_price, "sales"=>-1*$sales, "tax"=>-1*$tax, "rt_stock"=>$stock, "txSales"=>-1*$txSales,"deposit"=>-1*$deposit, "up_date"=>$update_date);
                    array_push($rt_sales_table,$ts_list);
                    continue;
                }
                // 返品手数料テーブル="tsf_table"               
                if(strpos($status,'在庫関連の手数料') !== false || strpos($status,'注文外料金') !== false ){
                    $tsf_list = array("ts_date"=>$sales_date, "reason"=>$product_name,"deposit"=>-1*$deposit,"up_date"=>$update_date);
                    array_push($tsf_table,$tsf_list);
                    continue;
                }
                // 入金額テーブル="$ts_table"               
                if(strpos($status,'振込み') !== false){
                    $ts_list = array("ts_date"=>$sales_date, "explanation"=>$product_name,"deposit"=>-1*$deposit,"up_date"=>$update_date);
                    array_push($ts_table,$ts_list);
                    continue;
                }
                // 売上テーブル&在庫管理テーブル
                if(strpos($status,'注文') !== false){
                    // 売上テーブル
                    $sales_list = array("ts_date"=>$sales_date, "ts_id"=>$ts_id, "sku"=>$sku,"purchase_price"=>$purchase_price, "sales"=>$sales, "sales_stock"=>$stock, "tax"=>$tax, "txSales"=>$txSales, "deposit"=>$deposit, "up_date"=>$update_date);
                    array_push($sales_table,$sales_list);
                    // 在庫テーブル=stock_table をSKU毎に個数をまとめて格納する処理
                    // $keyIndexにマイナス値を入れておくことで0以上のkeyIndex番号がtrueと判断される
                    $keyIndex = -1;
                    // array_column = 2次元配列からSKUのみ抜き出し配列化
                    // array_search = array_columnで配列化したSKUリストから"$sku"に一致する配列番号を返す  
                    $i = array_search($sku, array_column($stock_table,'sku'));
                    // array_searchで0以上の値が取得できた時に$keyIndexに$iを代入
                    if($i !==false){
                        $keyIndex = $i; 
                    }
                    if ( 0 <= $keyIndex ){
                        //$result = "stock_tableの更新対象SKUの在庫数" 
                        $result = $stock_table[$keyIndex]["stock"];
                        $u_stock = $result+$stock;
                        //加算した在庫数をstock_tableの対象配列番号["stock"]で格納
                        $stock_table[$keyIndex]["stock"]=$u_stock;
                    } else {
                        // 在庫管理テーブル="stock_table"
                        $stock_list = array("sku"=>$sku, "stock"=>$stock, "up_date"=>$update_date);
                        array_push($stock_table,$stock_list);
                        continue;
                    }                     
                }
            } 
            // echo('<pre>');
            // var_dump ($stock_table);
            // echo('</pre>');
            // exit;
            fclose($fp);
            include("./funcs.php");
            $pdo = db_conn();
            // 返金テーブル="rt_sales_table"の更新処理
                foreach($rt_sales_table as $val){
                    $sql = "INSERT INTO rt_sales_table(ts_id,ts_date,sku,purchase_price,sales,tax,txSales,rt_stock,deposit,up_date) VALUES(:ts_id,:ts_date,:sku,:purchase_price,:sales,:tax,:txSales,:rt_stock,:deposit,:up_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':ts_id', $val["ts_id"], PDO::PARAM_STR); 
                    $stmt->bindValue(':ts_date', $val["ts_date"], PDO::PARAM_STR); 
                    $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
                    $stmt->bindValue(':purchase_price', $val["purchase_price"], PDO::PARAM_INT); 
                    $stmt->bindValue(':sales', $val["sales"], PDO::PARAM_INT); 
                    $stmt->bindValue(':tax', $val["tax"], PDO::PARAM_INT); 
                    $stmt->bindValue(':txSales', $val["txSales"], PDO::PARAM_INT); 
                    $stmt->bindValue(':rt_stock', $val["rt_stock"], PDO::PARAM_INT); 
                    $stmt->bindValue(':deposit', $val["deposit"], PDO::PARAM_INT); 
                    $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_STR); 
                    $rt_sales_table_record = $stmt->execute();
                };
                if($rt_sales_table_record==false){
                    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    $error = $stmt->errorInfo();
                    exit("SQLError:".$error[2]);
                    } else{
                        echo("返金テーブル更新処理完了");
                    }                
            // 返品手数料テーブル="tsf_table"の更新処理
                foreach($tsf_table as $val){
                    $sql = "INSERT INTO tsf_table(ts_date,reason,deposit,up_date) VALUES(:ts_date,:reason,:deposit,:up_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':ts_date', $val["ts_date"], PDO::PARAM_STR); 
                    $stmt->bindValue(':reason', $val["reason"], PDO::PARAM_STR); 
                    $stmt->bindValue(':deposit', $val["deposit"], PDO::PARAM_INT); 
                    $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_STR); 
                    $tsf_table_record = $stmt->execute();
                };
                if($tsf_table_record==false){
                    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    $error = $stmt->errorInfo();
                    exit("SQLError:".$error[2]);
                    } else{
                        echo("返品手数料テーブル更新処理完了");
                    }                  
            //  入金額テーブル="$ts_table"の更新処理              
                foreach($ts_table as $val){
                    $sql = "INSERT INTO ts_table(ts_date,explanation,deposit,up_date) VALUES(:ts_date,:explanation,:deposit,:up_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':ts_date', $val["ts_date"], PDO::PARAM_STR); 
                    $stmt->bindValue(':explanation', $val["explanation"], PDO::PARAM_STR); 
                    $stmt->bindValue(':deposit', $val["deposit"], PDO::PARAM_INT); 
                    $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_STR); 
                    $ts_table_record = $stmt->execute();
                };
                if($ts_table_record==false){
                    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    $error = $stmt->errorInfo();
                    exit("SQLError:".$error[2]);
                    } else{
                        echo("入金額テーブル更新処理完了");
                    } 

            // 売上テーブル="sales_table"の更新処理
                foreach($sales_table as $val){
                    $sql = "INSERT INTO sales_table(ts_id,ts_date,sku,purchase_price,sales,tax,txSales,sales_stock,deposit,up_date) VALUES(:ts_id,:ts_date,:sku,:purchase_price,:sales,:tax,:txSales,:sales_stock,:deposit,:up_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':ts_id', $val["ts_id"], PDO::PARAM_STR); 
                    $stmt->bindValue(':ts_date', $val["ts_date"], PDO::PARAM_STR); 
                    $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
                    $stmt->bindValue(':purchase_price', $val["purchase_price"], PDO::PARAM_INT); 
                    $stmt->bindValue(':sales', $val["sales"], PDO::PARAM_INT); 
                    $stmt->bindValue(':tax', $val["tax"], PDO::PARAM_INT); 
                    $stmt->bindValue(':txSales', $val["txSales"], PDO::PARAM_INT); 
                    $stmt->bindValue(':sales_stock', $val["sales_stock"], PDO::PARAM_INT); 
                    $stmt->bindValue(':deposit', $val["deposit"], PDO::PARAM_INT); 
                    $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_STR); 
                    $sales_table_record = $stmt->execute();
                };
                if($sales_table_record==false){
                    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    $error = $stmt->errorInfo();
                    exit("SQLError:".$error[2]);
                    } else{
                        echo("売上テーブル更新処理完了");
                    }                 
            //  在庫管理テーブル="stock_table"→課題：stocktableのstockを加減算するsql構文に書き換え
                foreach($stock_table as $val){
                    $sql = "INSERT INTO stock_table (sku,stock,up_date) values (:sku,:stock,:up_date) ON DUPLICATE KEY UPDATE stock = stock+ :stock,up_date = :up_date";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
                    $stmt->bindValue(':stock', $val["stock"], PDO::PARAM_INT); 
                    $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_INT); 
                    $stock_table_record = $stmt->execute();
                };
                // foreach($stock_table as $val){
                //     $sql = "INSERT INTO stock_table(sku,stock,up_date) VALUES(:sku,:stock,:up_date)";
                //     $stmt = $pdo->prepare($sql);
                //     $stmt->bindValue(':sku', $val["sku"], PDO::PARAM_STR); 
                //     $stmt->bindValue(':stock', $val["stock"], PDO::PARAM_STR); 
                //     $stmt->bindValue(':up_date', $val["up_date"], PDO::PARAM_INT); 
                //     $status = $stmt->execute();
                // };
                //2．データ登録処理後
                if($status==false){
                    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    $error = $stmt->errorInfo();
                    exit("SQLError:".$error[2]);
                    } else{
                        echo("在庫管理テーブル処理完了");
                    }           
        ?>        
    </div>
</body>
</html>