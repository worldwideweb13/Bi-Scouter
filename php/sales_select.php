<?php
// POSTされたJSON文字列を取り出し
$json = file_get_contents("php://input");
// JSON文字列をobjectに変換
$contents = json_decode($json,true);
var_dump($contents); 

$start_date = $contents["start_date"];
$last_date = $contents["last_date"];
// var_dump($start_date);
// var_dump($last_date);

// echo $last;

    // sales_tableからデータ取得処理
        //1.  DB接続します
            include("funcs.php");
            $pdo = db_conn();
        //２．データ登録SQL作成
            $sql = "SELECT sales_table.sku,sales_table.ts_date,sales_table.purchase_price,sales_table.txSales,sales_table.deposit, sales_table.sales_stock,products_table.asin, products_table.product_name FROM sales_table LEFT JOIN products_table ON sales_table.sku=products_table.sku WHERE sales_table.ts_date BETWEEN :start_date AND :last_date";           
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindValue(':last_date', $last_date, PDO::PARAM_STR);             
            $status = $stmt->execute();
        //３．データ表示
            $productArrayList = array();
            $productArray = array();
            $sku ="";
            $asin = "";
            $ts_date = "";
            $product_name ="";        
            $purchase_price ="";
            $txSales = "";
            $sales_stock ="";
            $deposit = "";
            $profit = "";
            if($status==false) {
            //execute（SQL実行時にエラーがある場合）
                $error = $stmt->errorInfo();
                exit("SQLError:".$error[2]);
            }else{
                //Selectデータの数だけ自動でループしてくれる
                // $res=1レコード取得、whileでレコード行数分、$resを繰り返す
                while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){              
                    $sku = $res["sku"];
                    $asin = $res["asin"];
                    $ts_date = $res["ts_date"];
                    $product_name = $res["product_name"];
                    $purchase_price = (int)$res["purchase_price"];
                    $txSales = (int)$res["txSales"];
                    $sales_stock = (int)$res["sales_stock"];
                    $deposit = (int)$res["deposit"];
                    $profit = $deposit - $purchase_price;
                    $productArray = array("sku"=>$sku,"asin"=>$asin,"ts_date"=>$ts_date,"product_name"=>$product_name,"purchase_price"=>$purchase_price,"txSales"=>$txSales,"sales_stock"=>$sales_stock,"deposit"=>$deposit,"profit"=>$profit);
                    array_push($productArrayList,$productArray);              
                }
            // echo('<pre>');
            // var_dump($productArrayList);
            // echo('</pre>');
            // exit;              
        } 

    header('Content-Type: application/json');
    echo json_encode([
        'result' => $productArrayList
        // 'result_2' => $last_date
    ]);