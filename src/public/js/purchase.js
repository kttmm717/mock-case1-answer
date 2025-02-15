//支払い方法を即時プレビュー
const payment_method = document.getElementById('payment');
payment_method.addEventListener('change', function (e) {
    document.getElementById('pay_confirm').textContent = e.target.value == 'card' ? 'クレジットカード払い' : 'コンビニ払い';
    //e.target.valueはイベントが発生した要素のvalueを取得する
});

//配送先編集機能
const change_destination_btn = document.getElementById('destination__update');
//変更するボタンの要素取得
const set_destination_btn = document.getElementById('destination__setting');
//変更完了ボタンの要素取得

change_destination_btn.addEventListener('click', (e) => {
    e.target.style.display = "none";
    //イベントが発生した要素（change_destination_btn）のスタイルをnoneにする
    set_destination_btn.style.display = "unset";
    //変更完了ボタンをデフォルトの表示状態に戻す
    var inputs = document.getElementsByClassName('input_destination');
    for (const input of inputs) {
        input.readOnly = false;
        //inputのreadOnlyをfalseにして編集可能にする
    }
    inputs[0].focus();
});

set_destination_btn.addEventListener('click', (e) => {
//変更完了ボタンがクリックされたとき
    e.target.style.display = "none";
    //イベントが発生した要素（set_destination_btn）のスタイルをnoneにする
    change_destination_btn.style.display = "unset";
    //変更するボタンをデフォルトの表示状態に戻す
    var inputs = document.getElementsByClassName('input_destination');
    for (const input of inputs) {
        input.readOnly = true;
        //inputのreadOnlyをtrueにして読み取り専用にする
    }
});