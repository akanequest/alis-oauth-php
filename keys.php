<?php
return [
    //ALISに登録したアプリケーションの情報を記入する
    'cid' => '',
    'cs'  => '',
    'uri' => '/callback.php',

    //セッションのキーを隠蔽する時に使う。値は自由に変更してOK
    'index' => array(
        'verifier'  => 'code-verifier',
        'token'     => 'access-token',  //アクセストークン
        'rtoken'    => 'refresh-token',  //リフレッシュトークン
    ),
];

?>