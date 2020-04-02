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

return isset($name); //ログインしているか
?>