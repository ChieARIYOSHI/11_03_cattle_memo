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

$sql = 'SELECT * FROM cattle_weight WHERE cattle_id=:cattle_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cattle_id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  $records = $stmt->fetchAll();
}

//該当する牛の体重データが存在しない場合
if ($records == NULL) {
  echo "<p>登録データがありません．</p>";
  echo '<a href="read.php">牛さんリストに戻る</a>';
  exit();
}

// var_dump($records);
// exit();

//送信されたidだけのデータを抽出
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
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

//誕生日から月齢を算出
//誕生日の年月日をそれぞれ取得
$birth_year = (int)date("Y", strtotime($result["birthday"]));
$birth_month = (int)date("m", strtotime($result["birthday"]));
$birth_day = (int)date("d", strtotime($result["birthday"]));
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

// 結局テーブルの結合は使いませんでした。
// 参考までに試しに作ってみた結合テーブルのsqlを記録しておきます。
// $sql = 'SELECT * FROM cattle_memo LEFT OUTER JOIN (SELECT cattle_id, COUNT(id) AS cnt FROM cattle_weight GROUP BY cattle_id) AS information ON cattle_memo.id = information.cattle_id';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <link rel="stylesheet" href="../css/weight_grf.css">
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
    <div class="image" id="<?= "{$result['id']}" ?>">
      <img src="<?= "image.php?id={$result['id']}" ?>" width="auto" height="200px">
    </div>
    <div class="cattle_name"><?= $result["cattle_name"] ?> / <?= $result["gender"] ?></div>
    <div class="age"><?= $result["birthday"] ?> 生まれ （<?= $age ?>ヶ月）</div>
  </div>

  <div class="ct-chart"></div>

  <div class="button">
    <a class="submit_btn" href="read.php">牛さんリストに戻る</a>
  </div>

  <!-- ここからjs.体重の推移をグラフで表示させる -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    $(function () {
      const data = {
        // labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        labels: [<?php foreach ($records as $value): ?>
          <?php echo $value["cattle_age"] ?>, 
          <?php endforeach; ?>],
        series: [
          [<?php foreach ($records as $value): ?>
          <?php echo $value["current_weight"] ?>, 
          <?php endforeach; ?>],
          // [15, 25, 35, 45, 55, 60, 65, 70, 75, 80],
          // [15, 22, 29, 36, 43, 50, 57, 65, 72, 79],
          // [2, 1, 3, 4, 2, 5]
        ]
      };

      // console.log(data);

      const options = {
        height: 300
      };
      new Chartist.Line('.ct-chart', data, options);

    });
  
  </script>

  <!-- ハンバーガーメニューに関するjs -->
  <script type="text/javascript" src="../js/hamburger.js"></script>

</body>
</html>