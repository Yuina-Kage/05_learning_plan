<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();

$sql = "select * from plans where id = :id";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$plan = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title = $_POST['title'];
  $due_date = $_POST['due_date'];

  $errors = [];

  if ($title == '') {
    $errors['title'] = 'タスク名が変更されていません';
  }

  if ($due_date == '') {
    $errors['due_date'] = '日付が変更されていません';
  }

  if (empty($errors)) {
    $sql = "update plans set title = :title, :due_date, updated_at = now() where id = :id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":due_date", $due_date);
    $stmt->bindParam(":id", $id);
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
  <title>編集画面</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>編集</h2>
<p>
<form action="" method="post">
  <label for="title">学習内容:
    <input type="title" name="title" value="<?php echo h($plan['title']); ?>">
  </label> 
  <label for="due_date">期限日: 
    <input type="date" name="date" value="<?php echo h($plan['due_date']); ?>">
    <input type="submit" value="編集">
  </label>
  <?php if ($errors) : ?>
    <ul class="error-list">
      <?php foreach ($errors as $error) : ?>
        <li>
          <?php echo h($error); ?>
        </li>
      <?php endforeach; ?> 
    </ul>
  <?php endif; ?>  
</form>
</p>
</body>
</html>



