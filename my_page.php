<?php
session_start();
include('functions.php');
check_session_id();
$pdo = connect_to_db();

$user_id = $_SESSION['id'];
$sql = 'SELECT * FROM todo_table WHERE is_deleted=0 ORDER BY deadline ASC';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
echo 'マイページ';
$username = $_SESSION['username'];
$id = $_GET['from_id'];
$sql = 'SELECT * FROM users_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();
if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
}
$output .= "{$record["username"]}さんから友達リクエストが届いています。";
?>

<!DOCTYPE html>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>こんにちは <?= $_SESSION['username'] ?>さん</p>

</body>

</html>