<?php
// var_dump($_GET);
// exit();

session_start();
include("functions.php");
check_session_id();

// 送信されたidをgetで受け取る
$id = $_GET['id'];

// DB接続&id名でテーブルから検索
// include('functions.php'); これが被ってて邪魔してた！
$pdo = connect_to_db();

$sql = 'SELECT * FROM cattle_memo WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();
// $img = $stmt->fetchObject();

if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
}
  // var_dump($record);
  // exit();

//誕生日から月齢を算出
//誕生日の年月日をそれぞれ取得
$birth_year = (int)date("Y", strtotime($record["birthday"]));
$birth_month = (int)date("m", strtotime($record["birthday"]));
$birth_day = (int)date("d", strtotime($record["birthday"]));
//現在の年月日を取得
$now_date = (int)date("Ymd");
$now_year = (int)date("Y");
$now_month = (int)date("m");
$now_day = (int)date("d");
//月齢を計算
$age = ($now_year - $birth_year)*12 + ($now_month - $birth_month);
//「日」で月齢を微調整
if($now_day < $birth_day) {
  $age--;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <!-- 一旦、グラフを外してみた -->
  <!-- <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script> -->
  <link rel="stylesheet" href="../css/weight_input.css">
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

  <div class="cattle_info">
    <div class="image" id="<?= "{$record['id']}" ?>">
      <img src="<?= "image.php?id={$record['id']}" ?>" width="auto" height="200px">
    </div>
    <div class="cattle_name"><?= $record["cattle_name"] ?> / <?= $record["gender"] ?></div>
    <div class="age"><?= $record["birthday"] ?> 生まれ （<?= $age ?>ヶ月）</div>
  </div>

  <form action="weight_create.php" method="POST">
    <fieldset>
      <legend>今日の体重を入力</legend>
      <div>
        <input id="current_weight" type="text" name="current_weight" value=""> kg
      </div>
      <!-- idを見えないように送る -->
      <!-- input type="hidden"を使用する! -->
      <!-- form内に以下を追加 -->
      <div>
        <input id="cattle_id" type="hidden" name="cattle_id" value="<?= $record['id']?>">
        <input id="age" type="hidden" name="cattle_age" value="<?= $age ?>">
        <input id="updated_date" type="hidden" name="updated_date" value="<?= $now_date ?>">
      </div>
      <div class="button">
        <button class="submit_btn">送　信</button>
      </div>
    </fieldset>
  </form>

  <!-- ハンバーガーメニューに関するjs -->
  <script type="text/javascript" src="../js/hamburger.js"></script>

</body>
</html>