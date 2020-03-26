# alis-oauth-php
ALISのOAuth認証をPHPで行うサンプルです。<br/>
アクセストークンが失効した場合に再取得する処理は今のところありません。<br/>
<br/>
ALIS公式の利用ガイドラインはこちら
https://alisproject.github.io/oauth2/term/
<br/>
使い方
<ol>
  <li>ALISで<a href="https://alis.to/me/settings/applications">アプリケーション登録</a>を行う<br/>内容は後から変更できるので最初は適当でOK</li>
  <li>keys.phpにClientIDなどを記入する<br/>リダイレクトURIにはcallback.phpを指定すること（アプリケーションの設定と合わせる必要あり）</li>
  <li>sample.phpをブラウザで表示する</li>
</ol>
<br/>
現在このサンプルでは、次の情報をセッションに保存します。
<ul>
  <li>code_verifier（同意画面に遷移する際に必要なもの）</li>
  <li>許可コード（リダイレクト時に受け取る）</li>
  <li>アクセストークン（各種APIを使用する時に必要）</li>
  <li>ALISのユーザーID</li>
</ul>
