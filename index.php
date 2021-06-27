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
$rec_time=array();

for ($i = 1; $i <= $result['_id']; (int)$i=(int)$i+1) {
   $stmt = $dbh->query("SELECT * FROM human WHERE _id='".$i."'");
   $rec = $stmt->fetch(PDO::FETCH_ASSOC);
   (int)$rec_max=(int)$rec_max+1;
  if(!empty($rec)){
   $rec_name[$i]=$rec['human_name'];
   $rec_i[$i]=$rec['ibent_i'];
   $rec_k[$i]=$rec['ibent_k'];
   $rec_ibent[$i]=$rec['ibent_name'];
   $rec_com[$i]=$rec['ibent_com'];
   $rec_time[$i]=$rec['ibent_torokubi'];
   $rec_time[$i]=str_replace("-","/",$rec_time[$i]);
   if($rec_time[$i]<date("Y/m/d", strtotime('-1 day'))){
    $stmt = $dbh->query ("DELETE FROM human WHERE _id='".$i."'");
   }
  }else{
  $rec_name[$i]='0';
 }
}
 $name_json = json_encode($rec_name);
 $i_json = json_encode($rec_i);
 $k_json = json_encode($rec_k);
 $ibent_json = json_encode($rec_ibent);
 $com_json = json_encode($rec_com);
 $time_json = json_encode($rec_time);
?>
<html lang="ja">
<html>
    <head>
     <meta http-equiv="Pragma" content="no-cache" />
     <meta http-equiv="cache-control" content="no-cache" />
     <meta http-equiv="expires" content="0" />
        <title>app</title>
        <meta charset="UTF-8">
        <link href="test.css" rel="stylesheet">
    <body>
        <font size="7">密アプリ </div></font><br>
 
	  <div class="container">
          <div id="maps" style="width:620px; height:400px" class="main"></div>
    	  <div class="side" >
密アプリとは気軽に知らない人と気軽に密を作れる出会い系WEBアプリです。<br>
使い方A<br>
1.フォームに適当なことを書きましょう<br>
2.地図に情報が表示されるので待ちます<br>
3.知らない人が来るので楽しみましょう<br>
使い方B<br>
1.地図に表示された場所に向かいます<br>
2.誰かと会えます<br>
更新されない場合はキャッシュを切ってみてください<br>
          </div>
         </div>
 
         <footer>
           <div id="foot" style="width:700px; height:300px" class="main">
              <form action="#" method="post">
               <p>お名前<br></p>
               <p><input type="text" name="namet"value="名無しさん" ></p>
               <p>密の名前<br>
               <input type="text" name="pname"></p>
               <p>密の説明<br>
               <textarea name="comment" cols="30" rows="5"></textarea></p>
               <p><input type="submit" value="密の作成！" name="add" onclick="alert('作成しました');document.cookie = 'my='+(<?php echo ($rec_max) ?>+1);location.reload();"></p>
              <form/>
           </div>
         </footer>
 
        </div>

<script type="text/javascript" src="map.js"></script>
<script>
 var max2 = <?php echo $rec_max; ?>; 
 var ido = JSON.parse('<?php echo $i_json; ?>');
 var k =JSON.parse('<?php echo $k_json; ?>');
 var ibent = JSON.parse('<?php echo $ibent_json; ?>');
 var com= JSON.parse('<?php echo $com_json; ?>');
 var named = JSON.parse('<?php echo $name_json; ?>');
 var time=JSON.parse('<?php echo $time_json; ?>');
    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtBCSIGzsOd0g33UWcJNZKJNTUsg6MJJ8&callback=initMap">
    </script>
    <input type="button" value="作った密を消す" onclick="ButtonClick();">
<script>
//削除する
function ButtonClick() {
 alert("消しました");
 <?php
 $cook=$_COOKIE['my'];
 $stmt = $dbh->query ("DELETE FROM human WHERE _id='".$cook."'");
 ?>
 document.cookie = 'my=0';
 location.reload();
}
</script>
    </body>
 <font size="1"><br>苦情:<address><a href="https://twitter.com/syfty0">作った人のツイッター</a></address><br></font>
</html>