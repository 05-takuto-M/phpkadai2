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

// require_once('functions.php');

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}


// SQL作成&実行
// データ作成処理と同様にSQLを記述して実行する．今回は「ユーザが入力したデータ」を使用しないのでバインド変数は不要．

// また，$statusには実行結果が入るが，この時点ではまだデータ自体の取得はできていない点に注意．

$sql = 'SELECT * FROM product_todo_table';
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
  $output1 = "";
  foreach ($result as $record) {
    $output1 .= "
      <tr>
        <td>{$record["deadline"]}</td>
        <td>{$record["todo"]}</td>
        <td>{$record["reason"]}</td>
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
  <!-- <link rel="stylesheet" href="css/reset.css"> -->
  <link rel="stylesheet" href="css/style.css">
  <title>MyProduct_todoリスト（入力画面）</title>
</head>

<body>
  <div>
  <form action="todo_create.php" method="POST">
    <fieldset>
      <legend>MyProduct_todoリスト（入力画面）</legend>
      <div>
        todo: <input type="text" name="todo">
      </div>
      <div>
        reason: <input type="text" name="reason">
      </div>
      <div>
        deadline: <input type="date" name="deadline">
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form>
  </div>
  <div>
  <fieldset>
  <legend>MyProduct_todoリスト（一覧）</legend>
    <table>
      <thead>
        <tr>
          <th>deadline</th>
          <th>todo</th>
          <th>reason</th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
      <tr>
        <div class="result">
        <td><?= $output1 ?></td>
        </div>
      </tr>
      </tbody>
    </table>
  </fieldset>
  </div>




</body>

</html>