<!-- phpinfo(); -->
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/test.css">
    <link rel="stylesheet" href="../css/reset.css">
    <title>Document</title>
</head>
<body> -->
<?php
//   // 在庫数の更新処理
//     $stock = 4;
//     $sku = "oc-20200303-33-00139-o-se";
//     $updat_date = date("Ymd");
//     $Array_1 = array("sku"=>"oc-20200303-33-00139-o-so","stock"=>3,"date"=>$updat_date);
//     $Array_2 = array("sku"=>"oc-20200303-37-01155-o-so","stock"=>6,"date"=>$updat_date);
//     $Array_3 = array("sku"=>"io-20200303-10-02222-o-so","stock"=>9,"date"=>$updat_date);    
//     $stockArray = array($Array_1,$Array_2,$Array_3);
//     // $keyIndexにマイナス値を入れておくことで0以上のkeyIndex番号がtrueと判断される
//     $keyIndex = -1;
//     // array_column = 2次元配列からSKUのみ抜き出し配列化
//     // array_search = rray_columnで配列化したSKUリストから"$sku"に一致する配列番号を返す  
//     $i = array_search($sku, array_column($stockArray,'sku'));
//     // array_searchで0以上の値が取得できた時に$keyIndexに$iを代入
//     if($i !==false){
//       $keyIndex = $i; 
//     }
//     if ( 0 <= $keyIndex ){
//       $result = $stockArray[$keyIndex];
//       $b_stock = $result["stock"];
//       $u_stock = $b_stock+$stock;
//       $stockArray[$keyIndex]["stock"]=$u_stock;
//     } else {
//       $Array_4 = array("sku"=>$sku,"stock"=>$stock,"date"=>$updat_date);
//       array_push($stockArray,$Array_4);        
//     }
//     echo('<pre>');                         
//     var_dump($stockArray);
//     echo('</pre>'); 

//       $result = $stockArray[$keyIndex];
//       var_dump($result);

//     // 返り値    
//       if($result){
//           $u_stock = $result["stock"] + $stock;
//           $result['stok'] = $u_stock;          
//       } else {
//           $stock_item = array("sku"=>$sku,"stock"=>$stock,"update_date"=>$update_date);
//           array_push($stokArray,$stock_item);                   
//       }
  
// $credentials_path = '../credentials.json';
// $client = new \Google_Client();
// $client->setScopes([
//     \Google_Service_Sheets::SPREADSHEETS, // スプレッドシート
//     \Google_Service_Sheets::DRIVE, // ドライブ
// ]);
// $client->setAuthConfig($credentials_path);


// // スプレッドシートからデータ取得
// $spreadsheet_service = new \Google_Service_Sheets($client);


// $spreadsheet_id = '1LF5yLMTUNRihxLRINZX2OLukcZd0rjhRytJEG_EjBPQ';
// $range = 'Sheet1!A1:C5'; // 取得する範囲
// $response = $spreadsheet_service->spreadsheets_values->get($spreadsheet_id, $range);
// $values = $response->getValues();
// print_r($values);

?>


  <!-- <ul class="tab1">
    <li class="tab1__item on"><span class="tab1__link" data-tab-body="1">在庫</span></li>
    <li class="tab1__item"><span class="tab1__link" data-tab-body="2">備品一覧</span></li>
    <li class="tab1__item"><span class="tab1__link" data-tab-body="3">不良在庫</span></li>
  </ul>

  <div class="tab1-body">
    <div class="tab1-body__item tab1-body__item--1 on">
      タブのコンテンツ１
    </div>
    <div class="tab1-body__item tab1-body__item--2">
      タブのコンテンツ２
    </div>
    <div class="tab1-body__item tab1-body__item--3">
      タブのコンテンツ３
    </div>
  </div> -->
<!-- <div class="tab-panel">
  <div class="c-tabs_wrap">
        <ul class="c-tabs_list">
            <li class="tab c-tabs_item is-active"><span>在庫</span></li>
            <li class="tab c-tabs_item"><a href="">備品</a></li>
            <li class="tab c-tabs_item"><a href="">不良在庫</a></li>
        </ul>
  </div> -->
  <!--タブ-->
  <!-- <ul class="tab-group">
    <li class="tab tab-A is-active">Tab-A</li>
    <li class="tab tab-B">Tab-B</li>
    <li class="tab tab-C">Tab-C</li>  
 </ul> -->
  <!--タブを切り替えて表示するコンテンツ-->
  <!-- <div class="panel-group">
    <div class="panel tab-A is-show">Content-A</div>
    <div class="panel tab-B">Content-B</div>
    <div class="panel tab-C">Content-C</div>
  </div>
</div> -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/test.js"></script>
</body>
</html>