# google_drive_api_sample
google drive apiを使用するサンプル

# 概要
以下のページのサンプルが利用できることを確認する。ただしGMailは不要なので、google driveのみ。
https://tech-blog.rakus.co.jp/entry/20180725/google-apis/google-cloud-platform/quickstart

#手順補足
プロジェクト名：schedule
組織なし
google drive APIを有効にする
※gmailはいったん不要
認証の作成
OAuthクライアントIDの作成

その他のクライアント
※ここではその他。webアプリケーションでは、webアプリケーションのクライアントを作成する必要がある。
クライアントID
XXXXXXXXXXXXXXXXXXXX
クライアントシークレット
XXXXXXXXXXXXXXXXXXXX

OAuth同意画面
外部を選択
アプリケーション名:schedule

composer require google/apiclient:^2.0

認証情報をダウンロード
ダウンロードしたjsonの名前をcredentials.jsonに変更して、quiclstart.phpと同じフォルダへ移動

コード
XXXXXXXXXXXXX

以下コマンドでgoogle driveのファイル一覧が取得できればOK
php quickstart.php
