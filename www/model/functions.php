<?php

/**
 * var_dumpの結果を取得
 */
function dd($var){
  var_dump($var);
  exit();
}

/**
 * 引数で指定したファイルにリダイレクトする
 */
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

/**
 * $_GETで送られてきたキーが一致した場合は値を取得する
 */
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

/**
 * ＄＿POSTで送られてきたキーが一致した場合は値を取得する
 */
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

/**
 * ＄＿FILESで送られてきたキーがあった場合は値を取得する
 */
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

/**
 * セッションがあった場合は取得する
 * セッションはどのファイルにいても識別されて表示されるため、セッションを利用してメッセージを書く。
 */
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

/**
 * セッション変数に変数の値を保存する??
 */
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

//エラーの処理をセッションに格納
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

//
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

//エラーメッセージが0より多いときに結果を返す
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

/**
 * セッションに保存されているユーザーIDがあるのかの判別???
 */
function is_logined(){
  return get_session('user_id') !== '';
}

/**
 * 画像ファイルアップロード関数
 */
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

/**
 * 画像ファイルアップロード処理
 */
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


/**
 * 有効な文字数かを確認?
 * PHP_INT_MAXはphpがサポートする整数の最大値のこと
 * $lengthで文字数を取得して、引数である最大、最小文字数と比べる関数
 */
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

/**
 * 正規表現パターンを表す
 * alphanumeric ->（英数字）
 * REGEXP_ALPHANUMERICは定数正規表現を表す
 */
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

/**
 * 正規表現の1を返す（TRUE)
 */
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

/**
 * 画像ファイルアップロード関数
 */
function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}


