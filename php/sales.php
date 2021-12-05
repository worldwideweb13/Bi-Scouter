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
    <link rel="stylesheet" href="../css/sales.css">
    <title>Document</title>
</head>
<body>
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
        <!-- 日付入力画面 -->
            <p class="title">日付入力</p>
            <p class="subtitle">検索対象の日付を数字で入力してください</p>
            <form action="./test.php" id="input_form">
                <label for="sheet_name">開始日:  <input id="js_startDate" type="date" class="input_text" name="start_date"></label><br>
                <label for="sheet_id">終了日: <input id="js_lastDate" type="date" class="input_text" name="last_date"></label>
                <div class="submit_action">
                    <input type="button" id="submit_button" class="button" value="データ読込"></input><p class="load_action" id="load_message"></p>
                </div>
            </form>
        <!-- グラフ -->
        <!-- <canvas id="myBarChart"></canvas> -->
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
                        <thead>
                            <tr>
                                <th>販売日</th>
                                <th>商品名</th>
                                <th>SKU</th>
                                <th>個数</th>
                                <th>販売額</th>
                                <th>仕入値</th>
                                <th>粗利</th>
                                <!-- モーダルウィンドウ処理 -->
                                <!-- <th class="regist_button"><a class="js-modal-open" href="" data-target="modal01">＋</a></th>
                                <th class="regist_button">ー</a></th> -->
                            </tr>
                        </thead>
                        <tbody id="js-myTable"></tbody>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js" integrity="sha512-DZqqY3PiOvTP9HkjIWgjO6ouCbq+dxqWoJZ/Q+zPYNHmlnI2dQnbJ5bxAHpAMw+LXRm4D72EIRXzvcHQtE8/VQ==" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../js/sales.js"></script>
    </div>
</body>
</html>
