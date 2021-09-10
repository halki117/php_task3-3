<?php

// DBへの接続処理を関数化
function dbConnection(){

  $dsn = 'mysql:dbname=php_task3;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
          // SQL実行失敗時に例外をスロー
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          // デフォルトフェッチモードを連想配列形式に設定
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
          // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
          PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      );

  // PDOオブジェクト生成（DBへ接続）してretun文で返す
  return  new PDO($dsn, $user, $password, $options);

}


  // リンクのパラメータから送られてきた値を取得
  $edit_id = $_GET['id'];

// ----------- 以下、データ取得処理 -----------
  //DBへの接続準備
  $dbh = dbConnection();

  //SQL文（クエリー作成）
  $stmt = $dbh->prepare( 'SELECT * FROM posts WHERE id = :id ');
  
  $stmt->bindParam( ":id", $edit_id);
  
  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute();

  $post = $stmt->fetch();
  
  //レコードの件数を取得,下記の投稿一覧部分のif文の判定式にで使用する
  $count = $stmt->rowCount();


// ----------- 以下、データ更新処理 -----------
  if($_POST['submit_update']){

    $name = $_POST['name'];
    $content = $_POST['content']; 

    //DBへの接続準備
    $dbh = dbConnection();

    //SQL文（クエリー作成）
    $stmt = $dbh->prepare( 'UPDATE posts SET name = :name, content = :content WHERE id = :id');

    //プレースホルダに値をセットし、SQL文を実行
    $stmt->execute(array(':name' => $_POST['name'], ':content' => $_POST['content'], ':id' => $edit_id));

    header("Location:php3-3updated.php");

  }
  

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>php3-3edit</title>
</head>
<body>
  
<h2>新規投稿</h2>

<form  method="post">
  name: <input type="text" name="name" value="<?php echo $post['name']?>">
  <p>投稿内容</p><textarea name="content" id="" cols="30" rows="10"><?php echo $post['content']?></textarea>
  <p>
    <input type="submit" name="submit_update" value="更新">
    <a href="php3-3.php"><button  class="back" name="back" >戻る</a>
  </p>
</form>

</body>
</html>
