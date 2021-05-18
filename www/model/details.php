<?php

require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ユーザーごとの購入明細の情報を取得
function user_details($db, $order_number) {
    $sql = "
    SELECT
        buy_details.price,
        buy_details.amount,
        SUM(buy_details.price * buy_details.amount) AS details_total,
        items.name
    FROM
        buy_details
    JOIN
        items
    ON
        buy_details.item_id = items.item_id
    WHERE
        order_number = ?
    GROUP BY
        buy_details.price, buy_details.amount, items.name;
    ";
        return fetch_all_query($db, $sql, [$order_number]);
}