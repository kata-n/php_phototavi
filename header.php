 <header>
  <div class="header" id="<?php echo (empty($_SESSION['user_id'])) ? 'headercolor' : ''; ?>">
    <h2 class="heading"><a href="<?php echo (empty($_SESSION['user_id'])) ? 'index.php' : 'login.php'; ?>">Phototavi</a></h2>
    <ul class="nav">
      <?php if(empty($_SESSION['user_id'])){ ?>
      <li class="nav__list"><a href="promotion.php" class="signup">Photo taviとは</a></li>
      <?php }else{ ?>
      <li class="nav__list nav__login"><a href="<?php echo ($_SESSION['user_id'] == 2) ? 'promotion.php' : 'mypage.php'; ?>"><?php echo (!empty($_SESSION['user_name']) && ($_SESSION['user_id'] == 2)) ? 'Phototavi' : $_SESSION['user_name'];?></a></li>
      <li class="nav__list nav__login"><a href="logout.php" class="logout">ログアウト</a></li>
      <?php } ?>
      </ul>
  </div>
</header>
