<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>

<!--商品一覧ページのview-->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <div class="sort">
      <form method="get" action="index_sort.php" style="text-align: right;">
        <select name="sort">
          <option value="new_item" <?php if ($sort === 'new_item'){print 'selected';}?>>新着順</option>
          <option value="price_is_low" <?php if ($sort === 'price_is_low'){print 'selected';}?> >価格の安い順</option>
          <option value="price_is_high" <?php if ($sort === 'price_is_high'){print 'selected';}?> >価格の高い順</option>
        </select>
        <input type="submit" value="並び替え検索">
      </form>
    </div>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <!--php処理で商品一覧を表示-->
      <?php foreach($new_item as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print h(($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print h((IMAGE_PATH . $item['image'])); ?>">
              <figcaption>
                <?php print h((number_format($item['price']))); ?>円
                <!--下の処理で在庫が０の場合はエラーを表示させる処理をしている-->
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php print $token; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  
</body>
</html>