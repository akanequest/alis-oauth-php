<?php
session_start();
$keys = require('keys.php');
$client_id     = $keys['cid'];
$client_secret = $keys['cs'];
$redirect_uri  = $keys['uri'];

$code_verifier = get_code_verifier();
$code_challenge = get_code_challenge($code_verifier);
$_SESSION[$keys['index']['verifier']] = $code_verifier;

$url = "https://alis.to/oauth-authenticate?client_id=${client_id}&redirect_uri=${redirect_uri}&scope=read&code_challenge=${code_challenge}";
header('Location: '.$url);

exit;

function get_code_challenge($str) {
    $challenge_bytes = hash("sha256", $str, true);
    return rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");
}

function get_code_verifier() {
    $verifier_bytes = random_bytes(64);
    return rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
}

?>