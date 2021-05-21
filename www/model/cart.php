<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

/**
 * カートの中身を全て取得する関数
 * 引数でdbハンドル、ユーザーIDを取得する。
 * returnで$sqlを返すことで結果を配列に格納できる。
 */
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql,[$user_id]);
}

/**
 * add_cartでユーザーのカートに商品が入っているかを確かめる
 * user_idとitem_idでアイテムの情報を抽出している
 */
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db,$sql,[$user_id,$item_id]);

}

/**
 * if文のget_user_cart関数でユーザーがカートに商品を追加を押したらその情報をデータベースに追加する
 * カートに商品が追加されている場合は、update_cart_amount関数で商品の個数を変更する処理が実行される
 */
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}


/**
 * ユーザーのカートの情報を追加している
 * amount = 1 とはユーザーが商品をカートに追加をクリックしたときに商品が自動的に1つ追加されるようにするため
 */
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql,[$item_id,$user_id,$amount=1]);
}

/**
 * cartの指定した商品の購入数を変更する
 * 引数で抽出条件であるcart_id,変更したい値(SET)で指定しているamountを使用する
 */
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql,[$amount,$cart_id]);
}

/**
 * cartの中身を削除する関数
 */
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql,[$cart_id]);
}

/**
 * カートに入っている商品の購入処理
 * 処理がTRUEだった場合はカートの中身を削除
 * 購入履歴情報明細情報を追加　INSERT文のタイミング
 */
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //商品を購入後商品購入履歴、詳細データを追加
  $db->beginTransaction();
  try {
    
    insert_buy_history($db,$carts[0]['user_id']);
    $order_number = $db->lastInsertID();
  
  foreach($carts as $cart){

    //購入する商品が1つの商品のみとは限らないためforeach文の中に記述
    insert_buy_detail($db, $order_number, $cart['item_id'], $cart['price'], $cart['amount']);

    if(update_item_stock(
      $db, 
      $cart['item_id'], 
      $cart['stock'] - $cart['amount']
      ) === false){
        set_error($cart['name'] . 'の購入に失敗しました。');
        }
      }

    delete_user_carts($db, $carts[0]['user_id']);

    $db->commit();
  }catch (PDOException $e){
    $db->rollback();
  } 
}

/**
 * ユーザーが購入をクリックした場合商品をカートから削除する
 */
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  return execute_query($db, $sql,[$user_id]);
}

/**
 * カートに入っている商品の合計金額を計算し結果を返す関数
 * 引数は商品のカート情報
 */
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

/**
 * カートの購入を検証する関数
 * 1,if文でカートに入っている商品の個数が０個以下のときはfalseを返す
 * 2,is_open関数で商品ステータスが1の時結果を返すfalseの場合はエラー文
 * 3,stock(在庫)が購入数より少なかったらエラー文
 * 4,has_error関数でエラーがあった場合はfalse
 * 5,それ以外はTRUEを返す
 */
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

//購入履歴情報の追加テーブル
function insert_buy_history($db, $user_id) {
    $sql = "
    INSERT INTO
      buy_history(
        user_id
      )
    VALUES(?)
  ";

  return execute_query($db, $sql,[$user_id]);
}

//購入明細情報の追加テーブル
function insert_buy_detail($db, $order_number, $item_id, $price, $amount) {
  $sql = "
    INSERT INTO
      buy_details(
        order_number,
        item_id,
        price,
        amount
        )
      VALUES(?,?,?,?)
      ";
      return execute_query($db, $sql, [$order_number, $item_id, $price, $amount]);
}