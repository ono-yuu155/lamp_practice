<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入履歴</title>
</head>
<body>
    <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <h1>購入履歴</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table class="table table-bordered">
        <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
        </tr>
        <?php foreach($user_history as $value){ ?>
        <tr>
            <td><?php print h(($value['order_number'])); ?></td>
            <td><?php print h(($value['buy_update_time'])); ?></td>
            <td><?php print h(($value['total'])); ?></td>
            <td>
                <form method="post" action="details.php">
                    <input type="submit" value="購入明細を見る">
                    <input type="hidden" name="order_number" value="<?php print h(($value['order_number'])); ?>">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>