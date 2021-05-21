<?php

//商品の情報をデータベースに追加する処理

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

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

//ユーザータイプが管理者ではない時ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postから送られてくる値を変数に格納
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
//fileから送られてくるnameも変数化
$image = get_file('image');

//POSTから送られてきたトークンがセッションに保存されているトークンと等しいかを調べる関数
//falseだったときにはエラー分を表示しリダイレクトでログインページに戻す
if (is_valid_csrf_token(get_post('csrf_token')) === false) {
  set_error('アクセスが正しくありません');
  redirect_to(LOGIN_URL);
}


//regist_item関数が正しく行われたら新規商品を登録する
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}


redirect_to(ADMIN_URL);