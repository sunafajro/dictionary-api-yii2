<?php

namespace app\models;

use Yii;
use Google_Client;
use Google_Service_Sheets;
use yii\base\Model;

class GoogleApi extends Model {
    public function getSpreadsheet($spreadsheetId, $range)
    {
        $client = $this->getClient();
        $service = $this->getService($client);
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        return $response->getValues();
    } 
    private function getService($client)
    {
        return new Google_Service_Sheets($client);
    }
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Docs API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(Yii::getAlias('@config/credentials.json'));
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = Yii::getAlias('@config/token.json');
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}