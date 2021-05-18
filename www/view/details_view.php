<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入明細</title>
</head>
<body>
    <h1>購入明細</h1>

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
        </tr>
        <?php } ?>
    </table>

    <table class="table table-bordered">
        <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数<th>
            <th>小計</th>
        </tr>
        <?php foreach($user_details as $date){ ?>
        <tr>
            <td><?php print h(($date['name'])); ?></td>
            <td><?php print h(($date['price'])); ?></td>
            <td><?php print h(($date['amount'])); ?></td>
            <td><?php print h(($date['details_total'])); ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>