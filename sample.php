<html>
<head><title>ALIS OAuth サンプル</title></head>
<body>

<?php
session_start();
$keys = require('keys.php');

//セッションが残っているか？
if (array_key_exists($keys['index']['token'],$_SESSION)){
    require('./alis-api.php');

    $meinfo = api_me_info();
    if ($meinfo != null){
        $name = $meinfo['user_display_name'];
    }
}

if (isset($name)){
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