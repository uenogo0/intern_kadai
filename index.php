<?php
date_default_timezone_set('Asia/Tokyo');
error_reporting(-1);

define('DB_DNS', 'mysql:host=localhost; dbname=xs807734_data1; charset=utf8');
define('DB_USER', 'xs807734_user');
define('DB_PASSWORD', 'pass1234');

try {
  $dbh = new PDO(DB_DNS, DB_USER, DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e){
    echo $e->getMessage();
    exit;
}

/* データベースへ登録 */
   $sql = 'SELECT _id FROM human WHERE  _id=(SELECT MAX(_id) FROM human);';
   $stmt = $dbh->query($sql);
   $result = $stmt->fetch(PDO::FETCH_ASSOC);
if(!empty($_POST['add'])){
  try{
   $num=$result['_id']+1;

   $sql  = 'INSERT INTO human VALUES(:ID,:NAME,:i,:k,:PNAME,:COM,:CUR)';
   $stmt = $dbh->prepare($sql);
   $stmt->bindParam(':ID', $num, PDO::PARAM_STR);
   $stmt->bindParam(':NAME', $_POST['namet'], PDO::PARAM_STR);
   $stmt->bindParam(':PNAME', $_POST['pname'], PDO::PARAM_STR);
   $stmt->bindParam(':COM', $_POST['comment'], PDO::PARAM_STR);
   $date=date("Y/m/d");
   $stmt->bindParam(':CUR', $date, PDO::PARAM_STR);

   $stmt->bindParam(':i', $_COOKIE["ido"], PDO::PARAM_STR);
   $stmt->bindParam(':k', $_COOKIE["keido"], PDO::PARAM_STR);
   $stmt->execute();


   $uri = $_SERVER['HTTP_REFERER'];
   header("Location: ".$uri);
   exit();
  } catch (PDOException $e) {
      echo '何らかのエラー！'.$e->getMessage();
  }
}

$rec_name=array();
$rec_i=array();
$rec_k=array();
$rec_ibent=array();
$rec_com=array();
$rec_max=0;
$test='asd';

for ($i = 0; $i <= $result['_id']; $i++) {
   $stmt = $dbh->query("SELECT * FROM human WHERE _id='".$i."'");
   $rec = $stmt->fetch(PDO::FETCH_ASSOC);
   $rec_name['_id']=$rec['human_name'];
   $rec_i['_id']=$rec['ibent_i'];
   $rec_k['_id']=$rec['ibent_k'];
   $rec_ibent['_id']=$rec['ibent_name'];
   $rec_com['_id']=$rec['ibent_com'];
   $rec_max=$i;
}
?>
<html lang="ja">
<html>
    <head>
        <title>app</title>
        <meta charset="UTF-8">
        <link href="test.css" rel="stylesheet">
    <body>
        <div>Many people Matching application </div>

<div id="drag-area">
</div>
 
<!--	  <header>
　　　　　　<a href="login.php">ログインはこちら</a>
　　　　　　<a href="login.php">会員登録はこちら</a>
	  </header>-->

 
	  <div class="container">
          <div id="maps" style="width:620px; height:400px" class="main"></div>
    	  <div class="side">side</div>
         </div>
 
         <footer>
           パーティーの作成
           <div id="foot" style="width:700px; height:300px" class="main">
              <form action=# method="post">
               <p>お名前<br>
               <input type="text" name="namet"value="名無しさん" ></p>
               <p>パーティー名<br>
               <input type="text" name="pname"></p>
               <p>コメント<br>
               <textarea name="comment" cols="30" rows="5"></textarea></p>
               <p><input type="submit" value="作成！" name="add"></p>
              <form/>
           </div>
         </footer>
 
        </div>

<script type="text/javascript" src="map.js">
</script>
    
    <!-keyは親のクレカで払ったからあんまり使って欲しくない->
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtBCSIGzsOd0g33UWcJNZKJNTUsg6MJJ8&callback=initMap">
    </script>
    </body>
</html>