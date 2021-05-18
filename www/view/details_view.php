<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入明細</title>
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <h1>購入明細</h1>

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>注文番号</th>
                <th>購入日時</th>
                <th>合計金額</th>
            </tr>
        </thead>
        <?php foreach($user_history_details as $value){ ?>
        <?php $sum = $value['price'] * $value['amount'] ?>
        <tr>
            <td><?php print h(($value['order_number'])); ?></td>
            <td><?php print h(($value['buy_update_time'])); ?></td>
            <td><?php print h(($sum)); ?></td>
        </tr>
        <?php } ?>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($user_details as $date){ ?>
        <tr>
            <td><?php print h(($date['name'])); ?></td>
            <td><?php print h(($date['price'])); ?></td>
            <td><?php print h(($date['amount'])); ?></td>
            <td><?php print h(($date['details_total'])); ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>