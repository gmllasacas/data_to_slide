<?php
	require __DIR__ . '/vendor/autoload.php';
	define('APPLICATION_NAME', 'Google Slides');
	define('SCOPES', [
		Google_Service_Slides::PRESENTATIONS,
		Google_Service_Slides::DRIVE
	]);
	$client = new Google_Client();
	$client->setApplicationName(APPLICATION_NAME);
	$client->setScopes(SCOPES);
	$client->setAuthConfig('credentials/client_secret.json');
	$client->setAccessType('offline');
	$client->setPrompt('select_account consent');

	$tokenPath = 'credentials/token.json';
	if (file_exists($tokenPath)) {
		$accessToken = json_decode(file_get_contents($tokenPath), true);
		$client->setAccessToken($accessToken);
	}
	
	if ($client->isAccessTokenExpired()) {
		if ($client->getRefreshToken()) {
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		} else {
			$authUrl = $client->createAuthUrl();
			printf("Open the following link in your browser:\n%s\n", $authUrl);
			print 'Enter verification code: ';
			$authCode = trim(fgets(STDIN));
			$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
			$client->setAccessToken($accessToken);
			if (array_key_exists('error', $accessToken)) {
				throw new Exception(join(', ', $accessToken));
			}
		}
		if (!file_exists(dirname($tokenPath))) {
			mkdir(dirname($tokenPath), 0700, true);
		}
		file_put_contents($tokenPath, json_encode($client->getAccessToken()));
	}