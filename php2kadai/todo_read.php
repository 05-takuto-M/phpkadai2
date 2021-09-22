<!-- 処理の流れ
表示ファイル（todo_read.php）へアクセス時，DB接続する．
データ参照用SQL作成→実行．
取得したデータをHTMLに埋め込んで画面を表示．
※必要に応じて，並び替えやフィルタリングを実施してみよう． -->

<?php
// 新規データ作成の場合と同様の処理
// DB接続
$dbn ='mysql:dbname=gsacs_d03_05;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}


// SQL作成&実行
// データ作成処理と同様にSQLを記述して実行する．今回は「ユーザが入力したデータ」を使用しないのでバインド変数は不要．

// また，$statusには実行結果が入るが，この時点ではまだデータ自体の取得はできていない点に注意．

$sql = 'SELECT * FROM  product_todo_table  ';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// SQL実行後の処理
// SQLの実行に失敗した場合はエラーを表示して処理を中止する．

// SQLが正常に実行された場合は以下の流れで処理が実行される．

// fetchAll()関数でデータ自体を取得する．
// 繰り返し処理を用いて，取得したデータからHTMLタグを生成する．
// （HTML内の任意の位置に作成したタグを設置

if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:'.$error[2]);
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($result);
  // exit();
  $output = "";
  foreach ($result as $record) {
    $output .= "
      <tr>
        <td>{$record["deadline"]}</td>
        <td>{$record["reason"]}</td>
        <td>{$record["todo"]}</td>
      </tr>
    ";
  }
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型todoリスト（一覧画面）</title>
</head>

<body>
  <fieldset>
    <legend>DB連携型todoリスト（一覧画面）</legend>
    <a href="todo_input.php">入力画面</a>
    <table>
      <thead>
        <tr>
          <th>deadline</th>
          <th>reason</th>
          <th>todo</th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>