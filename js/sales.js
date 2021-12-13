function insertTable(start_date,last_date){
  const url = '../php/sales_select.php';
  const params = {
      start_date: start_date,
      last_date: last_date,    
  };
  axios.post(url,params).then(res => {
    res.data.result.forEach(key => {
        console.log("OK?");     
        console.log(key);
        //const wrapper = $('#js-myTable')
        const wrapper = document.getElementById('js-myTable')
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



