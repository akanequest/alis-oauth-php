<?php
return [
    //ALISに登録したアプリケーションの情報を記入する
    'cid' => '',
    'cs'  => '',
    'uri' => '/callback.php',

    //セッションのキーを隠蔽する時に使う。値は自由に変更してOK
    'index' => array(
        'user-id'   => 'alis-userid',
        'verifier'  => 'code-verifier',
        'res-code'  => 'res-code',      //許可コード
        'token'     => 'access-token',  //アクセストークン
    ),
];

?>