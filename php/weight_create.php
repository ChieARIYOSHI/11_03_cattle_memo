<?php
// var_dump($_POST);
// exit();

//もし何も入力できてなかったら弾く
if (
  !isset($_POST['current_weight']) || $_POST['current_weight']=='') {
    exit('ParamError'); 
  }

session_start();
include("functions.php");
check_session_id();

// 受け取ったデータを変数に入れる
$cattle_id = $_POST['cattle_id'];
$cattle_age = $_POST['cattle_age'];
$current_weight = $_POST['current_weight'];
// $updated_date = $_POST['updated_date'];

// var_dump($cattle_id);
// exit();

// DB接続
$pdo = connect_to_db();

// if文で分岐
// ①もし牛idと牛ageが一致したら〜update
// ②それ以外なら〜create

// 牛idと牛ageの確認
$sql = 'SELECT * FROM cattle_weight WHERE cattle_id=:cattle_id && cattle_age=:cattle_age';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cattle_id', $cattle_id, PDO::PARAM_INT);
$stmt->bindValue(':cattle_age', $cattle_age, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  // 牛idと牛ageが一致した場合は体重のデータをアップデートする
  // UPDATE文を作成&実行
  $sql = "UPDATE cattle_weight SET current_weight=:current_weight, updated_date=sysdate() WHERE cattle_id=:cattle_id && cattle_age=:cattle_age";

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':current_weight', $current_weight, PDO::PARAM_INT);
  $stmt->bindValue(':cattle_id', $cattle_id, PDO::PARAM_INT);
  $stmt->bindValue(':cattle_age', $cattle_age, PDO::PARAM_INT);
  $status = $stmt->execute();
  header('Location:read.php');
  exit();
} else {
  // データ登録SQL作成
  // `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
  $sql = 'INSERT INTO cattle_weight (id, cattle_id, cattle_age, current_weight, updated_date) VALUES(NULL, :cattle_id, :cattle_age, :current_weight, sysdate())';

  // SQL準備&実行
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':cattle_id', $cattle_id, PDO::PARAM_INT);
  $stmt->bindValue(':cattle_age', $cattle_age, PDO::PARAM_INT);
  $stmt->bindValue(':current_weight', $current_weight, PDO::PARAM_INT);
  
  $status = $stmt->execute(); //SQLの実行

  // データ登録処理後
  if ($status == false) {
    $error = $stmt->errorInfo();
    exit('sqlError:' . $error[2]);
  } else {
    header('Location:read.php');
    // header('Location:weight_create.php?id={$record["cattle_id"]}');
    exit();
  }
}

?>