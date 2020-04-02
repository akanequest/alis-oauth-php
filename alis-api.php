<?php
$keys = require('keys.php');

//ALISのユーザー情報を取得する
function api_me_info(){
    $options = makeConnectOptions(null,null);
    $api  = file_get_contents('https://alis.to/oauth2api/me/info', false, $options);
    return checkApiError($api, 'api_me_info', null);
}


$count_retry = 0;
//通信に失敗していないか調べる
function checkApiError($data, $func, $param){
    if ($data != null){
        $json = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);

        if (array_key_exists('message', $arr)){
            if ($arr['message'] == 'Unauthorized'){
                if (updateToken()){
                    global $count_retry;
                    if (++$count_retry <= 3){
                        if ($param != null){
                            return $func($param);
                        }else{
                            return $func();
                        }
                    }
                    $count_retry = 0;
                    error_oauth();
                }
            }else{
                throw new Exception('[ERROR] '.$arr['message']);
            }
        }else{
            return $arr;
        }
    }
    return null;
}

//OAuth関係 -----------------------------------------------------------------------------------------

//アクセストークンを取得する
function getToken_andSet(){
    global $keys;
    $redirect_uri  = $keys['uri'];
    $code_verifier = $_SESSION[$keys['index']['verifier']];
    $code = $_GET['code'];

    $data = array(
        'grant_type'=>'authorization_code',
        'code'=>$code,
        'redirect_uri'=>$redirect_uri,
        'code_verifier'=>$code_verifier,
    );
    $options = makeConnectOptions($data, makeBasicAuthorization());

    $api  = file_get_contents('https://alis.to/oauth2/token', false, $options);
    $json = mb_convert_encoding($api, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

    $arr = json_decode($json,true);
    if (array_key_exists('access_token',$arr) == false){
        error_oauth();
    }else{
        $_SESSION[$keys['index']['token']] = $arr['access_token'];
        $_SESSION[$keys['index']['rtoken']] = $arr['refresh_token'];
    }
}
//リフレッシュトークンを使用してアクセストークンを再取得する
function updateToken(){
    global $keys;
    $data = array(
        'grant_type'=>'refresh_token',
        'refresh_token'=>$_SESSION[$keys['index']['rtoken']],
    );
    $options = makeConnectOptions($data, makeBasicAuthorization());

    $api  = file_get_contents('https://alis.to/oauth2/token', false, $options);
    $json = mb_convert_encoding($api, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

    $arr = json_decode($json,true);
    if (array_key_exists('access_token',$arr) == false){
        //error_oauth();
        return false;
    }
    $_SESSION[$keys['index']['token']] = $arr['access_token'];
    return true;
}
function error_oauth(){
    $_SESSION = array();
    session_destroy();
    throw new Exception('[ERROR] アクセストークンが取得できませんでした。もう一度ログインし直してください。');
}


function makeBasicAuthorization(){
    global $keys;
    $text = $keys['cid'].':'.$keys['cs'];
    $auth = base64_encode($text);

    return 'Basic '.$auth;
}
function makeConnectOptions($data, $auth){
    global $keys;
    if ($auth == null){
        $auth = $_SESSION[$keys['index']['token']];
    }
    if ($data != null){
        $options = stream_context_create([
            "http" => [
                "method"=> "POST",
                "header"=> implode("\r\n", array(
                    "Authorization: ${auth}",
                    "Content-Type: application/x-www-form-urlencoded",
                )),
                "ignore_errors" => true,
                "timeout" => 5,
                "content" => http_build_query($data),
            ],
            "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
        ]);        
    }else{
        $options = stream_context_create([
            "http" => [
                "method"=> "GET",
                "header"=> implode("\r\n", array(
                    "Authorization: ${auth}",
                    "Content-Type: application/json",
                )),
                "ignore_errors" => true,
                "timeout" => 5
            ],
            "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
        ]);
    }
    return $options;
}

?>