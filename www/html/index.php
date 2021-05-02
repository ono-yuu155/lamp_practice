<?php

//商品一覧ページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();


if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

//ユーザーIDを抽出条件にユーザーの情報を取得する
$user = get_login_user($db);

//ステータスが１の商品を取得
$items = get_open_items($db);

//viewファイル読み込み
include_once VIEW_PATH . 'index_view.php';