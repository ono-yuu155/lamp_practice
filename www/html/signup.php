<?php

//新規登録ページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

/**
 * セッションに保存されている情報があれば商品一覧ページにリダイレクト???
 */
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'signup_view.php';



