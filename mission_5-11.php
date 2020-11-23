<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="UTF-8">
 <title>ミッション５</title>
</head>
<body>
<?php
//DB接続設定
$dsn = 'データベース名';
    $user = 'ユーザー名';  
	$password = 'パスワード';
	// データベース内でエラーが起こった際に警告（warning）を出す
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	 //データベース内にテーブルを作成
	 $sql="CREATE TABLE IF NOT EXISTS m5"
	 ."("
	 ."id INT AUTO_INCREMENT PRIMARY KEY,"
	 ."name char(32),"
	 ."comment TEXT,"
	 ."date TIMESTAMP DEFAULT CURRENT_TIMESTAMP," //現在の日時を返す　https://johobase.com/sqlserver-datetime-function/
	 ."pass TEXT"
	 .");";
	 $stmt=$pdo->query($sql);

$sql='SHOW TABLES';  //tbtest（現段階）を表示するための変数
$result=$pdo->query($sql);
foreach($result as $row){
	echo$row[0];
	echo'<br>';
}

$sql='SHOW CREATE TABLE m5';
$result=$pdo->query($sql);
foreach((array)$result as $row){  //(array)は強制的に配列にしている
	echo$row[1];     //()の中身が表示される
}
echo"<hr>";


//編集フォーム
//編集されたなら、投稿データを更新
if(isset($_POST["edit"])){
    $editNo=$_POST["editNo"]; 
    $editpass=$_POST["editpass"];
    $sql='SELECT* FROM m5 WHERE id=:id';
    $stmt=$pdo->prepare($sql); //差し替えるパラメータを含めて記述したSQLを準備する
    $stmt->bindParam(':id',$editNo,PDO::PARAM_INT); 
    $stmt->execute();   //sqlを実行する
    $results=$stmt->fetchAll();
    foreach($results as $row){
    //投稿番号と編集対象番号が一致したらその投稿の「名前」と「コメント」を取得
    if($editNo==($results[0])['id']){
        $editnumber=$row['id'];
        $editname=$row['name']; 
        $editcomment=$row['comment'];
        //既存の投稿フォームに、上記で取得した「名前」「コメント」の内容が既に入っている状態で表示させる
        //formのvalue属性で対応
    }

/*$editNo=$_POST["editNo"]; //変更する投稿番号*/
//ここの部分の工夫が必要 nameとcommentは要らない
$sql='UPDATE m5 SET name=:name,comment=:comment WHERE (id=:id) AND (pass=:editpass)'; //WHERE構文は特定のデータを抽出する
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':name',$editname,PDO::PARAM_STR);
$stmt->bindParam(':comment',$editcomment,PDO::PARAM_STR);
$stmt->bindParam(':id',$editnumber,PDO::PARAM_INT);
$stmt->bindParam(':editpass',$editpass,PDO::PARAM_STR);
$stmt->execute();
}
}

?>
        <form action="mission_5-11.php"method="post">
            <h>投稿フォーム</h>
           <p><input type="structure"name="na"placeholder="名前"value="
           <?php if(isset($editname)) {echo $editname;}?>">
           <input type="structure"name="str"placeholder="コメント"value="
           <?php if(isset($editcomment)){echo $editcomment;}?>">
           <input type="hidden"name="editNo"value="
           <?php if(isset($editnumber)){echo $editnumber;}?>">
            <input type="structure"name="pass"placeholder="パスワード">
            <input type="submit"name="submit"value="送信"></p>
            </form>
            
            <form action="mission_5-11.php"method="post">
            <h>削除フォーム</h>
         <p>
             <input type="number"name="deleteNo"placeholder="削除対象番号">
             <input type="structure"name="delpass"placeholder="パスワード">
             <input type="submit"name="delete"value="削除">   
        </p>
        </form>
        
        <form action="mission_5-11.php"method="post">
        <h>編集フォーム</h>
        <p>
            <input type="number"name="editNo"placeholder="編集対象番号"> 
            <input type="structure"name="editpass"placeholder="パスワード">
            <input type="submit"name="edit"value="編集">
            </p>
        </form>
 <?php
//DB接続設定
$dsn = 'データベース名';
	$user = 'ユーザー名';  
    $password = 'パスワード';
    //データベースへの登録
    //投稿されたなら、投稿データを記録
  if(isset($_POST["submit"])){
$na=$_POST["na"]; 
$str=$_POST["str"];
$pass=$_POST["pass"];
date_default_timezone_set("Asia/Tokyo");
$date=date("Y/m/d H:i:s");
$pdo=new PDO($dsn,$user,$password);
$sql=$pdo->prepare("INSERT INTO m5 (name,comment,pass,date) VALUES(:name,:comment,:pass,:date)"); 
    $sql->bindParam(':name',$na,PDO::PARAM_STR); 
    $sql->bindParam(':comment',$str,PDO::PARAM_STR); 
    $sql->bindParam(':pass',$pass,PDO::PARAM_STR);
    $sql->bindParam(':date',$date,PDO::PARAM_STR);
    $sql->execute();
//投稿内容表示
    $sql='SELECT* FROM m5';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
       echo$row["id"].',';
       echo$row["name"].',';
       echo$row["comment"].',';
       echo$row["date"].'<br>';
       echo'<hr>';
    }
}

  
  
 //編集済みの投稿フォームの内容を表示
    //編集済み投稿フォーム表示
        if(isset($_POST["edit"])){
            $editNo=$_POST["editNo"];
        if($editNo==($results[0])['id']){
            $editnumber=$row['id'];
            $editname=$row['name']; 
            $editcomment=$row['comment'];
    $sql='SELECT* FROM m5 WHERE id=:id';
    $stmt=$pdo->prepare($sql); //差し替えるパラメータを含めて記述したSQLを準備する
    $stmt->bindParam(":id",$editnumber,PDO::PARAM_INT); 
    $stmt->execute();   //sqlを実行する
    $results=$stmt->fetchAll();
    foreach($results as $row){
      echo$row["id"].',';
      echo$row["name"].',';
      echo$row["comment"].',';
      echo$row["date"].'<br>';
      echo'<hr>';
  }
}
}
    
//削除フォーム
//削除されたなら、投稿データを削除
//"delete"が投稿されたら
if(isset($_POST["delete"])){
    $delete=$_POST["deleteNo"]; //$deleteの定義付け 
	$delpass=$_POST["delpass"];
    
    $pdo=new PDO($dsn,$user,$password);
	$id=$_POST["deleteNo"];
	$sql='DELETE FROM m5 WHERE (id=:id) AND (pass=:delpass)';
	$stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
    $stmt->bindParam(':delpass',$delpass,PDO::PARAM_INT);
    $stmt->execute();
//削除機能表示
$sql='SELECT*FROM m5';
$stmt=$pdo->prepare($sql);
$stmt->bindParam(':id',$id,PDO::PARAM_INT);
$stmt->bindParam(':delpass',$delpass,PDO::PARAM_STR);
$stmt->execute();
$results=$stmt->fetchAll();
foreach($results as $row){
	echo$row["id"].',';
	echo$row["name"].',';
    echo$row["comment"].',';
    echo$row["date"].'<br>';
echo"<hr>";
}
}
 ?>

 </body>
 </html>