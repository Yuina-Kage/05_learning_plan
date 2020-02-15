<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb(); 

$sql = "select * from plans where status = 'notyet'";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$notyet_plans = $stmt->fetchALL(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  
  $errors = [];


  if ($title == '') {
    $errors['title'] = '学習内容と期限を記入してください';
  }

  if (empty($errors)) {
    $sql = "insert into plans (title, due_date, created_at, updated_at) values (:title, now(), now())";
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->execute();

    header('Location: index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>学習管理アプリ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>学習管理アプリ</h1>
  
    <label for="">学習内容：</label> 
    <input id="" type="text" name="gakusyuunaiyou">
    <form action="" method="post">
      <label for="">期限日：</label> 
      <input type="text" name="title" value="年/月/日">
      <input type="submit" value="追加">
    </form>
  

  <h2>未達成</h2>
  <ul>
    <?php foreach ($notyet_plans as $plan) : ?>
    <li>
      <?php echo h($plan['title']); ?>
    </li>
    <?php endforeach; ?>
  </ul>

  <hr>

  <h2>達成済み</h2>

  
</body>
</html>