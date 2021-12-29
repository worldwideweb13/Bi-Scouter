function insertTable(start_date,last_date){
  const url = '../php/sales_select.php';
  const params = {
      start_date: start_date,
      last_date: last_date,    
  };
  axios.post(url,params).then(res => {
    // (1)(2)(3)をレコード回数分繰り返す
    res.data.result.forEach(key => {
        // (1) id=js-myTable の要素を取得
        const wrapper = document.getElementById('js-myTable')
        // (2) html...売上データの１レコード。（テンプレートリテラルでHTML文字列にする）
        const html = `
        <tr>
          <td id="td_1">${key["ts_date"]}</td>
          <td id="td_2"><a href="https://delta-tracer.com/item/detail/jp/${key["asin"]}">${key["product_name"]}</a></td>
          <td id="td_3">${key["sku"]}</td>
          <td id="td_4">${key["sales_stock"]}</td>
          <td id="td_5">￥${key["txSales"]}</td>
          <td id="td_6">￥${key["purchase_price"]}</td>
          <td id="td_7">￥${key["profit"]}</td>
        </tr>  
        `;
        // (3) (1)の直下に(2)を挿入する
        wrapper.insertAdjacentHTML('beforeend',html)    
    });
  });
}

document.getElementById("submit_button").onclick = function(){
    const start_date = document.getElementById("js_startDate").value;
    const last_date = document.getElementById("js_lastDate").value;   
    const wrapper = document.getElementById('js-myTable');
    console.log(start_date,last_date);
    wrapper.innerHTML = "";
    insertTable(start_date,last_date);
}



