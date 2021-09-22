<?php

// var_dump($_POST);
// exit();


// POSTデータ確認

//データが送られてきているかチェックする記述
//SQLのtodo と deadlineが必須項目なのでそこが送られてきているか？空じゃないか？をチェックしている
if ( //!isset→データがない ||または =='' 空文字 をそれぞれtodo deadlineで記述する
    !isset($_POST['todo']) || $_POST['todo']=='' ||
    !isset($_POST['reason']) || $_POST['reason']=='' ||
    !isset($_POST['deadline']) || $_POST['deadline']==''
) {
    //チェックの結果NGだったらエラーメッセージを出す
    exit('ParamError');
}
// 上記でNGじゃなかったらデータを受け取る
// データの受け取り
$todo = $_POST['todo'];
$reason = $_POST['reason'];
$deadline = $_POST['deadline'];

// DB接続
// 各種項目設定
// dbname：DBの名前
// port：接続ポート
// host：DBのホスト名
// username：DB接続時のユーザ名
// password：DB接続時のパスワード
//今回はXAMPPなので規定値があるのでそれを設定
//gsacs_d03_05はSQLで設定した任意のDB名

$dbn ='mysql:dbname=gsacs_d03_05;charset=utf8;port=3306;host=localhost';
$user = 'root'; //XAMPP規定値
$pwd = '';      //XAMPP規定値

// DB接続
//DBに接続するコードは決まった形式（PDO）

try {
$pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
echo json_encode(["db error" => "{$e->getMessage()}"]);
exit();
}

// 「dbError:...」が表示されたらdb接続でエラーが発生していることがわかる．

// SQL作成&実行                                                                             :todo（バインド変数）
$sql = 'INSERT INTO  product_todo_table (id, todo, reason, deadline, created_at, updated_at) VALUES (NULL, :todo, :reason, :deadline, now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);

// SQL実行（実行に失敗すると$statusにfalseが返ってくる）
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    exit('sqlError:'.$error[2]);
} else {
    header('Location:todo_input.php');
}

?>

<?php
require_once('functions.php');

$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // 画像を取得

} else {
    // 画像を保存
    if (!empty($_FILES['image']['name'])) {
        $name = $_FILES['image']['name'];
        $type = $_FILES['image']['type'];
        $content = file_get_contents($_FILES['image']['tmp_name']);
        $size = $_FILES['image']['size'];

        $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at)
                VALUES (:image_name, :image_type, :image_content, :image_size, now())';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('Location:todo_input.php');
    exit();
}
?>