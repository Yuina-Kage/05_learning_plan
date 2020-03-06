<?php

require_once('config.php');
require_once('functions.php');

$errors = array();

$dbh = connectDb(); 

$sql = "select * from  plans where  status = 'notyet'";
$sql = "select * from plans order by updated_at desc";

$stmt = $dbh->prepare($sql);
$stmt->execute();
$notyet_plans = $stmt->fetchALL(PDO::FETCH_ASSOC);


$sql2 = "select * from plans where status = 'done'";
$sql2 = "select * from plans order by updated_at asc";

$stmt = $dbh->prepare($sql2);
$stmt->execute();
$done_plans = $stmt->fetchALL(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $title = $_POST['title'];
  $due_date = $_POST['due_date'];
  
  $errors = [];
  
  if ($title == '') {
    $errors['title'] = 'タスク名を入力してください';
  }

  if ($due_date == '') {
    $errors['due_date'] = '期限を入力してください';
  }

  if (empty($errors)) {
    $sql = "insert into plans (title, due_date, created_at, updated_at) values (:title, :due_date, now(), now())";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title",  $title,);
    $stmt->bindParam(":due_date", $due_date);
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
  <link href="https://fonts.googleapis.com/css?family=Work+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>学習管理アプリ</h1>
  <form action="" method="post">
    <label for="title">学習内容:
      <input type="text" name="title" value="<?php echo h($title); ?>">
    </label> 
    <br>
    <label for="due_date">期限日: 
      <input type="date" name="due_date" value="<?php echo h($due_date); ?>">
      <input type="submit" value="追加">
    </label>
      <?php if (count($errors) > 0) : ?>
        <ul class="error-list">
          <?php foreach ($errors as $key => $value) : ?>
            <li><?php echo h($value); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
  </form>
  <h2>未達成</h2>
    <ul>
      <?php foreach ($notyet_plans as $plan) : ?>
        <?php if (date('Y-m-d') >= $plan['due_date']) : ?>
          <li class="expired">
        <?php else : ?>
          <li>
        <?php endif; ?>
          <a href="done.php?id=<?php echo h($plan['id']); ?>">[完了]</a>
          <a href="edit.php?id=<?php echo h($plan['id']); ?>">[編集]</a>
          <?php echo h($plan['title']); ?>・・・完了期限:
          <?php echo h(date('Y/m/d', strtotime($plan['due_date']))); ?>
      <?php endforeach; ?>
    </ul>
  <hr>
  <h2>達成済み</h2>
    <ul>
      <?php foreach ($done_plans as $plan) : ?>
        <li>
      <?php echo h($plan['title']); ?>
        </li>
      <?php endforeach; ?>
    </ul>
</body>
</html>