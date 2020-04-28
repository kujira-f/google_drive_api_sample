<?php
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * 指定した権限が付与されたAPIクライアントを返す
 * @return 権限が付与されたGoogle_Clientオブジェクト
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('schedule');
    $client->setScopes(Google_Service_Drive::DRIVE); // ※1：スコープの設定
    $client->setAuthConfig('credentials.json');  // 取得したJSONファイルのパス
    $client->setAccessType('offline');

    // クライアント証明書ファイルが存在しない場合（初回実行時）は
    // 認証情報JSONファイルを用いて取得する。
    // 存在する場合（2回目以降の実行時）は、クライアント証明書を読み込む。
    $credentialsPath = 'token.json';  // クライアント証明書ファイルのパス
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // ユーザからの認証を行う
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // 認証コードをアクセストークンに変換する
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // クライアント証明書をファイルに保存する
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // クライアント証明書が有効期限切れの場合は更新し、ファイルへ保存しなおす
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * メイン処理
 */
// APIクライアントを作成し、サービスオブジェクトを作成する
$client = getClient();
$service = new Google_Service_Drive($client);  // 使用するAPIごとのサービスオブジェクトを作成

// Print the filels in the user's account.
$user = 'me';
$results = $service->files->listFiles($user);

if (count($results->getFiles()) == 0) {
  print "No Files found.\n";
} else {
  print "filels:\n";
  foreach ($results->getFiles() as $file) {
    printf("- %s\n", $file->getName());
  }
}
