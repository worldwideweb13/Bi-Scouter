// document.addEventListener('DOMContentLoaded', function(){
//     // 'tab'クラスを持つ要素をHTMLCollection形式で取得
//     const tabs = document.getElementsByClassName('tab');
//     console.log(tabs);
//     // tabs.kength=tab'クラスを要素の数,HTMLCollection内[i]番目のtabクラス要素がクリックされた時にtabSwitchアクション起動
//     for(let i = 0; i < tabs.length; i++) {
//       tabs[i].addEventListener('click', tabSwitch);
//     }
  
//     // タブをクリックすると実行する関数
//     function tabSwitch(){
//       // 'is-show'classを持つ最初の要素[0]を取得して、'is-active'クラスを削除(タブの表示切り替え)
//       document.getElementsByClassName('is-active')[0].classList.remove('is-active');
//       this.classList.add('is-active');
//       // 'is-show'classを持つ最初の要素[0]を取得して、'is-show'クラスを削除(タブ画面の表示切り替え)
//       document.getElementsByClassName('is-show')[0].classList.remove('is-show');
//       // Array.prototype.slice.call(tabs) = tabsを配列に変換
//       const arrayTabs = Array.prototype.slice.call(tabs);
//       const index = arrayTabs.indexOf(this);
//       document.getElementsByClassName('panel')[index].classList.add('is-show');
//     }
//   });


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