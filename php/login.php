<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login.css">
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="../css/navigation.css">
  <link rel="icon" href="../img/icon.ico">
  <title>牛さんメモ</title>
</head>
<body>
    <!-- 言語変換ナビゲーションの表示 -->
  <header_>
  <nav class="lang_nav">
    <a class="nav_ja" href="#" id="ja" glot-model="jp">日本語</a>
    <a class="nav_cw" href="#" id="cw" glot-model="us">チェワ語</a>
  </nav>
  </header>
  
  <div class="top_main">
    <img class="icon" src="../img/icon.png" alt="牛さんのアイコン">
    <h2 glot-model="title">牛さんメモ</h2>
    <h3 glot-model="sub-title">ログインページ</h3>
    <form class="login_main" action="login_act.php" method="POST">
      <fieldset>
        <ul class="index">
          <li>ユーザー名: <input type="text" name="username"></li>
          <li>パスワード: <input type="password" name="password"></li>
          <button class="login_btn" glot-model="login_btn">ログイン</button>
          <p>・・・</p>
          <div class="register_btn"><a href="register.php" glot-model="register_btn">新規登録</a></div>
          <p glot-model="text">※初めてご利用の方は新規登録から</p>
        </ul>
      </fieldset>
    </form>
  </div>

  <!-- 言語変換ナビゲーションに関するjs -->
  <!-- <script type="text/javascript" src="../js/navigation.js"></script> -->
  <script src="https://unpkg.com/glottologist"></script>
  <script>
    const glot = new Glottologist();

    glot.assign("title", {
      ja: "牛さんメモ",
      cw: "N'gombe buku"
    })

    glot.assign("sub-title", {
      ja: "ログインページ",
      cw: "Kulowa Kwabuku"
    })

    glot.assign("user_name", {
      ja: "ユーザー名:",
      cw: "Dzina Lanu:"
    })

    glot.assign("password", {
      ja: "パスワード:",
      cw: "Achinsinsi:"
    })

    glot.assign("login_btn", {
      ja: "ログイン",
      cw: "Kulowa"
    })

    glot.assign("register_btn", {
      ja: "新規登録",
      cw: "Kulembetsa Tsopano"
    })

    glot.assign("text", {
      ja: "※初めてご利用の方は新規登録から",
      cw: "*Ngati mukugwiritsa ntchito kwa nthawi yoyamba, chonde lembetsani kwatsopano."
    })

    glot.render();

    /**
    **言語切り替え用のイベント処理
    **/
    const ja = document.getElementById('ja');
    const cw = document.getElementById('cw');

    ja.addEventListener('click', e => {
      e.preventDefault();
      glot.render('ja');
    })

    cw.addEventListener('click', e => {
      e.preventDefault()
      glot.render('cw');
    })
  </script>
</body>
</html>