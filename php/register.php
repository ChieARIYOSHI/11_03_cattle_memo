<!DOCTYPE html>
<html lang="ja">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/register.css">
  <link rel="icon" href="../img/icon.ico">
  <title>牛さんメモ</title>
</head>

<body>
  <div class="top_main">
    <img class="icon" src="../img/icon.png" alt="牛さんのアイコン">
    <h2>牛さんメモ</h2>
    <h3>新 規 登 録</h3>
  <form action="register_act.php" method="POST">
    <fieldset>
    <ul class="index">
          <li>ユーザー名: <input type="text" name="username"></li>
          <li>パスワード: <input type="password" name="password" minlength="5"></li>
          <button class="register_btn">送　信</button>
          <p>・・・</p>
          <div class="back_btn"><a href="login.php">ログインに戻る</a></div>
        </ul>
    </fieldset>
  </form>
</body>

</html>