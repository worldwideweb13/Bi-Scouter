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
    <link rel="stylesheet" href="../css/pro_csv_before.css">    
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
    <p class="title">売上集計　CSVファイルから売上データを取り込みます</p>
    <p class="subtitle">※規定フォーマットのCSVファイルをアップロードして下さい</p>
    <form action="pro_csv_after_copy.php" id="input_form" method="post" enctype="multipart/form-data">
            <input type="file" class="file_up" name="fname"><br>
            <input type="submit" class="button" value="アップロード">
    </form>
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript">var alert_num = "<?= $alert_num ?>";</script>
    <script type="text/javascript" src="../js/pro_csv_before.js"></script>
</body>
</html>