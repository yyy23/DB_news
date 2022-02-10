<!-- ＜表示機能  作成 >  -->
<?php 
//エラーの表示（開発中のみ使用）
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL);


//変数の定義
$is_title = isset($_POST["title"]); //$_POST["title"]が未定義もしくはnullの場合、$is_titleにfalseが格納される・違う場合はtrueが格納される
if($is_title === true) { //$is_titleがtrueだったらifの処理、falseだったらelseの処理
  $title = $_POST["title"];  //$_POST["title"]が$titleに格納される
}else { 
  $title =  ""; //$titleに空文字が格納される 
}


$is_article = isset($_POST["article"]);  //$_POST["article"]が未定義もしくはnullの場合、$is_articleにfalseが格納される・違う場合はtrueが格納される
if($is_article === true) {  //$is_articleがtrueだったらifの処理、falseだったらelseの処理
  $article = trim($_POST["article"]); //$_POST["article"]が両端のスペースを消して(trim)$articleに格納される
}else {
  $article = "";  //$articleに空文字が格納される 
}

//$error_messageに空の配列を代入する＝初期化
$error_message = [];

//sqlに使用変数の初期化
$pdo = null;
$sql_set = null;


//データベースに接続する処理（使用するDBのドライバー、DB名、ホスト名、ユーザー名、パスワード）
    $pdo = new PDO('mysql:dbname=board;host=localhost', 'root', 'root');
    $pdo -> query('SET NAMES utf8;');  //文字化けを防ぐための処理

$is_sent = isset($_POST["send_submit"]);  //$_POST["send_submit"]が未定義もしくはnullの場合、・違う場合はtrueが格納される
if($is_sent === true) { 

    //SQLの作成  
    $sql_set = $pdo -> prepare("INSERT INTO posts (title, article)  
    VALUES( :title, :article)");  // テーブル:postsに投稿内容(title,article)を登録する

    $sql_title = $_POST["title"];  //$sql_title に $_POST["title"]を格納する
    $sql_article = $_POST["article"];  //$sql_article に $_POST["article"]を格納する

    //クエリの値の設定
    $sql_set -> bindValue(':title', $sql_title, 2);  //第3引数"2"はinteger型のPDO::PARAM_STRを表す
    $sql_set -> bindValue(':article', $sql_article, 2);
    
    //クエリの実行
    $sql_set -> execute();


    $sql_set = $pdo -> prepare("SELECT title, article FROM posts ORDER BY id DESC");  //デーブル:postsからidを降順して、title,articleを取得

    $sql_set -> bindValue(':title', $sql_title, 2);  //第3引数"2"はinteger型のPDO::PARAM_STRを表す
    $sql_set -> bindValue(':article', $sql_article, 2);

   //クエリの実行
    $sql_set -> execute();

    $ary_item = $sql_set -> fetchAll(PDO::FETCH_ASSOC);  //$sql_setの結果セットを$ary_itemに配列で格納
   
}    
?>


<!--以下、HTMLの書き込み-->

<!DOCTYPE html>
<html lang="ja">
<head>  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scal=1.0"> <!--表示領域設定：端末画面の幅、初期ズーム倍率-->

  <title>Laravel-News</title>
  <link rel="stylesheet" type="text/css" href="laravel-news.css"/>
</head> 

<header>
  <div class="nav-bar">
  <a href="http://localhost/index.php">Laravel-News</a> <!--TOP画面へのリンク-->
</div>
</header>

<body>
  <h2>皆さんのトレンドニュースを教えてください★</h2>

  <form action= "index.php" method= "POST">  <!--ファイル、methodの指定-->
    <p>タイトル： <input type= "text" name= "title"></p><br>  <!--タイトル入力部分の作成-->
    <p>記事： <textarea name= "article" cols= "30" rows= "10"></textarea></p><br>  <!--記事入力部分の作成-->
    <input type= "submit" name= "send_submit" value= "投稿">  <!--投稿ボタンの作成-->
  </form>
  <hr>
  <?php
    $i = 0;  //添字変数$iを宣言、0を$iに格納

    while( $i < $ary_item) {  //$ary_item数の分だけループさせる
      $toukou = $ary_item[$i];  //$toukouに$ary_item[$i]を格納  [$i]はループ数を表す
    ?>
    <p><?php echo $toukou["title"]; ?></p>  <!-- $toukou["title"]を表示 -->
    <p><?php echo $toukou["article"]; ?></p>  <!-- $toukou["article"]を表示 -->
    <a href="http://localhost/comment">記事全文・コメントを読む</a>  <!--コメントページへのリンク作成 -->
    <hr>
    <?php 
    $i =  $i + 1 ;//$iは$iに１プラスする
    }
    ?>
<a href="http://localhost/comment">記事全文・コメントを読む</a>  <!--コメントページへのリンク作成 -->  
  <hr>
</body>
</html>