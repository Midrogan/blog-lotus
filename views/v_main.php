<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=1200px">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <link rel="stylesheet" type="text/css" href="/media/css/style.css">
  <!-- <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet"> -->


  <?= $script ?>

  <link rel="shortcut icon" href="/media/img/logo_white.png" type="image/png">
  <title><?= $title ?></title>
</head>

<body>
  <div class="page">
    <header>
      <div class="container_header_footer">
        <a href="<?= ROOT ?>"><img class="logo" src="/media/img/logo_white.png" alt=""></a>
        <div class="header_auth">
          <?= $signout ?>
        </div>
      </div>
    </header>

    <div class="container">
      <?= $content ?>
    </div>

    <footer>
      <div class="container_header_footer">
        <a href="<?= ROOT ?>"><img class="logo" src="/media/img/logo_black.png" alt=""></a>
        <div class="footer_social">
          <a href="<?= ROOT ?>"><img class="footer_social_img" src="/media/img/vk.png" alt=""></a>
          <a href="<?= ROOT ?>"><img class="footer_social_img" src="/media/img/facebook.png" alt=""></a>
          <a href="<?= ROOT ?>"><img class="footer_social_img" src="/media/img/zen.png" alt=""></a>
          <a href="<?= ROOT ?>"><img class="footer_social_img" src="/media/img/telegram.png" alt=""></a>
          <a href="<?= ROOT ?>"><img class="footer_social_img" src="/media/img/twitter.png" alt=""></a>
        </div>
      </div>
    </footer>
  </div>
</body>

</html>