<?php

require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ユーザーごとの購入履歴テーブルの情報を取得
function user_history($db, $user_id){
    $sql = "
    SELECT 
        buy_history.order_number,
        buy_history.buy_update_time,
        SUM(buy_details.price * buy_details.amount) AS total
    FROM 
        buy_history
    JOIN
        buy_details
    ON
        buy_history.order_number = buy_details.order_number
    WHERE
        user_id = ?
    GROUP BY
        order_number
    ORDER BY
        buy_update_time DESC
    ";
    return fetch_all_query($db, $sql,[$user_id]);
}

//全ユーザーの購入履歴を取得
function admin_history($db) {
    $sql = "
    SELECT
        buy_history.order_number,
        buy_history.buy_update_time,
        SUM(buy_details.price * buy_details.amount) AS total
    FROM
        buy_history
    JOIN
        buy_details
    ON
        buy_history.order_number = buy_details.order_number
    GROUP BY
        order_number
    ORDER BY
        buy_update_time DESC
    ";
    return fetch_all_query($db, $sql);
    
}

