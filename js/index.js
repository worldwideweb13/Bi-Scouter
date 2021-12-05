// chart.js
var ctx = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
        labels: ['6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        datasets: [
            {
            label: '棚卸額',
            data: [60, 65, 93, 85, totalStockAmount, 66, 47],
            backgroundColor: "rgba(219,39,91,0.5)"
            },{
            label: '売上額',
            data: [55, 45, 73, 75, 800000, 200000, 58],
            backgroundColor: "rgba(130,201,169,0.5)"
            },{
            label: '粗利額',
            data: [33, 45, 62, 55, 100000, 130000, 38],
            backgroundColor: "rgba(255,183,76,0.5)"
            }
        ]
        },
        options: {
        title: {
            display: true,
            text: '棚卸累計額'
        },
        scales: {
            yAxes: [{
            ticks: {
                suggestedMax: 1000000,
                suggestedMin: 0,
                stepSize: 100000,
                callback: function(value, index, values){
                return  value +  '円'
                }
            }
            }]
        },
        }
    });

// タブ切り替え
document.addEventListener('DOMContentLoaded', function () {
    var $tab__link = $('.tab1__link')
    var $tab_body_item = $('.tab1-body__item')
    $tab__link.on('click', function (e) {
        var target = $(e.currentTarget)
        //タブの表示非表示
        $tab__link.removeClass('on')
        target.addClass('on')
        //タブの中身の表示非表示
        var num = target.data('tab-body')
        $tab_body_item.removeClass('on')
        $('.tab1-body__item--' + num).addClass('on')
    })
    })

// モーダル画面描画
$(function(){
    $('.js-modal-open').each(function(){
        $(this).on('click',function(){
            var target = $(this).data('target');
            var modal = document.getElementById(target);
            if(target == "modal01"){
                $(modal).fadeIn();
            } else if(target == "modal02"){
                var tds = $($(this).parent().parent().get(0)).find('td');
                var product_name = tds[0].textContent;
                var sku = tds[1].textContent;
                $("#m_sku").text(sku);
                $("#m_product_name").val(product_name);
                $(modal).fadeIn();                                               
            } else if(target == "modal03"){
                var tds = $($(this).parent().parent().get(0)).find('td');
                var sku = tds[1].textContent;
                $("#delete").val(sku);
                $(modal).fadeIn();                        
            }
            return false;
        });
    });
    $('.js-modal-close').on('click',function(){
        $('.js-modal').fadeOut();
        return false;
    }); 
});
