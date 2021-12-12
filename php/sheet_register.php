<?php
session_start();
// LOGIN チェック
include("funcs.php");
sschk();
// アップロード後メッセージ通知処理
$alert_num = $_GET["alert_num"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/sheet_register.css">    
    <title>Document</title>
</head>
<body>
    <div class="header">
        <!-- ヘッダーメニュー -->
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
    <p class="title">入庫処理　spreadsheetから商品データを取り込みます</p>
    <p class="subtitle">※管理者にてspreadsheetとscouterのデータ連携を事前に済ませて下さい</p>
    <form action="../sheet_insert.php" id="input_form">
        <label for="sheet_name">シート名:  <input type="text" class="input_text" name="sheet_name"></label><br>
        <label for="sheet_id">シートID: <input type="text" class="input_text" name="sheet_id"></label>
        <div class="submit_action">
            <input type="submit" class="button" value="データ読込"></input><p class="load_action" id="load_message"></p>
        </div>
    </form>
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript">var alert_num = "<?= $alert_num ?>";</script>
    <script type="text/javascript" src="../js/sheet_register.js"></script>
</body>
</html>