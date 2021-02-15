
<?php


//データベースへの接続

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


//それぞれの要件を定義

if(isset($_POST['name'])) {
    $name = $_POST['name'];
}

if(isset($_POST['comment'])) {
    $comment = $_POST['comment'];
}

if(isset($_POST['password'])) {
    $pass = $_POST['password'];
}

if(isset($_POST['delpass'])) {
    $delpass = $_POST['delpass'];
}

if(isset($_POST['edipass'])) {
    $edipass = $_POST['edipass'];
}

$date =date("Y/m/d H:i:s");


//名前・コメント・パスが入力された時

if(!empty($name) && !empty($comment) && !empty($pass)){

    //アップデートされる場合    
        if(!empty($_POST['number'])){
            $id = $_POST['number']; //変更する投稿番号
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
            $stmt->execute();
        }else{
            //新規投稿
            $sql = "INSERT INTO tbtest (name,comment,date,password) VALUES ('$name','$comment','$date','$pass')";
            $stmt = $pdo -> query($sql);
        }
    
}

//編集ナンバーとパスの入力（編集）

if(!empty($_POST['editNo'])){
    $id = $_POST['editNo'];  //目印の設定
    
    //編集したい投稿のパスワードの取得
    $sql = 'SELECT password FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll(); 
    foreach($results as $row){
            $pass = $row['password'];
    }

    //パスワードが一致したら処理
    if($pass == $edipass ){
        $sql = 'SELECT * FROM tbtest WHERE id = :id ';
        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach($results as $row){
            $value0 = $row['id'];
            $value1 = $row['name'];
            $value2 = $row['comment'];
        }
    }
    
}

//削除ナンバーとパスの入力（削除）

if(!empty($_POST['deleteNo'])){
    $id = $_POST['deleteNo'];
    
    //削除したい投稿のパスワードの取得
    $sql = 'SELECT password FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll(); 
    foreach($results as $row){
            $pass = $row['password'];
    }

    if($pass == $delpass ){
    $sql = 'delete from tbtest where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset ="UTF=8">
    <title>mission5-1</title>
</head>  

<body>


    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($value1)){echo $value1;}?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($value2)){echo $value2;}?>"><br>
        <input type="hidden" name="number" value="<?php if(isset($value0)){echo $value0;}?>">
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="submit"><br>
        <input type="text" name="deleteNo" placeholder="削除対象番号">
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="deletebt" value="削除"><br>
        <input type="text" name="editNo" placeholder="編集対象番号">
        <input type="text" name="edipass" placeholder="パスワード">
        <input type="submit" name="editbt" value="編集"><br>
    </form>


<?php

//画面に表示

    //SELECT(投稿の取得)
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date']."<br>";
        echo "<hr>";
    }
?>

</body>    
</html>