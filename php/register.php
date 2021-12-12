<?php
// ini_set('display_errors', 1);
session_start();
include("funcs.php");
// LOGIN チェック
sschk();
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/index.css">         
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div class="parts">
            <!-- ヘッダーメニュー -->
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
        </div>
        <!-- 新規登録画面 -->
        
    </body>
    </html>