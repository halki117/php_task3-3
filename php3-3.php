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


$name = $_POST['name'];

$content = $_POST['content'];


// ---------- 以下、データ投稿処理 ----------
if(!empty($_POST['submit_create'])){
  //DBへの接続準備
  $dbh = dbConnection();

  //SQL文（クエリー作成）
  $stmt = $dbh->prepare('INSERT INTO posts ( name, content) VALUES ( :name, :content )');

  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute(array(':name' => $name, ':content' => $content ));

  header("Location:php3-3sub.php");
}


// ----------- 以下、データ取得処理 -----------
  //DBへの接続準備
  $dbh = dbConnection();

  //SQL文（クエリー作成）
  $stmt = $dbh->prepare( 'SELECT * FROM posts');
  
  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute();

  $posts = $stmt->fetchAll();
  
  //レコードの件数を取得,下記の投稿一覧部分のif文の判定式にで使用する
  $count = $stmt->rowCount();

  
// ----------- 以下、データ削除処理 -----------

$delete_id = (int)$_POST['submit_delete'];

if($delete_id){

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

  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);

  //SQL文（クエリー作成）
  $stmt = $dbh->prepare( 'DELETE FROM posts WHERE id = :id ');

  $stmt->bindParam( ":id", $delete_id);

  //プレースホルダに値をセットし、SQL文を実行
  $stmt->execute();

  header("Location:php3-3deleted.php");
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>php3-3</title>
</head>
<body>
  <h1>掲示板</h1>

  <h2>新規投稿</h2>

  <form  method="post">
    name: <input type="text" name="name">
    <p>投稿内容</p><textarea name="content" id="" cols="30" rows="10"></textarea>
    <p><input type="submit" name="submit_create" value="投稿"></p>
  </form>

  <h2>投稿内容一覧</h2>

  <?php

    if($count){
      $i = 1 ;
          foreach( $posts as $key=>$post ){ ?>
      
            <div class="post">
              <p >No: <?php echo $i ;?></p>
              <p>名前: <?php echo $post['name']; ?></p>
              <p>投稿内容: <?php echo $post['content'] ?></p>
              <p><a href="php3-3edit.php?id=<?php echo $post['id']; ?>"><button >編集</button></a></p>
              <form method="post">
                <p><button type="submit"  name="submit_delete" value= "<?php echo $post['id']; ?>" placeholder="削除">削除</button></p>
              </form>
            </div>
      
            <?php $i++; ?>

    <?php  } ?>
  <?php  } ?>
  
</body>
</html>