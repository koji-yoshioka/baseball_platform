<?php
ini_set("log_errors", "on");
ini_set("error_log", "/log/error.log");
ini_set("date.timezone", "Asia/Tokyo");

// TODO:validation




?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>BB PLATFORM</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <header>Hello login page</header>
  <main class="login-content">
    <h2 class="title">ログイン</h2>
    <form class="login-form" method="POST" action="">
      <input type="text" name="login_id" placeholder="メールアドレス">
      <input type="password" name="password" placeholder="パスワード">
      <button type="submit" class="login-button">ログイン</button>
      <a href="registerMember.php">新規会員登録</a>
    </form>
  </main>

</body>

</html>
