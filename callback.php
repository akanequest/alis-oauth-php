<?php
session_start();
try{
    $keys = require('keys.php');

    $token = getToken($keys);
    $_SESSION[$keys['index']['token']] = $token;
    $_SESSION[$keys['index']['user-id']] = getID($token);

    header('Location: ./sample.php');
    exit;
}catch (Exception $e){
    echo $e->getMessage();
}

//アクセストークンを取得する
function getToken($keys){
    $redirect_uri  = $keys['uri'];
    $code_verifier = $_SESSION[$keys['index']['verifier']];
    $code = $_GET['code'];
    $_SESSION[$keys['index']['res-code']] = $code;

    $text = $keys['cid'].':'.$keys['cs'];
    $auth = base64_encode($text);

    $data = array(
        'grant_type'=>'authorization_code',
        'code'=>$code,
        'redirect_uri'=>$redirect_uri,
        'code_verifier'=>$code_verifier,
    );
    $options = stream_context_create([
        "http" => [
            "method"=> "POST",
            "header"=> implode("\r\n", array(
                "Authorization: Basic ${auth}",
                "Content-Type: application/x-www-form-urlencoded",
            )),
            "ignore_errors" => true,
            "timeout" => 5,
            "content" => http_build_query($data),
        ],
        "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
    ]);
    $api  = file_get_contents('https://alis.to/oauth2/token', false, $options);
    $json = mb_convert_encoding($api, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

    $arr = json_decode($json,true);
    if (array_key_exists('access_token',$arr) == false){
        $_SESSION = array();
        session_destroy();
        throw new Exception('[ERROR] アクセストークンが取得できませんでした。もう一度やり直してください。');
    }
    return $arr['access_token'];
}

//ALISのユーザーIDを取得する
function getID($token){
    $options = stream_context_create([
        "http" => [
            "method"=> "GET",
            "header"=> implode("\r\n", array(
                "Authorization: ${token}",
                "Content-Type: application/json",
            )),
            "ignore_errors" => true,
            "timeout" => 5
        ],
        "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
    ]);
    $api  = file_get_contents('https://alis.to/oauth2api/me/info', false, $options);
    $json = mb_convert_encoding($api, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr = json_decode($json,true);
    return $arr['user_id'];
}


?>