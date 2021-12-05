<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <form action="sales_csv_after.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fname">
            <input type="submit" value="アップロード">
        </form>
    </div>
</body>
</html>