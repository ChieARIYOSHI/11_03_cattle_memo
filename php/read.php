<?php

session_start();
include('functions.php');
check_session_id();

// var_dump($_SESSION); //$_SESSIONに何が入っているか確認
// exit();

$user_id = $_SESSION['session_id']; // ユーザid取得

$pdo = connect_to_db(); // DB接続

$sql = 'SELECT * FROM cattle_memo';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
// $img = $stmt->fetchObject();

if ($status==false) {
  $error = $stmt->errorInfo(); 
  exit('sqlError:'.$error[2]); // 失敗時􏰁エラー出力
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $output = "";
  foreach ($result as $record) {
    //誕生日から月齢を算出
    //誕生日の年月日をそれぞれ取得
    $birth_year = (int)date("Y", strtotime($record["birthday"]));
    $birth_month = (int)date("m", strtotime($record["birthday"]));
    $birth_day = (int)date("d", strtotime($record["birthday"]));
    //現在の年月日を取得
    $now_year = (int)date("Y");
    $now_month = (int)date("m");
    $now_day = (int)date("d");
    //月齢を計算
    $age = ($now_year - $birth_year)*12 + ($now_month - $birth_month);
    //「日」で月齢を微調整
    if($now_day < $birth_day) {
      $age--;
    }

    // var_dump($age);
    // exit();

    $output .= "<a class='cattle_info' href='weight_input.php?id={$record["id"]}'>
    <div class='image' id='{$record['id']}'>
    <img src='image.php?id={$record['id']}' width='auto' height='100px'>
    </div>
    <div class='text'>
    <li>名前　　{$record["cattle_name"]}</li><li>月齢　　{$age} ヶ月</li><li>性別　　{$record["gender"]}</li><li>特長　　{$record["feacher"]}</li><br>
    </div>
    </a>
    <div>
    <a class='grf_btn' href='weight_grf.php?id={$record["id"]}'
    style='width: 120px;
    height: 24px;
    border-radius: 30px;
    color: #f6ecf7;
    font-weight: bolder;
    text-decoration: none;
    background-color: #FF6ED8;
    border-style: none;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    margin-left: 10px;'>グラフを見る</a>
    </div>";
    // <li>誕生日　{$record["birthday"]}</li>
  } 
  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  // unset($value);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/read.css">
  <link rel="stylesheet" href="../css/hamburger.css">
  <link rel="icon" href="../img/icon.ico">
  <title>牛さんメモ</title>
</head>

<body>
  <header>
    <!-- ハンバーガーメニューの表記内容です -->
    <nav class="navi_menu">
      <ul class="menu_items">
        <li class="menu_content"><a href="index.php">トップページ</a></li>
        <li class="menu_content"><a href="input.php">新しい牛さんの登録</a></li>
        <li class="menu_content"><a href="read.php">牛さんリスト一覧</a></li>
        <li class="menu_content"><a href="">マップ</a></li>
        <li class="menu_content"><a href="logout.php">ログアウト</a></li>
      </ul>
      </nav>
      <!-- アイコン --> 
      <div class="navi_icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
  </header>

  <main class="list_main">
    <div class="title">
      <img class="icon" src="../img/icon.png" alt="牛さんのアイコン">
      <h1>牛さんリスト</h1>
    </div>
  
    <!-- <fieldset> -->
      <ul id="output">
        <?= $output ?>
      </ul>
    <!-- </fieldset> -->
  
    <a class="submit_btn" href="input.php">新しい牛さんを登録</a>
  </main>

  <!-- ハンバーガーメニューに関するjs -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/hamburger.js"></script>
</body>

</html>