<?php
//共通に使う関数を記述

//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}
function db_conn(){
    try {
        //Password:MAMP='root',XAMPP=''
        // dbname=(データベースの名前)host=(さくらサーバーのアドレス？),ID,PASSを記述
        $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','root');
        // $pdo = new PDO('mysql:dbname=worldwideweb_gs_learning;charset=utf8;host=mysql57.worldwideweb.sakura.ne.jp','worldwideweb','JYAGAR13');
        return $pdo;
      } catch (PDOException $e) {
        exit('DBConnectError:'.$e->getMessage());
      }
}


//SQLエラー
function sql_error($stmt){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);
}

function redirect($file_name){
  header("Location:".$file_name);
  exit();
}

//SessionCheck
function sschk(){
  if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!= session_id()){
    exit("Login Error");
  }else{
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();
  }
}

