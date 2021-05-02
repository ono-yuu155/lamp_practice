<?php

//商品管理情報ページ

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

//ユーザーのタイプが管理者じゃない場合はログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//すべての商品の情報を取得する
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
