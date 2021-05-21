<?php

//購入明細の表示は購入履歴と違い管理者とユーザーで分けなくてもいい

//購入明細のコントローラー

require_once '../conf/const.php';

require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'details.php';
require_once MODEL_PATH . 'history.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

//POSTから送られてきたトークンがセッションに保存されているトークンと等しいかを調べる関数
//falseだったときにはエラー分を表示しリダイレクトでログインページに戻す
if (is_valid_csrf_token(get_post('csrf_token')) === false) {
    set_error('アクセスが正しくありません');
    redirect_to(LOGIN_URL);
}

//ユーザーの注文番号を取得
$order_number = get_post('order_number');

//ユーザーの購入履歴を取得
$user_history_details = user_history_details($db, $order_number);
//ユーザーの購入明細を取得
$user_details = user_details($db, $order_number);


include_once VIEW_PATH. 'details_view.php';