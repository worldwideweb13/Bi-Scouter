// 更新処理後の動作
window.onload = function(){
    // ページ読み込み時に実行したい処理
    if(alert_num == 1){
        var message = document.getElementById("load_message");
        message.innerHTML = "✔︎ 読み込み完了しました";
    }
}