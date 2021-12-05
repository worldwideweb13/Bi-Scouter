<?php
// ini_set('display_errors', 1);
session_start();
include("funcs.php");
// LOGIN チェック
sschk();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>Document</title>
</head>
<body>
     <?php
        //1.  DB接続します
        // include("funcs.php");
        $pdo = db_conn();
        //２．データ登録SQL作成
        $stmt = $pdo->prepare("SELECT * FROM products_table INNER JOIN stock_table ON products_table.sku=stock_table.sku");
        // $stmt = $pdo->prepare("SELECT * FROM products_table ORDER BY product_name DESC");
        $status = $stmt->execute();
        //３．データ表示
        $productArrayList = array();
        $productArray = array();
        $product_name ="";
        $sku ="";
        $price ="";
        $stock ="";
        $asin = "";
        $stockAmount;
        $totalStockAmount;
        $stock ="";
        if($status==false) {
            //execute（SQL実行時にエラーがある場合）
            $error = $stmt->errorInfo();
            exit("SQLError:".$error[2]);
        }else{
            //Selectデータの数だけ自動でループしてくれる
            // $res=1レコード取得、whileでレコード行数分、$resを繰り返す
            while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
                $product_name = $res["product_name"];
                $sku = $res["sku"];               
                $price = $res["purchase_price"];
                $stock = $res["stock"];
                $asin = $res["asin"];
                $stockAmount = $price * $stock;
                $totalStockAmount += $stockAmount;
                $productArray = array($product_name,$sku,$price,$stock,$stockAmount,$asin);
                array_push($productArrayList,$productArray);              
            }
            // echo('<pre>');
            // var_dump($productArrayList);
            // echo('</pre>');
            // exit;              
        } 

     ?>
    <div class="parts">
        <!-- Headerコンテンツ -->
        <div class="header">
            <img src="../img/rogo.png" alt="" id="headerogo">
            <div class="menu">
                <div class="session">
                    <div class="userinfo"><p><?= $_SESSION["name"] ?>さん、こんにちは！</p></div>
                    <div class="logout"><a href="logout.php"><p>LOG OUT</p></a></div>
                </div>
                <ul class="listmenu">
                <li><a href="./register.php">Register</a></li>
                <li><a href="./index.php">在庫レポート</a></li>
                <li><a href="./sales.php">売上レポート</a></li>
                <li><a href="./sheet_register.php">入庫処理</a></li>
                <li><a href="./pro_csv_before.php">売上集計</a></li>
                </ul>
            </div>
        </div>
        <!-- グラフ -->
        <canvas id="myBarChart"></canvas>
        <!--タブメニュー-->
        <ul class="tab1">
            <li class="tab1__item"><span class="tab1__link on" data-tab-body="1">在庫</span></li>
            <li class="tab1__item"><span class="tab1__link" data-tab-body="2">備品一覧</span></li>
            <li class="tab1__item"><span class="tab1__link" data-tab-body="3">不良在庫</span></li>
        </ul>
        <!--タブを切り替えて表示するコンテンツ-->       
        <div class="tab1-body">
            <div class="tab1-body__item tab1-body__item--1 on">
                <!-- 在庫テーブル表示 -->
                <div class="stocktable">
                    <table>
                        <tr>
                            <th>発売日</th>
                            <th>SKU</th>
                            <th>仕入値</th>
                            <th>在庫数</th>
                            <th>棚卸額</th>
                            <!-- モーダルウィンドウ処理 -->
                            <th class="regist_button"><a class="js-modal-open" href="" data-target="modal01">＋</a></th>
                            <th class="regist_button">ー</a></th>
                        </tr>
                        <?php foreach ($productArrayList as $key): ?>
                            <tr>
                                <td id="td_1"><a href="https://delta-tracer.com/item/detail/jp/<?= $key[5]; ?>"><?= $key[0]; ?></a></td>
                                <td id="td_2"><?= $key[1]; ?></td>
                                <td id="td_3"><?= "￥".number_format($key[2]); ?></td>
                                <td id="td_4"><?= number_format($key[3]); ?></td>
                                <td id="td_5"><?= "￥".number_format($key[4]); ?></td>
                                <td id="td_6" class=regist_button ><a class="js-modal-open" href="" data-target="modal02">＋</a></td>
                                <td id="td_7" class="regist_button"><a class="js-modal-open" href="" data-target="modal03">ー</a></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- 新規登録モーダルウィンドウ -->
                        <div id="modal01" class="modal js-modal">
                            <div class="modal__bg js-modal-close"></div>
                            <div class="modal__content">
                                <!-- 新規登録モーダル入力項目 -->
                                <form action="insert.php" method="POST">
                                    <div class="modal_form">
                                        <fieldset>
                                            <legend class="modal_title">新規登録</legend>
                                            <div class="modal_sku_box">
                                                <label>SKU: <input type="text" class="modal_common modal_sku" name="sku"></label><br>
                                            </div>                                            
                                            <label>商品名: <input type="text" class="modal_common modal_text" name="product_name"></label><br>
                                            <label>ASIN: <input type="text" class="modal_common modal_asin" name="asin"></label>
                                            <label> 在庫数：<input type="number" class="modal_common modal_stock" name="stock"></label><br>                                    
                                            <div class="modal_action">
                                                <input type="submit" class="button" action="" value="送信">
                                                <div class="close"><a class="js-modal-close" href="">閉じる</a></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- 編集登録モーダルウィンドウ -->
                        <div id="modal02" class="modal js-modal">
                            <div class="modal__bg js-modal-close"></div>
                            <div class="modal__content">
                                <!-- 編集登録モーダル入力項目 -->
                                <form action="update.php" method="POST">
                                    <div class="modal_form">
                                        <fieldset>
                                            <legend class="modal_title">編集登録</legend>
                                            <div class="modal_sku_box">
                                                <label> SKU：</label><div class="modal_sku" name="sku" id="m_sku"></div>
                                            </div>                                            
                                            <label>商品名: <input type="text" class="modal_common modal_text" name="product_name" id="m_product_name"></label><br>
                                            <label>ASIN: <input type="text" class="modal_common modal_asin" name="asin" id="m_asin"></label>
                                            <label> 在庫数：<input type="number" class="modal_common modal_stock" name="stock" id="m_stock"></label><br>                                    
                                            <div class="modal_action">
                                                <input type="submit" class="button" action="" value="送信">
                                                <div class="close"><a class="js-modal-close" href="">閉じる</a></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- 削除モーダルウィンドウ -->
                        <div id="modal03" class="modal js-modal">
                            <div class="modal__bg js-modal-close"></div>
                            <div class="modal__content">
                                <!-- モーダル表示項目 -->
                                <form action="delete.php" method="POST">
                                    <div class="modal_form">
                                        <fieldset>
                                            <legend class="modal_title">商品削除</legend>
                                            <p style="font-size:1.2em;">削除しますか？ ※一度削除したデータは戻せません！ご注意ください。</p>
                                            <input type="hidden" name="sku" id="delete" value="">
                                            <div class="modal_action">                         
                                                <input type="submit" class="button" action="" value="送信">
                                                <div class="close"><a class="js-modal-close" href="">閉じる</a></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </form>
                            </div>
                        </div>              
                    </table>    
                </div>
            </div>
            <div class="tab1-body__item tab1-body__item--2">
            タブのコンテンツ２
            </div>
            <div class="tab1-body__item tab1-body__item--3">
            タブのコンテンツ３
            </div>
        </div>
        <!-- JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <script type="text/javascript">var totalStockAmount = "<?= $totalStockAmount ?>";</script>
        <script type="text/javascript" src="../js/index.js"></script>
    </div>
</body>
</html>
