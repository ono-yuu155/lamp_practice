<?php

//カートの中身を表示するページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();


if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

//ユーザーIDを抽出条件にユーザーの情報を取得する
$user = get_login_user($db);

//ユーザーのカートの情報を取得
$carts = get_user_carts($db, $user['user_id']);

//ユーザーのカートの合計金額を変数に格納
$total_price = sum_carts($carts);

//生成したトークンを変数に格納　元となるページのためこのファイルにのみこの関数を実行する
$token = get_csrf_token();


include_once VIEW_PATH . 'cart_view.php';