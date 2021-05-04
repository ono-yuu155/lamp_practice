<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

/**
 * ユーザーIDで抽出してデータを取得する
 * リターンでsql文をfetchで情報を取得している
 */
function get_user($db, $user_id){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = ?
    LIMIT 1
  ";

  return fetch_query($db,$sql,[$user_id]);
}

/**
 * 抽出したユーザー名でユーザーの情報を取得する関数
 * リターンでsql文をfetchで情報を取得している
 */
function get_user_by_name($db, $name){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = ?
    LIMIT 1
  ";

  return fetch_query($db, $sql,[$name]);
}

/**
 * ユーザーの情報を取得
 * 1,その情報をif文で正しいかを判別して正しくない時falseを返す
 * 2,1でfalseではなくtrueだった場合そのユーザーIDをセッション変数に格納しユーザー情報を返す
 */
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    return false;
  }
  set_session('user_id', $user['user_id']);
  return $user;
}

/**
 * 引数でデータベースハンドルを設定する
 * セッションに保存されているユーザーidを変数に格納する
 * get_user関数でユーザーidを抽出する値に使う
 */
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

/**
 *ユーザーの新規登録情報(バリデーション)のfalseを返す関数
 */
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

/**
 * ユーザーのタイプが管理者(1)を返している
 */
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

/**
 * ユーザー新規登録のバリデーションが正しいとき変数に格納して、
 * ID、パスワードの正しいバリデーションを返している
 */
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

/**
 * 1,ユーザーIDの文字数が指定した文字数と異なった場合はエラーを返す
 * 2,バリデーションのエラーを返す
 */
function is_valid_user_name($name) {
  $is_valid = true;
  //ユーザーIDの文字数が指定した文字数と異なった場合はエラーを返す
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  //バリデーションが正しくない場合エラーを返す処理
  if(is_alphanumeric($name) === false){
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * 1,ユーザーパスワードの文字数が指定した文字数と異なった場合はエラーを返す
 * 2,バリデーションエラーを返す
 */
function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  //ユーザーパスワードの文字数が指定した文字数と異なった場合はエラーを返す
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  //バリデーションが正しくない場合エラーを返す処理
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

/**
 * sqlインジェクションの対策のため?で値をバインド
 */
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES (? , ?);
  ";
  return execute_query($db, $sql,[$name,$password]);
}

