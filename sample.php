<html>
<head><title>ALIS OAuth サンプル</title></head>
<body>

<?php
$islogin = require('alis-oauth.php');
if ($islogin){
    $result = <<<TEXT
<p>{$name} でログイン中</p>
<form action="logout.php" method="post" enctype="multipart/form-data">
<p><input class="button" type="submit" value="ログアウトする" /></p>
</form>
TEXT;
}else{
    $result = <<<TEXT
<form action="oauth.php" method="post" enctype="multipart/form-data">
<p><input class="button" type="submit" value="ALISでログイン" /></p>
</form>
TEXT;
}
echo $result;
?>

</body>
</html>