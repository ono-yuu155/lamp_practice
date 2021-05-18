
-- 購入履歴テーブル
CREATE TABLE `buy_history` (
    `order_number` INT(11)　NOT NULL AUTO_INCREMENT,
    `user_id' INT`(11),
    `buy_update_time` datetime,
    primary key (`order_number`)
)

-- 購入詳細テーブル
CREATE TABLE `buy_details` (
    `order_number` INT(11),
    `item_id` INT(11),
    `price`   INT(11),
    `amount`  INT(11),
)