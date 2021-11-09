<?php

// var_dump($_SESSION);
// exit();

session_start();
include('functions.php');
check_session_id();
$pdo = connect_to_db();

$user_id = $_SESSION['id'];

// $sql = 'SELECT * FROM todo_table WHERE is_deleted=0 ORDER BY deadline ASC';
$sql = "SELECT* FROM todo_table LEFT OUTER JOIN(SELECT todo_id,COUNT(id)
AS cnt FROM help_table GROUP BY todo_id) AS likes On todo_table.id=likes.todo_id";

$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示 
    exit('sqlError:' . $error[2]);
} else {
    // 登録ページへ移動
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($result[0]['id']);
    // exit();
    $output = '';
    for ($i = 0; $i < count($result); $i++) {
        $todo_id = $result[$i]['id'];
        // var_dump($todo_id);
        // exit();
        $sql = 'SELECT * FROM help_table WHERE todo_id = :todo_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_INT);
        $status = $stmt->execute();
        if ($status == false) {
            $error = $stmt->errorInfo();
            exit('sqlError:' . $error[2]);
        } else {
            $result_name = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result_name);
            // exit();
        }

        $output .= "<tr>
            <td>{$result[$i]['deadline']}</td>
            <td>{$result[$i]['todo_name']}</td>
            <td>{$result[$i]['todo_kana']}</td>
            <td>{$result[$i]['age']}</td>
            <td>{$result[$i]['company']}</td>
            <td>{$result[$i]['hope']}</td>
            <td>{$result[$i]['hope2']}</td>
            <td>{$result[$i]['hope3']}</td>
            <td>{$result[$i]['username']}</td></tr>";
        $output .= "<td><a href='help_create.php?user_id={$user_id}&todo_id={$result[$i]["id"]}&username={$_SESSION['username']}'>協力するよ{$result[$i]["cnt"]}</a></td>";

        for ($s = 0; $s < count($result_name); $s++) {
            $output .= "<td>{$result_name[$s]['username']}</td>";
        }
    }
}













// $sql = "SELECT*FROM help_table WHERE todo_id=:todo_id";
// $stmt->bindValue(':todo_id', $result['id'], PDO::PARAM_STR);
// $stmt = $pdo->prepare($sql);
// $status = $stmt->execute();
// if ($status == false) {
//     $error = $stmt->errorInfo();
//     exit('sqlError:' . $error[2]);
// } else {
//     $result_name = $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// 
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>公開ページ</title>
</head>

<body>
    <fieldset>
        <legend>公開ページ</legend>
        <p>こんにちは <?= $_SESSION['username'] ?>さん</p>
        <a href="todo_txt_input.php">入力画面</a>
        <a href="todo_logout.php">ログアウト</a>
        <a href="my_page.php?user">マイページ</a>

        <table>
            <thead>
                <tr>
                    <th>日時</th>
                    <th>名前</th>
                    <th>カナ</th>
                    <th>年代</th>
                    <th>所属</th>
                    <th>やりたいこと</th>
                </tr>
            </thead>
            <tbody>
                <?= $output ?>
            </tbody>
        </table>
    </fieldset>
</body>

</html>