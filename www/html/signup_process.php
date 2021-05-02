<?php

//新規登録判別ページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

/**
 * セッションに保存されている情報があれば商品一覧ページにリダイレクト???
 */
if(is_logined() === true){
  redirect_to(HOME_URL);
}

/**
 * postで送られてきたものを判別して変数に格納
 */
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

//dbハンドル取得
$db = get_db_connect();

try{
  //resist_user関数は新規登録情報のfalseを返す処理
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    //エラーメッセージをセッションに格納
    set_error('ユーザー登録に失敗しました。');
    //新規登録ページにリダイレクトする
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  //例外が発生した場合も上記と同じ処理
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
//登録完了のメッセージをセッションに格納
set_message('ユーザー登録が完了しました。');
//新規登録情報が正しい場合、データベースのユーザー情報を返す
login_as($db, $name, $password);
//上記処理が正しく行われた場合商品一覧ページにリダイレクト
redirect_to(HOME_URL);