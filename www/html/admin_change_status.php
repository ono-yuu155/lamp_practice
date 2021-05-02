<?php

//ステータス変更の処理

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//dbハンドル取得
$db = get_db_connect();

$user = get_login_user($db);

//ユーザータイプが管理者ではない時ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//POSTで送られてきた値を取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//$change_toの値がopenだったら非公開->公開に変更処理を実行、closeだったら公開->非公開に実行する
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

//admin.phpにリダイレクトする
redirect_to(ADMIN_URL);